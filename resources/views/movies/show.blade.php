@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $movie->title }}</h4>
                </div>

                <div class="card-body">
                    @if($movie->image_path)
                        <div class="text-center mb-4">
                            <img src="{{ asset($movie->image_path) }}" alt="Movie poster" class="img-fluid" style="max-height: 400px; width: auto;">
                        </div>
                    @endif
                    @if($movie->reviews)
                        <div class="mb-3">
                            <strong>Rating:</strong> 
                            <span class="badge bg-primary">{{ $movie->reviews }}/10</span>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Review</h5>
                        <p class="card-text">{{ $movie->description }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Added:</strong> {{ $movie->created_at->format('F j, Y, g:i a') }}
                    </div>

                    @if($movie->updated_at && $movie->updated_at != $movie->created_at)
                        <div class="mb-3">
                            <strong>Last Updated:</strong> {{ $movie->updated_at->format('F j, Y, g:i a') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Back to List</a>
                        <div>
                            <a href="{{ route('movies.edit', $movie) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this movie?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection