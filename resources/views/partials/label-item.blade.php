{{-- resources/views/partials/label-item.blade.php --}}
<li class="flex justify-between items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded group label-item" data-label-id="{{ $label->id }}">
    <div class="flex items-center">
        <span class="label-color-dot w-3 h-3 rounded-full mr-3" style="background-color: {{ $label->color ?? '#A0AEC0' }};"></span>
        <span class="label-name-display text-sm text-gray-700 dark:text-gray-300">{{ $label->name }}</span>
        <input type="text" value="{{ $label->name }}" class="form-input edit-label-input hidden text-sm p-1 border-blue-500 dark:bg-gray-600 dark:border-blue-400 dark:text-gray-200" style="max-width: 150px;">
    </div>
    <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
        <button type="button" class="edit-label-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
            </svg>
        </button>
        <button type="button" class="save-label-btn p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hidden" title="Save">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </button>
        <button type="button" class="cancel-edit-label-btn p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hidden" title="Cancel">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <form class="delete-label-form inline-block" action="{{ route('labels.destroy', $label->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Delete" onclick="return confirm('Delete label {{ $label->name }}?')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
    </div>
</li>