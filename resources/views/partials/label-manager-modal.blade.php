{{-- resources/views/partials/label-manager-modal.blade.php --}}
<div class="modal" id="labelManagerModal" aria-labelledby="labelManagerModalTitle" role="dialog" aria-modal="true">
    <div class="modal-content bg-white dark:bg-gray-800" style="max-width: 500px;">
        <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
            <h2 class="text-xl font-google-sans font-medium text-gray-800 dark:text-gray-200" id="labelManagerModalTitle">
                Manage Labels</h2>
            <button class="close text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300"
                id="closeLabelManagerModalBtn" aria-label="Close label manager">×</button>
        </div>

        <div class="mt-6">
            <!-- Form untuk membuat label baru -->
            <form id="createLabelForm" action="{{ route('labels.store') }}" method="POST"
                class="mb-6 pb-6 border-b dark:border-gray-700">
                @csrf
                <label for="newLabelNameInput"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Create new label</label>
                <div class="flex items-center space-x-2">
                    <input type="text" name="name" id="newLabelNameInput" placeholder="Enter label name"
                        class="form-input flex-grow text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                        required>
                    {{-- Opsional: Color picker untuk label (bisa ditambahkan nanti) --}}
                    {{-- <input type="color" name="color" value="#CCCCCC" class="h-10 w-10 rounded"> --}}
                    <button type="submit" id="createLabelSubmitBtn"
                        class="btn btn-primary text-sm px-4 py-2 whitespace-nowrap">
                        Create
                    </button>
                </div>
                <div id="createLabelError" class="text-red-500 text-xs mt-1"></div>
            </form>

            <!-- Daftar label yang sudah ada -->
            <div id="existingLabelsListContainer">
                <h3 class="text-md font-google-sans text-gray-700 dark:text-gray-300 mb-3">Existing Labels:</h3>
                <div id="labelsList" class="space-y-2 max-h-60 overflow-y-auto pr-2">
                    {{-- Daftar label akan di-render oleh JavaScript atau Blade jika sudah ada saat modal dibuka --}}
                    @if (isset($allUserLabels) && $allUserLabels->isNotEmpty())
                        @foreach ($allUserLabels as $label)
                            @include('partials.label-item', ['label' => $label])
                        @endforeach
                    @else
                        <p id="noLabelsMessage" class="text-sm text-gray-500 dark:text-gray-400 italic">No labels
                            created yet.</p>
                    @endif
                </div>
            </div>

            <div class="mt-8 pt-4 border-t dark:border-gray-700 text-right">
                <button type="button" id="doneLabelManagerBtn" class="btn btn-secondary">Done</button>
            </div>
        </div>
    </div>
</div>

{{-- Template untuk item label (digunakan oleh JavaScript) --}}
<template id="labelItemTemplate">
    <li class="flex justify-between items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded group"
        data-label-id="">
        <div class="flex items-center">
            <span class="label-color-dot w-3 h-3 rounded-full mr-3" style="background-color: #A0AEC0;"></span>
            <span class="label-name-display text-sm text-gray-700 dark:text-gray-300">Label Name</span>
            <input type="text" value=""
                class="form-input edit-label-input hidden text-sm p-1 border-blue-500 dark:bg-gray-600 dark:border-blue-400 dark:text-gray-200"
                style="max-width: 150px;">
        </div>
        <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <button type="button"
                class="edit-label-btn p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </button>
            <button type="button"
                class="save-label-btn p-1.5 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hidden"
                title="Save">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>
            <button type="button"
                class="cancel-edit-label-btn p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hidden"
                title="Cancel">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <form class="delete-label-form inline-block" action="" method="POST"> {{-- Action akan diisi JS --}}
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="p-1.5 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                    title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>
    </li>
</template>

