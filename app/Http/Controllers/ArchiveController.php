<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArchiveController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $notes = Auth::user()->notes()
            ->where('is_archived', true)
            // SoftDeletes otomatis
            ->latest()
            ->get();
        $pageTitle = "Archived Notes";
        return view('notes.index', compact('notes', 'pageTitle')); // Kita bisa reuse view notes.index
    }

    // Fungsi unarchive bisa ada di sini atau di NoteController (archiveToggle)
    // Jika di sini:
    public function unarchive(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id() || !$note->is_archived) {
            abort(403);
        }
        $note->is_archived = false;
        $note->save();

        $message = 'Note unarchived.';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'is_archived' => $note->is_archived]);
        }
        return redirect()->route('archive.index')->with('success', $message);
    }
}
