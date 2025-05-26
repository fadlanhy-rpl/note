<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TrashController extends Controller
{
     use AuthorizesRequests;
    public function index()
    {
        $notes = Auth::user()->notes()
            ->onlyTrashed() // Hanya yang di soft delete
            ->latest('deleted_at')
            ->get();
        $pageTitle = "Trash";
        return view('notes.index', compact('notes', 'pageTitle')); // Re-use view
    }

    public function restore(Request $request, $noteId) // Gunakan $noteId agar bisa restore tanpa instance
    {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($noteId);
        $note->restore(); // Restore dari soft delete

        $message = 'Note restored from trash.';
         if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->route('trash.index')->with('success', $message);
    }

    public function permanentDelete(Request $request, $noteId)
    {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($noteId);
        $note->labels()->detach(); // Hapus relasi label
        $note->forceDelete(); // Hapus permanen

        $message = 'Note permanently deleted.';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->route('trash.index')->with('success', $message);
    }

    public function emptyTrash(Request $request)
    {
        $trashedNotes = Auth::user()->notes()->onlyTrashed()->get();
        foreach ($trashedNotes as $note) {
            $note->labels()->detach();
            $note->forceDelete();
        }
        $message = 'Trash emptied successfully.';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->route('trash.index')->with('success', $message);
    }
}