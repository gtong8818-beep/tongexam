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

        // Image uploads removed: tweets will be created without an image_path

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

        // Image uploads removed: updates no longer accept or process image files

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
            try {
                Storage::disk('public')->delete($tweet->image_path);
            } catch (\Exception $e) {
                // Try fallback deletion
                if (file_exists(public_path($tweet->image_path))) {
                    try {
                        unlink(public_path($tweet->image_path));
                    } catch (\Exception $fallbackError) {
                        \Log::error('Tweet image deletion failed: ' . $fallbackError->getMessage());
                    }
                }
            }
        }

        $tweet->delete();

        return redirect()->route('home')->with('success', 'Tweet deleted successfully!');
    }
}
