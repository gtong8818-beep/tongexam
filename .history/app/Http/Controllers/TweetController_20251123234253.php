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

            try {
                // Try to store using Storage facade first
                $path = $request->file('image')->store('tweets', 'public');
                $data['image_path'] = $path;
            } catch (\Exception $e) {
                // Fallback: store directly in public directory if storage fails
                try {
                    $tweetsDir = public_path('tweet_images');
                    if (!file_exists($tweetsDir)) {
                        mkdir($tweetsDir, 0755, true);
                    }
                    
                    $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                    $request->file('image')->move($tweetsDir, $imageName);
                    $data['image_path'] = 'tweet_images/' . $imageName;
                } catch (\Exception $fallbackError) {
                    \Log::error('Tweet image upload failed (all methods): ' . $fallbackError->getMessage());
                }
            }
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

            try {
                // delete old image if exists
                if ($tweet->image_path) {
                    Storage::disk('public')->delete($tweet->image_path);
                }

                // Store new image using Storage facade
                $path = $request->file('image')->store('tweets', 'public');
                $data['image_path'] = $path;
            } catch (\Exception $e) {
                // Log error but don't fail the tweet update
                \Log::error('Tweet image update failed: ' . $e->getMessage());
            }
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
            try {
                Storage::disk('public')->delete($tweet->image_path);
            } catch (\Exception $e) {
                \Log::error('Tweet image deletion failed: ' . $e->getMessage());
            }
        }

        $tweet->delete();

        return redirect()->route('home')->with('success', 'Tweet deleted successfully!');
    }
        }

        $tweet->delete();

        return redirect()->route('home')->with('success', 'Tweet deleted successfully!');
    }
}
