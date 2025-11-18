@extends('layouts.app')

@section('title', $user->name . "'s Profile")

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- User Profile Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-3xl font-bold mb-2">{{ $user->name }}</h2>
        <p class="text-gray-600 mb-4">Joined {{ $user->created_at->format('F Y') }}</p>
        
        <div class="flex space-x-6">
            <div>
                <span class="font-bold text-lg">{{ $tweetCount }}</span>
                <span class="text-gray-600">Tweets</span>
            </div>
            <div>
                <span class="font-bold text-lg">{{ $totalLikes }}</span>
                <span class="text-gray-600">Likes Received</span>
            </div>
        </div>
    </div>

    <!-- User's Tweets -->
    <h3 class="text-xl font-bold mb-4">Tweets</h3>
    <div class="space-y-4">
        @forelse($tweets as $tweet)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-3">
                    <p class="text-sm text-gray-500">
                        {{ $tweet->created_at->diffForHumans() }}
                        @if($tweet->is_edited)
                            <span class="text-gray-400">(edited)</span>
                        @endif
                    </p>
                    
                    @if($tweet->user_id === auth()->id())
                        <div class="flex space-x-2">
                            <a href="{{ route('tweets.edit', $tweet) }}" class="text-blue-500 hover:text-blue-700">
                                Edit
                            </a>
                            <form action="{{ route('tweets.destroy', $tweet) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this tweet?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <p class="text-gray-800 mb-4">{{ $tweet->content }}</p>

                <div class="flex items-center space-x-4">
                    <form action="{{ route('tweets.like', $tweet) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center space-x-1 {{ $tweet->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-500' }} hover:text-red-500">
                            <span>{{ $tweet->isLikedBy(auth()->user()) ? '❤️' : '🤍' }}</span>
                            <span>{{ $tweet->likes_count }}</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                No tweets yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
