@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Movie</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('movies.update', $movie) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if($movie->image_path)
                            <div class="mb-4">
                                <label class="form-label">Current Image</label>
                                <div class="text-center">
                                    <img src="{{ asset($movie->image_path) }}" alt="Movie poster" class="img-thumbnail" style="max-height: 200px; margin: 0 auto;">
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="image" class="form-label">Update Movie Poster (Optional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                id="image" name="image" accept="image/jpeg,image/png">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a new JPEG or PNG image (max 2MB)</div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Movie Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" value="{{ old('title', $movie->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Review</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="4" required>{{ old('description', $movie->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reviews" class="form-label">Rating (1-10)</label>
                            <input type="number" class="form-control @error('reviews') is-invalid @enderror" 
                                id="reviews" name="reviews" min="1" max="10" value="{{ old('reviews', $movie->reviews) }}">
                            @error('reviews')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave empty if you haven't watched it yet</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('movies.index') }}" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Update Movie</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection