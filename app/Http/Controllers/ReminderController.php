<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Note; // Untuk mengambil note saat membuat reminder
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Untuk otorisasi jika diperlukan

class ReminderController extends Controller
{
    use AuthorizesRequests; // Jika Anda ingin menggunakan policy untuk reminder

    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'upcoming'); // upcoming, past, completed
        $pageTitle = "Reminders";

        $remindersQuery = $user->reminders()->with('note'); // Eager load note

        switch ($filter) {
            case 'past':
                $remindersQuery->past()->where('is_completed', false); // Past tapi belum selesai
                $pageTitle = "Past Reminders";
                break;
            case 'completed':
                $remindersQuery->completed();
                $pageTitle = "Completed Reminders";
                break;
            case 'upcoming':
            default:
                $remindersQuery->upcoming();
                $pageTitle = "Upcoming Reminders";
                break;
        }

        $reminders = $remindersQuery->paginate(15);

        return view('reminders.index', compact('reminders', 'pageTitle', 'filter'));
    }

    /**
     * Show the form for creating a new reminder for a specific note.
     * Biasanya ini akan berupa modal atau bagian dari form edit note.
     * Untuk contoh ini, kita buat halaman terpisah.
     */
    public function create(Request $request)
    {
        $noteId = $request->query('note_id');
        $note = null;
        if ($noteId) {
            $note = Auth::user()->notes()->findOrFail($noteId);
        }
        $pageTitle = $note ? "Set Reminder for: " . $note->title : "Set New Reminder";
        $notes = Auth::user()->notes()->where('is_archived', false)->orderBy('title')->get(); // Untuk dropdown pilih note jika tidak dari note tertentu

        return view('reminders.create', compact('pageTitle', 'note', 'notes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'note_id' => 'required|exists:notes,id,user_id,' . Auth::id(),
            'remind_at' => 'required|date|after_or_equal:now',
            'description' => 'nullable|string|max:255',
        ]);

        $reminder = Auth::user()->reminders()->create($validated);

        // Jika dari AJAX (misal modal di note)
        if ($request->ajax() || $request->wantsJson()) {
             return response()->json(['success' => true, 'message' => 'Reminder set successfully!', 'reminder' => $reminder->load('note')]);
        }

        return redirect()->route('reminders.index')->with('success', 'Reminder set successfully!');
    }

    /**
     * Mark a reminder as completed.
     */
    public function complete(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$reminder->is_completed) {
            $reminder->is_completed = true;
            $reminder->completed_at = now();
            $reminder->save();
            $message = 'Reminder marked as completed.';
        } else {
            $message = 'Reminder was already completed.';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'reminder' => $reminder]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Unmark a reminder as completed.
     */
    public function uncomplete(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reminder->is_completed) {
            $reminder->is_completed = false;
            $reminder->completed_at = null;
            $reminder->save();
            $message = 'Reminder marked as not completed.';
        } else {
            $message = 'Reminder was not completed.';
        }
         if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'reminder' => $reminder]);
        }
        return redirect()->back()->with('success', $message);
    }


    public function edit(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }
        $pageTitle = "Edit Reminder for: " . $reminder->note->title;
        // $notes tidak diperlukan jika kita tidak membiarkan user mengganti note pada reminder yang sudah ada
        return view('reminders.edit', compact('reminder', 'pageTitle'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            // 'note_id' => 'sometimes|required|exists:notes,id,user_id,' . Auth::id(), // Jika boleh ganti note
            'remind_at' => 'required|date|after_or_equal:now',
            'description' => 'nullable|string|max:255',
        ]);

        $reminder->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Reminder updated successfully!', 'reminder' => $reminder->load('note')]);
        }

        return redirect()->route('reminders.index')->with('success', 'Reminder updated successfully!');
    }

    public function destroy(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }
        $reminder->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Reminder deleted successfully.']);
        }

        return redirect()->route('reminders.index')->with('success', 'Reminder deleted successfully.');
    }
}