@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Create Tweet Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-bold mb-4">What's happening?</h3>
        <form action="{{ route('tweets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <textarea name="content" id="content" rows="3" maxlength="280" required
                class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="What's on your mind?"></textarea>
            <div class="mt-3">
                <label class="block text-sm text-gray-700 mb-1">Attach image (optional)</label>
                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-600" />
            </div>
            <div class="flex justify-between items-center mt-2">
                <span id="charCount" class="text-sm text-gray-500">0 / 280</span>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full">
                    Tweet
                </button>
            </div>
        </form>
    </div>

    <!-- Tweets List -->
    <div class="space-y-4">
        @forelse($tweets as $tweet)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <a href="{{ route('profile.show', $tweet->user) }}" class="font-bold text-lg text-blue-500 hover:underline">
                            {{ $tweet->user->name }}
                        </a>
                        <p class="text-sm text-gray-500">
                            {{ $tweet->created_at->diffForHumans() }}
                            @if($tweet->is_edited)
                                <span class="text-gray-400">(edited)</span>
                            @endif
                        </p>
                    </div>
                    
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

                @if($tweet->image_path)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $tweet->image_path) }}" alt="Tweet image" class="max-w-full h-auto rounded">
                    </div>
                @endif

                <div class="flex items-center space-x-4">
                    <form action="{{ route('tweets.like', $tweet) }}" method="POST" class="like-form">
                        @csrf
                        <button type="submit" class="flex items-center space-x-1 {{ $tweet->isLikedBy(auth()->user()) ? 'text-red-500' : 'text-gray-500' }} hover:text-red-500">
                            <span>{{ $tweet->isLikedBy(auth()->user()) ? '❤️' : '🤍' }}</span>
                            <span class="like-count">{{ $tweet->likes_count }}</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                No tweets yet. Be the first to tweet!
            </div>
        @endforelse
    </div>
</div>

<script>
    // Character counter
    document.getElementById('content').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('charCount').textContent = count + ' / 280';
    });

    // AJAX like toggle (optional bonus)
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            if (response.ok) {
                const data = await response.json();
                const button = this.querySelector('button');
                const likeCount = button.querySelector('.like-count');
                
                button.querySelector('span:first-child').textContent = data.liked ? '❤️' : '🤍';
                button.classList.toggle('text-red-500', data.liked);
                button.classList.toggle('text-gray-500', !data.liked);
                likeCount.textContent = data.likes_count;
            }
        });
    });
</script>
@endsection
