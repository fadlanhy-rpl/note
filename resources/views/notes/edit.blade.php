@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 md:p-8 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-google-sans font-medium text-gray-800">Edit Note</h1>
        <a href="{{ route('notes.show', $note) }}" class="text-gray-500 hover:text-gray-700" title="Close">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </a>
    </div>

    <form action="{{ route('notes.update', $note->id) }}" method="POST" id="editNoteFullForm">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1 sr-only">Title</label>
            <input type="text" id="title" name="title" class="w-full p-3 border-b border-gray-300 focus:border-blue-500 outline-none text-lg placeholder-gray-400" value="{{ old('title', $note->title) }}" placeholder="Title" autocomplete="off">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1 sr-only">Content</label>
            <textarea id="content" name="content" rows="10" class="w-full p-3 border-b border-gray-300 focus:border-blue-500 outline-none placeholder-gray-400 text-sm" placeholder="Take a note...">{{ old('content', $note->content) }}</textarea>
            @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
            <div class="flex flex-wrap gap-2">
                @foreach($noteColors as $colorName)
                <div class="color-option bg-note-{{ $colorName }} {{ (old('color', $note->color) == $colorName) ? 'selected' : '' }} {{ $colorName == 'white' ? 'border border-gray-300' : '' }}" data-color="{{ $colorName }}" title="{{ ucfirst($colorName) }}"></div>
                @endforeach
            </div>
            <input type="hidden" name="color" id="noteColorInput" value="{{ old('color', $note->color) }}">
            @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="labels" class="block text-sm font-medium text-gray-700 mb-2">Labels</label>
            @if($allUserLabels->isNotEmpty())
            <select id="labels" name="labels[]" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" multiple size="4">
                @php $currentLabels = old('labels', $note->labels->pluck('id')->toArray()); @endphp
                @foreach($allUserLabels as $label)
                    <option value="{{ $label->id }}" {{ in_array($label->id, $currentLabels) ? 'selected' : '' }}>{{ $label->name }}</option>
                @endforeach
            </select>
            @else
            <p class="text-sm text-gray-500">No labels created yet. <a href="{{ route('labels.manage') }}" class="text-blue-500 hover:underline">Manage labels</a></p>
            @endif
            @error('labels') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            @error('labels.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-between items-center">
            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to move this note to trash?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm text-red-600 border border-red-600 rounded-md hover:bg-red-600 hover:text-white transition-colors">Move to Trash</button>
            </form>
            <div class="flex space-x-3">
                <a href="{{ route('notes.show', $note) }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">Update Note</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorOptions = document.querySelectorAll('#editNoteFullForm .color-option');
        const noteColorInput = document.getElementById('noteColorInput');

        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                const color = this.getAttribute('data-color');
                if (noteColorInput) noteColorInput.value = color;
                colorOptions.forEach(opt => opt.classList.remove('selected', 'ring-2', 'ring-blue-500'));
                this.classList.add('selected', 'ring-2', 'ring-blue-500');
            });
        });
        // Auto-select current color
        const currentSelectedColor = document.querySelector(`#editNoteFullForm .color-option[data-color="${noteColorInput.value}"]`);
        if(currentSelectedColor) currentSelectedColor.classList.add('selected', 'ring-2', 'ring-blue-500');
    });
</script>
@endpush

@push('styles')
<style>
    .color-option { width: 30px; height: 30px; }
    .color-option.selected { box-shadow: 0 0 0 2px white, 0 0 0 4px #3b82f6; }
</style>
@endpush