@push('script')
    <script>
        // Label Manager JavaScript - Add this to your main JS file or create a separate file

        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const labelManagerModal = document.getElementById('labelManagerModal');
            const openLabelManagerBtn = document.getElementById('openLabelManagerBtn');
            const closeLabelManagerModalBtn = document.getElementById('closeLabelManagerModalBtn');
            const doneLabelManagerBtn = document.getElementById('doneLabelManagerBtn');

            // Form elements
            const createLabelForm = document.getElementById('createLabelForm');
            const newLabelNameInput = document.getElementById('newLabelNameInput');
            const createLabelError = document.getElementById('createLabelError');
            const labelsList = document.getElementById('labelsList');
            const noLabelsMessage = document.getElementById('noLabelsMessage');

            // Open modal
            if (openLabelManagerBtn) {
                openLabelManagerBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openLabelManagerModal();
                });
            }

            // Close modal
            if (closeLabelManagerModalBtn) {
                closeLabelManagerModalBtn.addEventListener('click', closeLabelManagerModal);
            }

            if (doneLabelManagerBtn) {
                doneLabelManagerBtn.addEventListener('click', closeLabelManagerModal);
            }

            // Close modal when clicking outside
            if (labelManagerModal) {
                labelManagerModal.addEventListener('click', function(e) {
                    if (e.target === labelManagerModal) {
                        closeLabelManagerModal();
                    }
                });
            }

            // Create new label
            if (createLabelForm) {
                createLabelForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    createLabel();
                });
            }

            // Event delegation for edit, save, cancel, and delete buttons
            if (labelsList) {
                labelsList.addEventListener('click', function(e) {
                    const target = e.target.closest('button');
                    if (!target) return;

                    const labelItem = target.closest('.label-item');
                    const labelId = labelItem.dataset.labelId;

                    if (target.classList.contains('edit-label-btn')) {
                        startEditLabel(labelItem);
                    } else if (target.classList.contains('save-label-btn')) {
                        saveEditLabel(labelItem, labelId);
                    } else if (target.classList.contains('cancel-edit-label-btn')) {
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
                            deleteLabel(labelId, labelItem);
                        }
                    }
                });
            }

            // Functions
            function openLabelManagerModal() {
                labelManagerModal.classList.add('show');
                document.body.style.overflow = 'hidden';
                loadLabels(); // Refresh labels when opening modal
            }

            function closeLabelManagerModal() {
                labelManagerModal.classList.remove('show');
                document.body.style.overflow = '';
                clearCreateLabelForm();
                // Cancel any ongoing edits
                const editingItems = labelsList.querySelectorAll('.label-item.editing');
                editingItems.forEach(item => cancelEditLabel(item));
            }

            function clearCreateLabelForm() {
                newLabelNameInput.value = '';
                createLabelError.textContent = '';
                createLabelError.style.display = 'none';
            }

            function showError(element, message) {
                element.textContent = message;
                element.style.display = 'block';
            }

            function hideError(element) {
                element.textContent = '';
                element.style.display = 'none';
            }

            function createLabel() {
                const name = newLabelNameInput.value.trim();

                if (!name) {
                    showError(createLabelError, 'Label name is required.');
                    return;
                }

                const formData = new FormData(createLabelForm);

                fetch(createLabelForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            addLabelToList(data.label);
                            clearCreateLabelForm();
                            hideNoLabelsMessage();
                            showToast(data.message || 'Label created successfully!', 'success');
                        } else {
                            showError(createLabelError, data.message || 'Failed to create label.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError(createLabelError, 'An error occurred. Please try again.');
                    });
            }

            function loadLabels() {
                // This function can be used to refresh the labels list if needed
                // For now, we'll rely on the server-side rendering
            }

            function addLabelToList(label) {
                const template = document.getElementById('labelItemTemplate');
                const clone = template.content.cloneNode(true);

                const listItem = clone.querySelector('li');
                listItem.dataset.labelId = label.id;

                const colorDot = clone.querySelector('.label-color-dot');
                colorDot.style.backgroundColor = label.color || '#A0AEC0';

                const nameDisplay = clone.querySelector('.label-name-display');
                nameDisplay.textContent = label.name;

                const editInput = clone.querySelector('.edit-label-input');
                editInput.value = label.name;

                const deleteForm = clone.querySelector('.delete-label-form');
                deleteForm.action = `/labels/${label.id}`;

                labelsList.appendChild(clone);
            }

            function hideNoLabelsMessage() {
                if (noLabelsMessage) {
                    noLabelsMessage.style.display = 'none';
                }
            }

            function showNoLabelsMessage() {
                if (noLabelsMessage && labelsList.children.length === 0) {
                    noLabelsMessage.style.display = 'block';
                }
            }

            function startEditLabel(labelItem) {
                // Prevent multiple edits at once
                const currentlyEditing = labelsList.querySelector('.label-item.editing');
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
                nameDisplay.classList.add('hidden');
                editInput.classList.remove('hidden');
                editBtn.classList.add('hidden');
                saveBtn.classList.remove('hidden');
                cancelBtn.classList.remove('hidden');

                // Focus and select text
                editInput.focus();
                editInput.select();

                // Handle Enter and Escape keys
                editInput.addEventListener('keydown', handleEditKeydown);
            }

            function handleEditKeydown(e) {
                const labelItem = e.target.closest('.label-item');
                const labelId = labelItem.dataset.labelId;

                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveEditLabel(labelItem, labelId);
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    cancelEditLabel(labelItem);
                }
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
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content'));

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

                            // Update sidebar if label name changed
                            updateSidebarLabel(labelId, data.label.name);
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
                nameDisplay.classList.remove('hidden');
                editInput.classList.add('hidden');
                editBtn.classList.remove('hidden');
                saveBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');

                // Remove event listener
                editInput.removeEventListener('keydown', handleEditKeydown);
            }

            function deleteLabel(labelId, labelItem) {
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content'));

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
                            showNoLabelsMessage();

                            // Remove from sidebar
                            removeSidebarLabel(labelId);
                        } else {
                            showToast(data.message || 'Failed to delete label.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred. Please try again.', 'error');
                    });
            }

            function updateSidebarLabel(labelId, newName) {
                // Update label name in sidebar if it exists
                const sidebarLink = document.querySelector(`a[href="/labels/${labelId}"] span`);
                if (sidebarLink) {
                    sidebarLink.textContent = newName;
                }
            }

            function removeSidebarLabel(labelId) {
                // Remove label from sidebar if it exists
                const sidebarLink = document.querySelector(`a[href="/labels/${labelId}"]`);
                if (sidebarLink) {
                    sidebarLink.remove();

                    // Check if no labels remain and show "No labels yet" message
                    const labelsSection = sidebarLink.closest('.pt-2');
                    const remainingLabels = labelsSection.querySelectorAll(
                        'a[href*="/labels/"]:not([id="openLabelManagerBtn"])');
                    if (remainingLabels.length === 0) {
                        const noLabelsMsg = labelsSection.querySelector('p');
                        if (noLabelsMsg) {
                            noLabelsMsg.style.display = 'block';
                        }
                    }
                }
            }

            // Toast notification function (you can customize this)
            function showToast(message, type = 'info') {
                // Simple toast implementation - you can replace with your preferred toast library
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

        if (data.success && data.label) {
    renderAndAppendLabelItem(data.label); // Update UI modal
    newLabelNameInput.value = '';
    if (noLabelsMessageEl) noLabelsMessageEl.classList.add('hidden');

    console.log('[LabelManager] Dispatching labelsUpdated (created)');
    document.dispatchEvent(new CustomEvent('labelsUpdated', { detail: { action: 'created', label: data.label } }));
}

// Di dalam fungsi AJAX untuk update label (setelah sukses)
if (data.success && data.label) {
    nameDisplay.textContent = data.label.name;
    // Update warna dot jika warna juga diupdate
    const colorDot = listItem.querySelector('.label-color-dot');
    if (colorDot && data.label.color) colorDot.style.backgroundColor = data.label.color;


    console.log('[LabelManager] Dispatching labelsUpdated (updated)');
    document.dispatchEvent(new CustomEvent('labelsUpdated', { detail: { action: 'updated', label: data.label } }));
}

// Di dalam fungsi AJAX untuk delete label (setelah sukses)
if (data.success) {
    const labelIdToDelete = listItem.dataset.labelId; // Ambil ID sebelum remove
    listItem.remove();
    if (labelsListEl.children.length === 0 && noLabelsMessageEl) {
        noLabelsMessageEl.classList.remove('hidden');
    }
    console.log('[LabelManager] Dispatching labelsUpdated (deleted)');
    document.dispatchEvent(new CustomEvent('labelsUpdated', { detail: { action: 'deleted', labelId: labelIdToDelete } }));
}
    </script>
@stack('scripts')

    {{-- Buat file partials/label-item.blade.php untuk me-render item label --}}
    {{-- Ini akan digunakan saat load awal dan oleh JS untuk menambah item baru --}}
