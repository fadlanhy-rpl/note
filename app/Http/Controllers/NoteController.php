<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     * Halaman utama akan menampilkan catatan yang tidak diarsip & tidak di trash.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $notesQuery = $user->notes()
            ->where('is_archived', false)
            ->latest();

        $pageTitle = "My Notes";
        $notes = $notesQuery->get();

        return view('notes.index', compact('notes', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Note::class);
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Note::class);

        // Validasi input
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required_without:title|nullable|string',
            'color' => ['required', Rule::in(config('notes.colors'))],
            'labels' => 'nullable|array',
            'labels.*' => 'exists:labels,id,user_id,' . Auth::id(),
        ], [
            'content.required_without' => 'Either a title or content is required for the note.',
            'color.required' => 'The color field is required.',
            'color.in' => 'The selected color is invalid.',
        ]);

        // Double check untuk memastikan tidak ada note kosong
        if (empty(trim($validated['title'] ?? '')) && empty(trim($validated['content'] ?? ''))) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Either a title or content is required.',
                    'errors' => ['content' => ['Either a title or content is required for the note.']]
                ], 422);
            }
            return back()->withErrors(['content' => 'Either a title or content is required for the note.'])->withInput();
        }

        try {
            // Gunakan database transaction untuk mencegah duplikasi
            $note = DB::transaction(function () use ($validated, $request) {
                // Cek apakah ada note yang sama dalam 5 detik terakhir (mencegah double submission)
                $recentNote = Auth::user()->notes()
                    ->where('title', $validated['title'] ?? null)
                    ->where('content', $validated['content'] ?? null)
                    ->where('created_at', '>=', now()->subSeconds(5))
                    ->first();

                if ($recentNote) {
                    throw new \Exception('Duplicate note submission detected');
                }

                // Buat note baru
                $note = Auth::user()->notes()->create([
                    'title' => $validated['title'] ?? null,
                    'content' => $validated['content'] ?? null,
                    'color' => $validated['color'],
                    'is_archived' => $request->boolean('is_archived', false),
                ]);

                // Attach labels jika ada
                if (!empty($validated['labels'])) {
                    $note->labels()->sync($validated['labels']);
                }

                return $note;
            });

            // Response sukses
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Note created successfully!',
                    'note' => $note->fresh()->load('labels')
                ]);
            }

            return redirect()->route('home')->with('success', 'Note created successfully!');

        } catch (\Exception $e) {
            // Handle error (termasuk duplikasi)
            if ($e->getMessage() === 'Duplicate note submission detected') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Note already exists. Please check your recent notes.',
                        'errors' => ['general' => ['Duplicate submission detected']]
                    ], 409); // Conflict
                }
                return back()->withErrors(['general' => 'This note appears to be a duplicate. Please check your recent notes.'])->withInput();
            }

            // Handle other errors
            \Log::error('Note creation failed: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create note. Please try again.',
                    'errors' => ['general' => ['An error occurred while creating the note']]
                ], 500);
            }

            return back()->withErrors(['general' => 'Failed to create note. Please try again.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        $this->authorize('view', $note);
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $rules = [
            'title' => 'nullable|string|max:255',
            'content' => 'required_without:title|nullable|string',
            'color' => ['required', Rule::in(config('notes.colors'))],
            'labels' => 'nullable|array',
            'labels.*' => 'exists:labels,id,user_id,' . Auth::id(),
            'is_archived' => 'sometimes|boolean',
        ];

        $messages = [
            'content.required_without' => 'The content field is required when title is not present.',
            'color.required' => 'The color field is required.',
            'color.in' => 'The selected color is invalid.',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        try {
            DB::transaction(function () use ($note, $validatedData, $request) {
                $note->update($validatedData);

                if ($request->has('labels')) {
                    $note->labels()->sync($validatedData['labels'] ?? []);
                }
            });

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Note updated successfully!',
                    'note' => $note->fresh()->load('labels')
                ]);
            }

            return redirect()->route('notes.show', $note)->with('success', 'Note updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Note update failed: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update note. Please try again.'
                ], 500);
            }

            return back()->withErrors(['general' => 'Failed to update note. Please try again.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage (Move to Trash).
     */
    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        
        try {
            $note->delete(); // Soft delete

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Note moved to trash.']);
            }
            return redirect()->route('home')->with('success', 'Note moved to trash.');
            
        } catch (\Exception $e) {
            \Log::error('Note deletion failed: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete note.'], 500);
            }
            return back()->withErrors(['general' => 'Failed to delete note.']);
        }
    }

    /**
     * Toggle the archive status of a note.
     */
    public function archiveToggle(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        try {
            DB::transaction(function () use ($note) {
                $newArchivedState = !$note->is_archived;
                $note->is_archived = $newArchivedState;

                if ($newArchivedState && $note->trashed()) {
                    $note->restore();
                }
                $note->save();
            });

            $message = $note->is_archived ? 'Note archived successfully.' : 'Note unarchived successfully.';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'is_archived' => $note->is_archived,
                    'note_id' => $note->id
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Archive toggle failed: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update note status.'], 500);
            }
            return back()->withErrors(['general' => 'Failed to update note status.']);
        }
    }

    /**
     * Search notes by title or content.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        if (empty(trim($query))) {
            return redirect()->route('home');
        }

        $user = Auth::user();

        $notes = $user->notes()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->orWhereHas('labels', function ($labelQuery) use ($query) {
                        $labelQuery->where('name', 'like', "%{$query}%");
                    });
            })
            ->where('is_archived', false)
            ->latest()
            ->get();

        $pageTitle = "Search results for: \"{$query}\"";

        if ($request->ajax()) {
            return response()->json($notes->load('labels'));
        }

        return view('notes.index', compact('notes', 'query', 'pageTitle'));
    }
}