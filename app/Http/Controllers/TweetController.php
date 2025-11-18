<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        Tweet::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

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

        $tweet->update([
            'content' => $request->input('content'),
            'is_edited' => true,
        ]);

        return redirect()->route('home')->with('success', 'Tweet updated successfully!');
    }

    public function destroy(Tweet $tweet)
    {
        if ($tweet->user_id !== Auth::id()) {
            abort(403);
        }

        $tweet->delete();

        return redirect()->route('home')->with('success', 'Tweet deleted successfully!');
    }
}
