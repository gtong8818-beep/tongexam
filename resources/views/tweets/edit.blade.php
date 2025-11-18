@extends('layouts.app')

@section('title', 'Edit Tweet')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold mb-4">Edit Tweet</h3>
        <form action="{{ route('tweets.update', $tweet) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <textarea name="content" id="content" rows="5" maxlength="280" required
                class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('content', $tweet->content) }}</textarea>
            
            <div class="mt-3 mb-4">
                @if($tweet->image_path)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $tweet->image_path) }}" alt="Current image" class="max-w-full h-auto rounded">
                    </div>
                @endif
                <label class="block text-sm text-gray-700 mb-1">Replace image (optional)</label>
                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-600" />
            </div>

            <div class="flex justify-between items-center mt-2">
                <span id="charCount" class="text-sm text-gray-500">{{ strlen($tweet->content) }} / 280</span>
                <div class="space-x-2">
                    <a href="{{ route('home') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-full">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('content').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('charCount').textContent = count + ' / 280';
    });
</script>
@endsection
