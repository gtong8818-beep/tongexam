<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::where('user_id', Auth::id())->latest()->get();
        return view('movies.index', compact('movies'));
    }

    public function create()
    {
        return view('movies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'reviews' => 'nullable|integer|min:1|max:10',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('movie_images'), $imageName);
            $data['image_path'] = 'movie_images/' . $imageName;
        }

        $data['user_id'] = Auth::id();
        
        Movie::create($data);
        
        return redirect()->route('movies.index')->with('success', 'Movie added successfully!');
    }

    public function show(Movie $movie)
    {
        if($movie->user_id !== Auth::id()) {
            abort(403);
        }
        return view('movies.show', compact('movie'));
    }

    public function edit(Movie $movie)
    {
        if($movie->user_id !== Auth::id()) {
            abort(403);
        }
        return view('movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        if($movie->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'reviews' => 'nullable|integer|min:1|max:10',
            'image' => 'nullable|image|mimes:jpeg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($movie->image_path && file_exists(public_path($movie->image_path))) {
                unlink(public_path($movie->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('movie_images'), $imageName);
            $data['image_path'] = 'movie_images/' . $imageName;
        }

        $movie->update($data);

        return redirect()->route('movies.index')->with('success', 'Movie updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        if($movie->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete the associated image if it exists
        if ($movie->image_path && file_exists(public_path($movie->image_path))) {
            unlink(public_path($movie->image_path));
        }

        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Movie deleted successfully!');
    }
}
