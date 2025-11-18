<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with(['user', 'likes'])
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tweets.index', compact('tweets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ];

        // handle optional image upload
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'nullable|image|max:2048', // max 2MB
            ]);

            $path = $request->file('image')->store('tweets', 'public');
            $data['image_path'] = $path;
        }

        Tweet::create($data);

        return redirect()->route('home')->with('success', 'Tweet posted successfully!');
    }

    public function edit(Tweet $tweet)
    {
        if ($tweet->user_id !== Auth::id()) {
            abort(403);
        }

        return view('tweets.edit', compact('tweet'));
    }

    public function update(Request $request, Tweet $tweet)
    {
        if ($tweet->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $data = [
            'content' => $request->input('content'),
            'is_edited' => true,
        ];

        // handle optional new image upload
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'nullable|image|max:5120', // up to 5MB
            ]);

            // delete old image if exists
            if ($tweet->image_path) {
                Storage::disk('public')->delete($tweet->image_path);
            }

            $path = $request->file('image')->store('tweets', 'public');
            $data['image_path'] = $path;
        }

        $tweet->update($data);

        return redirect()->route('home')->with('success', 'Tweet updated successfully!');
    }

    public function destroy(Tweet $tweet)
    {
        if ($tweet->user_id !== Auth::id()) {
            abort(403);
        }

        // delete image if present
        if ($tweet->image_path) {
            Storage::disk('public')->delete($tweet->image_path);
        }

        $tweet->delete();

        return redirect()->route('home')->with('success', 'Tweet deleted successfully!');
    }
}
