@props(['note'])

<div class="card mb-3">
    <div class="card-body">
        @if(!empty($note->title))
            <h5 class="card-title">{{ $note->title }}</h5>
        @endif

        @if(!empty($note->content))
            <p class="card-text">{{ $note->content }}</p>
        @endif

        @if(!empty($note->created_at))
            <p class="card-text"><small class="text-muted">{{ $note->created_at->diffForHumans() }}</small></p>
        @endif

        <div class="text-center">
            <a href="{{ route('notes.show', $note) }}" class="btn btn-sm btn-info text-white">
                View Details
            </a>
        </div>
    </div>
</div>


