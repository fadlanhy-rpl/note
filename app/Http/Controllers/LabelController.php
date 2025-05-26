<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LabelController extends Controller
{
    use AuthorizesRequests;

    protected $labelColors = ['red', 'orange', 'yellow', 'green', 'teal', 'blue', 'purple', 'pink', 'brown', 'gray', 'black']; // Tambahkan warna jika perlu

    public function index()
    {
        // Halaman utama untuk melihat semua label mungkin tidak diperlukan jika sudah ada di sidebar
        // Tapi untuk 'Edit Labels' ini bisa digunakan
        $labels = Auth::user()->labels()->orderBy('name')->get();
        $pageTitle = "Manage Labels";
        return view('labels.index-page', compact('labels', 'pageTitle')); // Buat view labels.index
    }

    public function create()
    {
        // Biasanya create label dilakukan inline atau dari modal 'Edit Labels'
        // return view('labels.create'); // Jika perlu halaman create terpisah
        abort(404); // Untuk saat ini
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('labels')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'color' => ['nullable', 'string', Rule::in($this->labelColors)],
        ]);

        $label = Auth::user()->labels()->create([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? $this->labelColors[array_rand($this->labelColors)], // Warna random jika tidak dipilih
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Label created!', 'label' => $label]);
        }
        return redirect()->route('labels.index')->with('success', 'Label created.');
    }

    public function show(Label $label)
    {
        // Pastikan label milik user
        if ($label->user_id !== Auth::id()) {
            abort(403);
        }
        // Tampilkan note yang memiliki label ini
        $notes = $label->notes()
            ->where('user_id', Auth::id())
            ->where('is_archived', false)
            // SoftDeletes otomatis
            ->latest()->get();
        $pageTitle = "Notes with label: " . $label->name;
        return view('notes.index', compact('notes', 'label', 'pageTitle'));
    }

    public function edit(Label $label)
    {
        // Halaman 'Edit Labels' akan memanggil index.
        // Untuk edit satu label, bisa via AJAX.
        // Jika Anda mau halaman edit label terpisah:
        // if ($label->user_id !== Auth::id()) abort(403);
        // return view('labels.edit', compact('label'));
        abort(404); // Untuk saat ini
    }

    public function update(Request $request, Label $label)
    {
        if ($label->user_id !== Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('labels')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($label->id),
            ],
            'color' => ['nullable', 'string', Rule::in($this->labelColors)],
        ]);

        $label->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Label updated!', 'label' => $label]);
        }
        return redirect()->route('labels.index')->with('success', 'Label updated.');
    }

    public function destroy(Label $label)
    {
        if ($label->user_id !== Auth::id()) {
            abort(403);
        }
        $label->notes()->detach(); // Hapus relasi sebelum menghapus label
        $label->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Label deleted.']);
        }
        return redirect()->route('labels.index')->with('success', 'Label deleted.');
    }

    public function getSidebarLabelsPartial(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['html' => '<p class="px-4 text-sm text-gray-500 dark:text-gray-400 italic">Please log in.</p>'], 401);
        }

        // Gunakan logika caching yang sama seperti di AppServiceProvider untuk konsistensi
        $labelsForSidebar = Cache::remember('user_sidebar_labels_' . $user->id, now()->addMinutes(10), function () use ($user) {
            return $user->labels()->orderBy('name')->take(10)->get();
        });

        // Render partial view dan kembalikan sebagai HTML
        // Kita perlu membuat view partial ini.
        $html = view('partials.sidebar-labels-list-items', compact('labelsForSidebar'))->render();

        return response()->json(['html' => $html]);
    }

}
