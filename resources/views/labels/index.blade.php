@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-google-sans font-medium mb-6">{{ $pageTitle ?? 'Manage Labels' }}</h1>

    <!-- Form Tambah Label -->
    <form action="{{ route('labels.store') }}" method="POST" class="mb-6 bg-white p-4 rounded-lg shadow-sm">
        @csrf
        <h2 class="text-lg font-medium mb-2">Create New Label</h2>
        <div class="flex items-center gap-2">
            <input type="text" name="name" placeholder="Label name" class="flex-grow p-2 border rounded-md" required>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create</button>
        </div>
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </form>

    <div class="bg-white p-4 rounded-lg shadow-sm">
        <h2 class="text-lg font-medium mb-4">Existing Labels</h2>
        @if($labels->isEmpty())
            <p class="text-gray-500">No labels yet.</p>
        @else
            <ul class="space-y-2">
                @foreach($labels as $label)
                <li class="flex justify-between items-center p-2 border-b group label-item" data-label-id="{{ $label->id }}">
                    <span class="flex items-center">
                        <span class="label-color-dot w-3 h-3 rounded-full mr-2" style="background-color: {{ $label->color ?? '#ccc' }};"></span>
                        <span class="label-name-display">{{ $label->name }}</span>
                        <input type="text" value="{{ $label->name }}" class="edit-label-input hidden p-1 border border-blue-500 rounded text-sm ml-2" style="max-width: 150px;">
                    </span>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <!-- Tombol Edit -->
                        <button type="button" class="edit-label-btn text-blue-500 hover:text-blue-700 text-sm px-2 py-1">Edit</button>
                        <button type="button" class="save-label-btn text-green-500 hover:text-green-700 text-sm px-2 py-1 hidden">Save</button>
                        <button type="button" class="cancel-edit-label-btn text-gray-500 hover:text-gray-700 text-sm px-2 py-1 hidden">Cancel</button>
                        
                        <!-- Form Delete -->
                        <form action="{{ route('labels.destroy', $label->id) }}" method="POST" class="delete-label-form inline-block" onsubmit="return confirm('Delete label {{ $label->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm px-2 py-1">Delete</button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const labelsList = document.querySelector('.space-y-2');
    
    if (labelsList) {
        // Event delegation for edit, save, cancel buttons
        labelsList.addEventListener('click', function(e) {
            const target = e.target;
            const labelItem = target.closest('.label-item');
            const labelId = labelItem?.dataset.labelId;

            if (target.classList.contains('edit-label-btn')) {
                e.preventDefault();
                startEditLabel(labelItem);
            } else if (target.classList.contains('save-label-btn')) {
                e.preventDefault();
                saveEditLabel(labelItem, labelId);
            } else if (target.classList.contains('cancel-edit-label-btn')) {
                e.preventDefault();
                cancelEditLabel(labelItem);
            }
        });

        // Handle delete form submission
        labelsList.addEventListener('submit', function(e) {
            if (e.target.classList.contains('delete-label-form')) {
                e.preventDefault();
                const labelItem = e.target.closest('.label-item');
                const labelId = labelItem.dataset.labelId;
                const labelName = labelItem.querySelector('.label-name-display').textContent;
                
                if (confirm(`Delete label "${labelName}"?`)) {
                    deleteLabel(labelId, labelItem, e.target);
                }
            }
        });
    }

    function startEditLabel(labelItem) {
        // Prevent multiple edits at once
        const currentlyEditing = document.querySelector('.label-item.editing');
        if (currentlyEditing && currentlyEditing !== labelItem) {
            cancelEditLabel(currentlyEditing);
        }

        labelItem.classList.add('editing');
        
        const nameDisplay = labelItem.querySelector('.label-name-display');
        const editInput = labelItem.querySelector('.edit-label-input');
        const editBtn = labelItem.querySelector('.edit-label-btn');
        const saveBtn = labelItem.querySelector('.save-label-btn');
        const cancelBtn = labelItem.querySelector('.cancel-edit-label-btn');
        
        // Store original value for cancel functionality
        editInput.dataset.originalValue = nameDisplay.textContent;
        
        // Show/hide elements
        nameDisplay.style.display = 'none';
        editInput.classList.remove('hidden');
        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
        
        // Focus and select text
        editInput.focus();
        editInput.select();
        
        // Handle Enter and Escape keys
        editInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveEditLabel(labelItem, labelItem.dataset.labelId);
            } else if (e.key === 'Escape') {
                e.preventDefault();
                cancelEditLabel(labelItem);
            }
        });
    }

    function saveEditLabel(labelItem, labelId) {
        const editInput = labelItem.querySelector('.edit-label-input');
        const newName = editInput.value.trim();
        
        if (!newName) {
            editInput.focus();
            return;
        }

        const formData = new FormData();
        formData.append('name', newName);
        formData.append('_method', 'PUT');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch(`/labels/${labelId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const nameDisplay = labelItem.querySelector('.label-name-display');
                nameDisplay.textContent = data.label.name;
                finishEditLabel(labelItem);
                showToast(data.message || 'Label updated successfully!', 'success');
            } else {
                showToast(data.message || 'Failed to update label.', 'error');
                editInput.focus();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'error');
            editInput.focus();
        });
    }

    function cancelEditLabel(labelItem) {
        const editInput = labelItem.querySelector('.edit-label-input');
        editInput.value = editInput.dataset.originalValue;
        finishEditLabel(labelItem);
    }

    function finishEditLabel(labelItem) {
        labelItem.classList.remove('editing');
        
        const nameDisplay = labelItem.querySelector('.label-name-display');
        const editInput = labelItem.querySelector('.edit-label-input');
        const editBtn = labelItem.querySelector('.edit-label-btn');
        const saveBtn = labelItem.querySelector('.save-label-btn');
        const cancelBtn = labelItem.querySelector('.cancel-edit-label-btn');
        
        // Show/hide elements
        nameDisplay.style.display = 'inline';
        editInput.classList.add('hidden');
        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
    }

    function deleteLabel(labelId, labelItem, form) {
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch(`/labels/${labelId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                labelItem.remove();
                showToast(data.message || 'Label deleted successfully!', 'success');
                
                // Check if no labels remain
                const remainingLabels = labelsList.querySelectorAll('.label-item');
                if (remainingLabels.length === 0) {
                    labelsList.innerHTML = '<p class="text-gray-500">No labels yet.</p>';
                }
            } else {
                showToast(data.message || 'Failed to delete label.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'error');
        });
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>

@endsection