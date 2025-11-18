<x-layout>
    <x-slot:title>All Movies</x-slot:title>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">My Movies</h1>
        <a href="{{ route('notes.create') }}" class="btn btn-primary">
            Add Movie
        </a>
    </div>

    <div class="row g-4"> <!-- g-4 adds spacing between grid items -->
        @forelse($notes as $note)
            <div class="col-12 col-md-6 col-lg-4 mb-4"> <!-- mb-4 adds bottom margin -->
                <x-note-card :note="$note" />
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No movies yet. Create your first note.</div>
            </div>
        @endforelse
    </div>
</x-layout>
