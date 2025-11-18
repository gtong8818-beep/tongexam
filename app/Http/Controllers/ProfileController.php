<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $tweets = $user->tweets()
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalLikes = $user->totalLikesReceived();
        $tweetCount = $user->tweets()->count();

        return view('profile.show', compact('user', 'tweets', 'totalLikes', 'tweetCount'));
    }
}
