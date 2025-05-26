@extends('layouts.app')

{{-- Judul halaman dinamis berdasarkan konteks --}}
@section('title', $pageTitle ?? 'My Notes')

@section('content')

    {{-- Hanya tampilkan Quick Create dan Welcome Section di halaman utama (home) tanpa filter aktif --}}
     @if (Route::currentRouteName() == 'home' && !request()->has('query') && !isset($label))
        <div class="mb-10 ">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-3xl font-google-sans font-medium text-gray-800 dark:text-gray-100">Welcome, {{ $currentUser->name }}!</h1>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-base mb-6">Ready to capture your thoughts and ideas? Let's get started.</p>
        </div>

        <!-- Create Note Input (Quick Create) - Mirip Google Keep -->
        <div class="max-w-xl mx-auto mb-12">
            <div id="quickCreateNoteContainer"
                class="bg-white dark:bg-dark-surface rounded-lg shadow-lg border border-gray-200 dark:border-dark-border-soft transition-all duration-300 ease-out">
                <form action="{{ route('notes.store') }}" method="POST" id="quickCreateForm" class="p-4">
                    @csrf
                    <input type="text" name="title" id="quickCreateNoteTitleInput" placeholder="Take a note..."
                        class="w-full outline-none text-gray-800 dark:text-dark-text-primary text-base font-medium placeholder-gray-500 dark:placeholder-gray-400 bg-transparent pb-2"
                        autocomplete="off">

                    <div id="quickCreateContentArea" class="hidden mt-1">
                        <textarea name="content" id="quickCreateNoteContentTextarea" placeholder="Your note..."
                            class="w-full outline-none text-gray-700 dark:text-dark-text-secondary resize-none text-sm placeholder-gray-500 dark:placeholder-gray-400 bg-transparent min-h-[60px]"
                            rows="3"></textarea>
                    </div>

                    <div id="quickCreateActions"
                        class="flex justify-between items-center mt-3 pt-3 border-t border-gray-200/70 dark:border-dark-border-soft/70 hidden">
                        <div class="flex space-x-1 items-center">
                            <button type="button"
                                class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 focus:outline-none"
                                title="Add reminder (Edit in full editor)">
                                <i class="ri-time-line text-lg"></i>
                            </button>
                            <div class="relative">
                                <button type="button" id="quickCreateColorToggle"
                                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 focus:outline-none"
                                    title="Change background color">
                                    <i class="ri-palette-line text-lg"></i>
                                </button>
                                <div id="quickCreateColorPalette"
                                    class="absolute bottom-full left-0 mb-2 bg-white dark:bg-gray-700 shadow-xl rounded-lg p-2 hidden z-30 border border-gray-200 dark:border-dark-border-hard w-max">
                                    <div class="grid grid-cols-6 gap-1">
                                        {{-- Variabel $noteColors harus tersedia dari AppServiceProvider --}}
                                        @if (isset($noteColors) && (is_array($noteColors) || is_object($noteColors)))
                                            @foreach ($noteColors as $colorName)
                                                <div class="quick-color-option color-option bg-note-{{ $colorName }} cursor-pointer rounded-full h-6 w-6 {{ $colorName == 'white' ? 'border border-gray-300 dark:border-gray-600' : '' }}"
                                                    data-color="{{ $colorName }}" title="{{ ucfirst($colorName) }}"></div>
                                            @endforeach
                                        @else
                                             <p class="text-xs text-red-500 col-span-6">Colors unavailable.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="color" id="quickCreateNoteColorInput" value="white">
                        </div>
                        <div>
                            <button type="button" id="closeQuickCreateBtn"
                                class="px-4 py-2 text-sm text-gray-700 dark:text-dark-text-secondary font-medium bg-gray-100 dark:hover:bg-gray-700 rounded-md focus:outline-none">Close</button>
                            <button type="submit" id="saveQuickCreateBtn"
                                class="px-5 py-2 text-sm bg-brand-blue text-white font-medium bg-blue-700 dark:hover:bg-blue-500 rounded-md focus:outline-none focus:ring-2 focus:ring-brand-blue focus:ring-offset-1">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif


    <!-- Notes Section -->
    <div class="mt-8">
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <h2 class="text-xl font-google-sans font-medium text-gray-700">
                {{ $pageTitle ?? 'My Notes' }}
                @if (isset($label) && $label)
                    <span class="text-base font-normal text-gray-500">(Label: {{ $label->name }})</span>
                @endif
                @if (request('query'))
                    <span class="text-base font-normal text-gray-500">(Search results for: "{{ request('query') }}")</span>
                @endif
            </h2>
            @if (request()->routeIs('trash.index') && $notes->isNotEmpty())
                <form action="{{ route('trash.empty') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to empty the trash? This action cannot be undone and will permanently delete all notes in the trash.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-xs font-medium bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                        Empty Trash
                    </button>
                </form>
            @endif
        </div>

        @if ($notes->isEmpty())
            <div class="text-center py-12 px-6 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.846 5.671a1 1 0 00.95.69h5.969c.969 0 1.371 1.24.588 1.81l-4.828 3.522a1 1 0 00-.364 1.118l1.846 5.671c.3.921-.755 1.688-1.54 1.118l-4.828-3.522a1 1 0 00-1.176 0l-4.828 3.522c-.784.57-1.838-.197-1.539-1.118l1.846-5.671a1 1 0 00-.364-1.118L2.28 11.1c-.783-.57-.38-1.81.588-1.81h5.969a1 1 0 00.95-.69L11.049 2.927z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-700">
                    @if (request()->routeIs('home') && !request()->has('query') && !isset($label))
                        No notes yet
                    @elseif(request()->routeIs('archive.index'))
                        Your archive is empty
                    @elseif(request()->routeIs('trash.index'))
                        Your trash is empty
                    @elseif(request('query'))
                        No notes found for your search
                    @elseif(isset($label))
                        No notes found with this label
                    @else
                        No notes to display
                    @endif
                </h3>
                <p class="mt-2 text-sm text-gray-500">
                    @if (request()->routeIs('home') && !request()->has('query') && !isset($label))
                        Why not create your first note above?
                    @else
                        Try a different search or filter.
                    @endif
                </p>
                @if (request()->routeIs('home') && !request()->has('query') && !isset($label))
                    <div class="mt-6">
                        <a href="{{ route('notes.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Create a Detailed Note
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="masonry-grid group" id="notesGrid">
                @foreach ($notes as $note)
                    <div class="note-card group/card relative flex flex-col bg-note-{{ $note->color ?? 'white' }} p-4 cursor-pointer break-words hover:shadow-xl focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-1"
                        tabindex="0" {{-- Make it focusable for keyboard nav to open modal --}} data-id="{{ $note->id }}"
                        data-title="{{ $note->title ?? '' }}" data-content="{{ $note->content ?? '' }}"
                        data-color="{{ $note->color ?? 'white' }}"
                        data-archived="{{ $note->is_archived ? 'true' : 'false' }}"
                        data-labels="{{ json_encode($note->labels->pluck('id')) }}">

                        <div class="flex-grow">
                            @if ($note->title)
                                <h3 class="font-google-sans font-medium text-base mb-2 text-black">{{ $note->title }}
                                </h3>
                            @endif
                            @if ($note->content)
                                <p class="text-sm text-black opacity-80 mb-3 whitespace-pre-line">
                                    {{ Str::limit($note->content, strlen($note->title ?? '') > 50 ? 100 : 200) }}</p>
                            @endif

                            @if ($note->labels->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mt-2 mb-2">
                                    @foreach ($note->labels as $labelObj)
                                        <span class="label-chip text-xs">{{ $labelObj->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div
                            class="flex justify-between items-center text-xs text-gray-500 mt-3 pt-2 border-t border-black/10">
                            <span>{{ $note->updated_at->diffForHumans(null, true, true) }}</span>
                            {{-- Aksi muncul saat hover pada kartu catatan --}}
                            <div
                                class="flex space-x-1 note-card-actions opacity-0 group-hover/card:opacity-100 group-focus-within/card:opacity-100 transition-opacity duration-150">
                                @if (request()->routeIs('trash.index'))
                                    <form action="{{ route('trash.restore', $note->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        <button type="submit"
                                            class="p-1.5 rounded-full hover:bg-black/10 text-gray-600 focus:outline-none focus:bg-black/10"
                                            title="Restore note">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('trash.permanent-delete', $note->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Delete this note permanently? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-full hover:bg-black/10 text-red-500 focus:outline-none focus:bg-black/10"
                                            title="Delete Permanently">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('notes.archive-toggle', $note->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        <button type="submit"
                                            class="p-1.5 rounded-full hover:bg-black/10 text-gray-600 focus:outline-none focus:bg-black/10"
                                            title="{{ $note->is_archived ? 'Unarchive note' : 'Archive note' }}">
                                            @if ($note->is_archived)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4m-4-4l4 4m0 0l4-4m-4 4V4" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <button type="button"
                                        class="p-1.5 rounded-full hover:bg-black/10 text-red-500 focus:outline-none focus:bg-black/10 open-in-modal-btn"
                                        title="Edit note">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </button>
                                    {{-- Tombol delete (move to trash) dipisahkan agar tidak terpicu tidak sengaja saat klik card --}}
                                    {{-- Formnya bisa dibuat di JS saat tombol diklik, atau disembunyikan --}}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // --- Quick Create Note Logic ---
            const quickCreateNoteContainer = document.getElementById('quickCreateNoteContainer');
            const quickCreateNoteTitleInput = document.getElementById('quickCreateNoteTitleInput');
            const quickCreateContentArea = document.getElementById('quickCreateContentArea');
            const quickCreateNoteContentTextarea = document.getElementById('quickCreateNoteContentTextarea');
            const quickCreateActions = document.getElementById('quickCreateActions');
            const closeQuickCreateBtn = document.getElementById('closeQuickCreateBtn');
            const quickCreateForm = document.getElementById('quickCreateForm');
            const quickCreateNoteColorInput = document.getElementById('quickCreateNoteColorInput');
            const quickCreateColorToggle = document.getElementById('quickCreateColorToggle');
            const quickCreateColorPalette = document.getElementById('quickCreateColorPalette');
            let isQuickCreateExpanded = false;

             function expandQuickCreate() {
        if (isQuickCreateExpanded) return; // Jangan expand jika sudah
        isQuickCreateExpanded = true;

        console.log('Expanding quick create area');
        if (quickCreateNoteContainer) quickCreateNoteContainer.classList.add('shadow-xl');
        if (quickCreateContentArea) quickCreateContentArea.classList.remove('hidden');
        if (quickCreateActions) quickCreateActions.classList.remove('hidden');

        if (quickCreateNoteContentTextarea) {
            quickCreateNoteContentTextarea.style.height = 'auto';
            quickCreateNoteContentTextarea.style.height = (quickCreateNoteContentTextarea.scrollHeight) + 'px';
            // Fokus ke textarea jika judul sudah ada isinya, atau jika area konten memang target fokus awal.
            if (document.activeElement !== quickCreateNoteTitleInput || quickCreateNoteTitleInput.value.trim() !== '') {
                // Jika kita tidak ingin langsung fokus ke textarea saat judul baru difokuskan,
                // kita bisa mengomentari baris di bawah ini atau menambah kondisi.
                // quickCreateNoteContentTextarea.focus();
            }
        }
    }

            function collapseQuickCreate() {
                if (quickCreateNoteContainer) quickCreateNoteContainer.classList.remove('shadow-xl');
                if (quickCreateContentArea) quickCreateContentArea.classList.add('hidden');
                if (quickCreateActions) quickCreateActions.classList.add('hidden');
                if (quickCreateForm) quickCreateForm.reset();
                if (quickCreateNoteColorInput) quickCreateNoteColorInput.value = 'white'; // Reset warna
                if (quickCreateNoteContainer) quickCreateNoteContainer.style.backgroundColor = ''; // Reset bg color
                if (quickCreateColorPalette) quickCreateColorPalette.classList.add('hidden');
            }

            if (quickCreateNoteTitleInput) {
                quickCreateNoteTitleInput.addEventListener('focus', function() {
                    console.log('Title input focused'); // Debug
                    expandQuickCreate();
                });

                quickCreateNoteTitleInput.addEventListener('input', function() {
                    console.log('Title input value:', this.value); // Debug
                    if (this.value.trim() !== '' && quickCreateContentArea.classList.contains('hidden')) {
                        // Hanya expand jika belum expand dan ada input, untuk menghindari re-trigger berlebih
                        expandQuickCreate();
                    }
                });
            }

            if (quickCreateNoteContentTextarea) {
                quickCreateNoteContentTextarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }
            if (closeQuickCreateBtn) {
                closeQuickCreateBtn.addEventListener('click', collapseQuickCreate);
            }
            // Klik di luar area quick create untuk collapse (jika tidak ada input)
            document.addEventListener('click', function(event) {
                if (quickCreateNoteContainer && !quickCreateNoteContainer.contains(event.target)) {
                    if (!quickCreateNoteTitleInput.value.trim() && !quickCreateNoteContentTextarea.value
                        .trim()) {
                        collapseQuickCreate();
                    }
                }
                // Sembunyikan palet warna jika klik di luar
                if (quickCreateColorPalette && !quickCreateColorPalette.contains(event.target) && event
                    .target !== quickCreateColorToggle && !quickCreateColorToggle.contains(event.target)) {
                    quickCreateColorPalette.classList.add('hidden');
                }
            });


            if (quickCreateColorToggle) {
                quickCreateColorToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    quickCreateColorPalette.classList.toggle('hidden');
                });
            }
            if (quickCreateColorPalette) {
                quickCreateColorPalette.querySelectorAll('.quick-color-option').forEach(option => {
                    option.addEventListener('click', function() {
                        const color = this.dataset.color;
                        if (quickCreateNoteColorInput) quickCreateNoteColorInput.value = color;
                        if (quickCreateNoteContainer) { // Ganti warna background live preview
                            quickCreateNoteContainer.className = quickCreateNoteContainer.className
                                .replace(/bg-note-\w+/g, '') + ' bg-note-' + color;
                        }
                        quickCreateColorPalette.classList.add('hidden');
                    });
                });
            }


            if (quickCreateForm) {
                quickCreateForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(quickCreateForm);
                    const title = formData.get('title');
                    const content = formData.get('content');
                    const saveButton = document.getElementById('saveQuickCreateBtn');

                    if (!title && !content) {
                        alert('Please enter a title or content.');
                        return;
                    }
                    saveButton.disabled = true;
                    saveButton.innerHTML =
                        `<span class="loading-spinner-small inline-block border-2 border-gray-500 border-t-white rounded-full w-4 h-4 animate-spin mr-1"></span> Saving...`;

                    fetch("{{ route('notes.store') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload(); // Reload untuk melihat note baru
                            } else {
                                let errorMsg = data.message || 'Could not create note.';
                                if (data.errors) {
                                    errorMsg = Object.values(data.errors).flat().join('\n');
                                }
                                alert('Error: ' + errorMsg);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred.');
                        })
                        .finally(() => {
                            saveButton.disabled = false;
                            saveButton.textContent = 'Save';
                        });
                });
            }

            // --- Note Card Click to open Modal & Actions ---
            const noteModal = document.getElementById('noteModal'); // Asumsi ada di layouts/app.blade.php
            document.querySelectorAll('.note-card').forEach(card => {
                // Klik pada kartu untuk membuka modal
                card.addEventListener('click', function(event) {
                    if (event.target.closest('form, button, .note-card-actions'))
                        return; // Jangan buka modal jika klik tombol aksi di kartu
                    if (typeof openNoteModalWithData ===
                        'function') { // Cek fungsi dari note-modal.js
                        openNoteModalWithData({
                            id: this.dataset.id,
                            title: this.dataset.title,
                            content: this.dataset.content,
                            color: this.dataset.color,
                            is_archived: this.dataset.archived === 'true',
                            labels: JSON.parse(this.dataset.labels || '[]')
                        });
                    }
                });
                card.addEventListener('keydown', function(event) { // Buka modal dengan Enter/Space
                    if ((event.key === 'Enter' || event.key === ' ') && !event.target.closest(
                            'form, button, .note-card-actions')) {
                        event.preventDefault();
                        if (typeof openNoteModalWithData === 'function') {
                            openNoteModalWithData({
                                /* ... data ... */
                            });
                        }
                    }
                });

                // Tombol "Edit in modal" di kartu
                card.querySelectorAll('.open-in-modal-btn').forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation(); // Hindari trigger klik pada card
                        const parentCard = this.closest('.note-card');
                        if (typeof openNoteModalWithData === 'function') {
                            openNoteModalWithData({
                                id: parentCard.dataset.id,
                                title: parentCard.dataset.title,
                                content: parentCard.dataset.content,
                                color: parentCard.dataset.color,
                                is_archived: parentCard.dataset.archived === 'true',
                                labels: JSON.parse(parentCard.dataset.labels ||
                                    '[]')
                            });
                        }
                    });
                });
            });


            // --- Search (jika diperlukan AJAX di masa depan) ---
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const currentForm = this.closest('form');
                    if (this.value.length > 1 || this.value.length === 0) {
                        searchTimeout = setTimeout(() => {
                            currentForm.submit(); // Default: server-side search
                        }, 600);
                    }
                });
            }
        });
    </script>
@endpush
