<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::latest()->get();
        return view('notes.index', ['notes' => $notes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function show(Note $note)
    {
        return view('notes.show', compact('note'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $note = Note::create($validated);

        return redirect()->route('notes.index')
            ->with('success', "Note '{$note->title}' has been created successfully!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $note->update($validated);

        return redirect()->route('notes.index')
            ->with('success', "Note '{$note->title}' has been updated successfully!");
    }

    /**
     * jjijfdsoijgRemove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $title = $note->title;
        $note->delete();

        return redirect()->route('notes.index')
            ->with('success', "Note '{$title}' has been deleted successfully!");
    }
}
