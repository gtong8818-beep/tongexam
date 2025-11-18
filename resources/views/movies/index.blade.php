@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>My Movies</h1>
                <a href="{{ route('movies.create') }}" class="btn btn-primary">Add New Movie</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @foreach($movies as $movie)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            @if($movie->image_path)
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <img src="{{ asset($movie->image_path) }}" alt="Movie poster" class="img-thumbnail" style="max-height: 200px; width: auto; margin: 0 auto;">
                                </div>
                            @endif
                            <div class="col">
                                <div class="d-flex flex-column">
                                    <div class="mb-3">
                                        <h5 class="card-title">{{ $movie->title }}</h5>
                                        @if($movie->reviews)
                                            <div class="text-muted mb-2">
                                                Rating: {{ $movie->reviews }}/10
                                            </div>
                                        @endif
                                        <p class="card-text">{{ Str::limit($movie->description, 100) }}</p>
                                    </div>
                                    <div class="mt-auto">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('movies.show', $movie) }}" class="btn btn-sm btn-info text-white">View</a>
                                            <a href="{{ route('movies.edit', $movie) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('movies.destroy', $movie) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this movie?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($movies->isEmpty())
                <div class="text-center text-muted">
                    <p>No movies added yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection