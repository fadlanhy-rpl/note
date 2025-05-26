@extends('layouts.app')

@section('title', 'Create New Note')

@section('content')

<div class="max-w-2xl mx-auto bg-white p-6 md:p-8 rounded-lg shadow-xl">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-google-sans font-medium text-gray-800">Create New Note</h1>
        <a href="{{ url()->previous(route('home')) }}" class="text-gray-500 hover:text-gray-700 transition-colors" title="Close and go back">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </a>
    </div>

    {{-- Tampilkan error umum jika ada --}}
    @error('general')
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ $message }}
        </div>
    @enderror

    <form action="{{ route('notes.store') }}" method="POST" id="createNoteFullForm">
        @csrf
        <div class="mb-5">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1 sr-only">Title</label>
            <input type="text" id="title" name="title"
                   class="w-full p-3 border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 outline-none text-lg placeholder-gray-400 transition-colors"
                   value="{{ old('title') }}" placeholder="Title (Optional)" autocomplete="off">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-5">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1 sr-only">Content</label>
            <textarea id="content" name="content" rows="8"
                      class="w-full p-3 border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 outline-none placeholder-gray-400 text-sm transition-colors"
                      placeholder="Take a note...">{{ old('content') }}</textarea>
            @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
            <div class="flex flex-wrap gap-2 items-center">
                @foreach($noteColors as $colorName)
                <div class="color-option bg-note-{{ $colorName }} cursor-pointer rounded-full
                            {{ $colorName == 'white' ? 'border border-gray-400' : '' }}"
                     data-color="{{ $colorName }}" title="{{ ucfirst($colorName) }}">
                </div>
                @endforeach
            </div>
            <input type="hidden" name="color" id="noteColorInput" value="{{ old('color', 'white') }}">
            @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
                <label for="modalNoteLabelsSelectTom"
                    class="block text-xs font-medium text-gray-500 mb-1">Labels</label>
                <select id="modalNoteLabelsSelectTom" name="labels[]" multiple placeholder="Add or select labels..."
                    autocomplete="off" class="tom-select-custom w-full"> {{-- Ganti kelas jika perlu untuk styling --}}
                    @if (isset($allUserLabels) && $allUserLabels->isNotEmpty())
                        @foreach ($allUserLabels as $label)
                            {{-- Nilai value harus ID label, teks adalah nama label --}}
                            <option value="{{ $label->id }}">{{ $label->name }}</option>
                        @endforeach
                    @endif
                </select>
                {{-- TomSelect akan menggantikan ini dengan UI-nya --}}
            </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ url()->previous(route('home')) }}"
               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-colors">
               Cancel
            </a>
            <button type="submit" id="submitBtn"
                    class="px-6 py-2.5 text-sm font-medium bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitBtnText">Save Note</span>
                <span id="submitBtnLoading" class="hidden">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createNoteFullForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        const submitBtnLoading = document.getElementById('submitBtnLoading');
        
        if (!form) return;

        let isSubmitting = false; // Flag untuk mencegah double submission

        const colorOptions = form.querySelectorAll('.color-option');
        const noteColorInput = form.querySelector('#noteColorInput');

        // Fungsi untuk menandai warna yang dipilih
        function selectColor(selectedOption) {
            if (!selectedOption) return;
            const color = selectedOption.getAttribute('data-color');
            if (noteColorInput) noteColorInput.value = color;

            colorOptions.forEach(opt => opt.classList.remove('selected', 'ring-2', 'ring-offset-2', 'ring-blue-500'));
            selectedOption.classList.add('selected', 'ring-2', 'ring-offset-2', 'ring-blue-500');
        }

        // Event listener untuk setiap opsi warna
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                selectColor(this);
            });
        });

        // Inisialisasi warna yang dipilih saat halaman dimuat
        const initialColorValue = noteColorInput ? noteColorInput.value : 'white';
        const initiallySelectedOption = form.querySelector(`.color-option[data-color="${initialColorValue}"]`);
        if (initiallySelectedOption) {
            selectColor(initiallySelectedOption);
        } else {
            const defaultWhiteOption = form.querySelector('.color-option[data-color="white"]');
            if (defaultWhiteOption) selectColor(defaultWhiteOption);
        }

        // Prevent double submission
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            // Validasi client-side
            const title = form.querySelector('#title').value.trim();
            const content = form.querySelector('#content').value.trim();
            
            if (!title && !content) {
                alert('Please enter either a title or content for the note.');
                e.preventDefault();
                return false;
            }

            // Set flag dan disable button
            isSubmitting = true;
            submitBtn.disabled = true;
            submitBtnText.classList.add('hidden');
            submitBtnLoading.classList.remove('hidden');

            // Reset flag setelah timeout (fallback jika ada masalah)
            setTimeout(() => {
                if (isSubmitting) {
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtnText.classList.remove('hidden');
                    submitBtnLoading.classList.add('hidden');
                }
            }, 10000); // 10 detik timeout
        });

        // Reset form state jika user navigasi back
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                // Reset state jika halaman di-load dari cache
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtnText.classList.remove('hidden');
                submitBtnLoading.classList.add('hidden');
            }
        });

        // Prevent multiple rapid clicks on submit button
        submitBtn.addEventListener('click', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .color-option {
        width: 28px;
        height: 28px;
        transition: transform 0.15s ease-out;
    }
    .color-option:hover {
        transform: scale(1.15);
    }
    
    #content::placeholder {
        font-size: 0.95rem;
    }

    /* Loading state styles */
    .disabled {
        opacity: 0.6;
        pointer-events: none;
    }
</style>
@endpush