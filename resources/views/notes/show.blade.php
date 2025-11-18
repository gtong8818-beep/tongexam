<x-layout>
    <x-slot:title>{{ $note->title }}</x-slot:title>

    <div class="container">
        <div class="mb-4">
            <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Movies
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <h1 class="card-title h2 mb-4">{{ $note->title }}</h1>
                <p class="card-text fs-5 mb-4">{{ $note->content }}</p>
                <p class="text-muted">
                    Created {{ $note->created_at->diffForHumans() }}
                    @if($note->created_at != $note->updated_at)
                        · Updated {{ $note->updated_at->diffForHumans() }}
                    @endif
                </p>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('notes.edit', $note) }}" class="btn btn-primary">
                        Edit Movie
                    </a>
                    <form action="{{ route('notes.destroy', $note) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this movie?')">
                            Delete Movie
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Edit Modal -->
    <div class="modal fade" id="editNoteModal{{ $note->id }}" tabindex="-1" aria-labelledby="editNoteModalLabel{{ $note->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('notes.update', $note) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editNoteModalLabel{{ $note->id }}">Edit Movie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $note->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required>{{ $note->content }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
