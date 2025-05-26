<div class="modal" id="noteModal">
    <div class="modal-content bg-note-white"> <!-- Default color, akan diubah oleh JS -->
        <button class="close" id="closeNoteModalBtn" aria-label="Close note editor">×</button>
        <form id="noteModalForm" action="" method="POST"> <!-- Action akan diisi oleh JS -->
            @csrf
            <input type="hidden" name="_method" id="noteModalMethodInput" value="POST">
            <input type="hidden" name="id" id="noteModalIdInput">

            <div class="mb-4">
                <input type="text" id="modalNoteTitleInput" name="title"
                    class="w-full outline-none text-xl font-google-sans font-medium mb-2 bg-transparent placeholder-gray-500 text-gray-800"
                    placeholder="Title" autocomplete="off">
                <textarea id="modalNoteContentTextarea" name="content" rows="8"
                    class="w-full outline-none text-gray-700 resize-none bg-transparent placeholder-gray-500 text-sm"
                    placeholder="Take a note..."></textarea>
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


            <div class="flex justify-between items-center">
                <div class="flex space-x-1 items-center">

                    <a href="#" id="modalSetReminderBtn"
                        class="p-2 rounded-full hover:bg-gray-400/20 text-gray-600" title="Set reminder for this note">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                    {{-- Collaborator Button --}}
                    {{-- <button type="button" class="p-2 rounded-full hover:bg-gray-400/20 text-gray-600" title="Add collaborator (Not implemented)">...</svg></button> --}}

                    <div class="relative">
                        <button type="button" class="p-2 rounded-full hover:bg-gray-400/20 text-gray-600"
                            title="Change color" id="modalColorPickerToggleBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </button>
                        <input type="hidden" name="color" id="modalNoteColorInput" value="white">
                        <div class="absolute bottom-full left-0 mb-2 bg-white shadow-xl rounded-lg p-2 hidden z-30 border"
                            id="modalColorPickerContainer">
                            <div class="flex flex-wrap gap-2 items-center">
                                @foreach ($noteColors as $colorName)
                                    {{-- Asumsi $noteColors ada dari controller/AppServiceProvider --}}
                                    <div class="color-option modal-color-option bg-note-{{ $colorName }} {{ $colorName == 'white' ? 'border border-gray-300' : '' }}"
                                        data-color="{{ $colorName }}" title="{{ ucfirst($colorName) }}"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- Image Button --}}
                    {{-- <button type="button" class="p-2 rounded-full hover:bg-gray-400/20 text-gray-600" title="Add image (Not implemented)">...</svg></button> --}}

                    <button type="button" class="p-2 rounded-full hover:bg-gray-400/20 text-gray-600"
                        title="Archive/Unarchive" id="modalArchiveToggleBtn">
                        <svg id="modalArchiveIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <svg id="modalUnarchiveIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4m-4-4l4 4m0 0l4-4m-4 4V4" />
                        </svg>
                    </button>
                    <input type="hidden" name="is_archived" id="modalIsArchivedInput" value="0">

                    <button type="button" class="p-2 rounded-full hover:bg-gray-400/20 text-red-500"
                        title="Move to Trash" id="modalDeleteNoteBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <button type="submit"
                    class="px-5 py-2 text-sm text-gray-800 font-medium hover:bg-gray-200/50 rounded-md"
                    id="modalSaveNoteBtn">Done</button>
            </div>
        </form>
    </div>
</div>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() { // Pastikan semua skrip modal ada di dalam ini
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.error('CSRF token not found!');
                // Anda mungkin ingin menghentikan eksekusi atau menampilkan pesan error ke pengguna
            }

            // --- Elemen Modal ---
            const modalSetReminderBtn = document.getElementById('modalSetReminderBtn');
            const noteModalElement = document.getElementById('noteModal');
            const noteModalForm = document.getElementById('noteModalForm');
            const noteModalMethodInput = document.getElementById('noteModalMethodInput');
            const noteModalIdInput = document.getElementById('noteModalIdInput');
            const modalNoteTitleInput = document.getElementById('modalNoteTitleInput');
            const modalNoteContentTextarea = document.getElementById('modalNoteContentTextarea');
            const modalNoteColorInput = document.getElementById('modalNoteColorInput');
            const modalNoteLabelsSelectElement = document.getElementById(
                'modalNoteLabelsSelectTom'); // Untuk TomSelect
            const modalIsArchivedInput = document.getElementById('modalIsArchivedInput');
            const closeNoteModalBtn = document.getElementById('closeNoteModalBtn');
            const modalSaveNoteBtn = document.getElementById('modalSaveNoteBtn');
            const modalDeleteNoteBtn = document.getElementById('modalDeleteNoteBtn');
            const modalArchiveToggleBtn = document.getElementById('modalArchiveToggleBtn');
            const modalArchiveIcon = document.getElementById('modalArchiveIcon');
            const modalUnarchiveIcon = document.getElementById('modalUnarchiveIcon');
            const modalColorPickerToggleBtn = document.getElementById('modalColorPickerToggleBtn');
            const modalColorPickerContainer = document.getElementById('modalColorPickerContainer');
            const modalColorOptions = modalColorPickerContainer ? modalColorPickerContainer.querySelectorAll(
                '.modal-color-option') : [];
            const modalContentElement = noteModalElement?.querySelector('.modal-content');

            let currentOpenNoteId = null;
            let currentIsArchived = false;
            let modalTomSelectInstance = null; // Instance TomSelect

            if (modalSetReminderBtn) {
                modalSetReminderBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (currentOpenNoteId) { // currentOpenNoteId dari logika modal
                        // Tutup modal saat ini
                        if (typeof gsap !== 'undefined') {
                            gsap.to(modalContentElement, {
                                opacity: 0,
                                y: -20,
                                duration: 0.2,
                                onComplete: () => {
                                    noteModalElement.classList.remove('show');
                                    // Arahkan ke halaman create reminder dengan note_id
                                    window.location.href =
                                        "{{ route('reminders.create') }}?note_id=" +
                                        currentOpenNoteId;
                                }
                            });
                        } else {
                            noteModalElement.classList.remove('show');
                            window.location.href = "{{ route('reminders.create') }}?note_id=" +
                                currentOpenNoteId;
                        }
                    } else {
                        // Jika note belum disimpan (modal create note baru), arahkan ke halaman create reminder umum
                        window.location.href = "{{ route('reminders.create') }}";
                    }
                });
            }

            // --- Inisialisasi TomSelect untuk Label di Modal ---
            if (modalNoteLabelsSelectElement && typeof TomSelect !== 'undefined') {
                modalTomSelectInstance = new TomSelect(modalNoteLabelsSelectElement, {
                    plugins: {
                        remove_button: {
                            title: 'Remove this item'
                        }
                    },
                    create: function(input, callback) {
                        if (!input.trim()) { // Jangan buat jika input kosong
                            callback();
                            return;
                        }
                        console.log('TomSelect: Attempting to create label - ', input);

                        // Sederhanakan pengecekan duplikasi (backend seharusnya menangani ini dengan baik)
                        // const existingOption = Object.values(this.options).find(opt => opt.text.toLowerCase() === input.toLowerCase());
                        // if (existingOption) {
                        //     alert(`Label "${input}" already exists or is very similar.`);
                        //     callback();
                        //     return;
                        // }

                        fetch("{{ route('labels.store') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    name: input
                                })
                            })
                            .then(res => {
                                if (!res.ok) return res.json().then(errData => {
                                    throw errData;
                                });
                                return res.json();
                            })
                            .then(data => {
                                if (data.success && data.label) {
                                    console.log('TomSelect: Label created - ', data.label);
                                    callback({
                                        value: data.label.id.toString(),
                                        text: data.label.name
                                    });
                                    // Opsional: Tambahkan opsi baru ini ke select lain yang mungkin ada
                                    // atau kirim event untuk memberitahu komponen lain agar refresh data label
                                } else {
                                    const errorMsg = data.message || (data.errors && data.errors
                                        .name ? data.errors.name[0] :
                                        'Could not create new label.');
                                    alert(errorMsg);
                                    callback();
                                }
                            })
                            .catch(error => {
                                console.error('TomSelect: Error creating label:', error);
                                let errorMsg = 'Error creating label.';
                                if (error && error.errors && error.errors.name) errorMsg = error
                                    .errors.name[0];
                                else if (error && error.message) errorMsg = error.message;
                                alert(errorMsg);
                                callback();
                            });
                    },
                    persist: false,
                    createOnBlur: true,
                    // render: { /* Kustomisasi render jika perlu */ },
                    // placeholder: "Add or select labels..." // Diambil dari HTML placeholder
                });
            }

            // --- Fungsi Helper Modal ---
            function setModalBackgroundColor(color) {
                if (modalContentElement) {
                    // Hapus kelas bg-note-* yang lama
                    let classes = modalContentElement.className.split(' ');
                    classes = classes.filter(cls => !cls.startsWith('bg-note-'));
                    modalContentElement.className = classes.join(' ') + ' bg-note-' + (color || 'white');
                }
            }

            function updateArchiveButtonStateInModal(isArchived) {
                currentIsArchived = isArchived; // Update state global modal
                if (modalIsArchivedInput) modalIsArchivedInput.value = isArchived ? '1' : '0';

                if (modalArchiveIcon && modalUnarchiveIcon && modalArchiveToggleBtn) {
                    if (isArchived) {
                        modalArchiveIcon.classList.add('hidden');
                        modalUnarchiveIcon.classList.remove('hidden');
                        modalArchiveToggleBtn.title = "Unarchive";
                    } else {
                        modalArchiveIcon.classList.remove('hidden');
                        modalUnarchiveIcon.classList.add('hidden');
                        modalArchiveToggleBtn.title = "Archive";
                    }
                }
            }

            // --- Fungsi Utama untuk Membuka dan Mengisi Modal ---
            window.openNoteModalWithData = function(
                data) { // Jadikan global agar bisa dipanggil dari notes/index.js
                if (!noteModalElement || !noteModalForm || !modalNoteTitleInput || !modalNoteContentTextarea ||
                    !modalNoteColorInput || !modalIsArchivedInput || !modalSaveNoteBtn || !
                    noteModalMethodInput || !noteModalIdInput) {
                    console.error("One or more modal elements are missing from the DOM.");
                    return;
                }
                currentOpenNoteId = data.id || null;

                noteModalForm.reset(); // Reset form
                if (modalTomSelectInstance) modalTomSelectInstance.clear(); // Clear TomSelect

                if (currentOpenNoteId) {
                    noteModalForm.action = `/notes/${currentOpenNoteId}`;
                    noteModalMethodInput.value = 'PUT';
                    noteModalIdInput.value = currentOpenNoteId;
                    modalSaveNoteBtn.textContent = 'Save Changes';
                } else {
                    noteModalForm.action = "{{ route('notes.store') }}";
                    noteModalMethodInput.value = 'POST';
                    noteModalIdInput.value = '';
                    modalSaveNoteBtn.textContent = 'Create Note';
                }

                modalNoteTitleInput.value = data.title || '';
                modalNoteContentTextarea.value = data.content || '';

                const initialColor = data.color || 'white';
                modalNoteColorInput.value = initialColor;
                setModalBackgroundColor(initialColor);
                modalColorOptions.forEach(opt => opt.classList.remove('ring-2', 'ring-offset-2',
                    'ring-blue-500'));
                const activeColorOption = modalColorPickerContainer?.querySelector(
                    `.modal-color-option[data-color="${initialColor}"]`);
                if (activeColorOption) activeColorOption.classList.add('ring-2', 'ring-offset-2',
                    'ring-blue-500');

                updateArchiveButtonStateInModal(data.is_archived || false);

                if (modalTomSelectInstance && data.labels && Array.isArray(data.labels)) {
                    modalTomSelectInstance.setValue(data.labels.map(String));
                } else if (modalNoteLabelsSelectElement && data.labels && Array.isArray(data
                        .labels)) { // Fallback
                    Array.from(modalNoteLabelsSelectElement.options).forEach(option => {
                        option.selected = data.labels.includes(parseInt(option.value));
                    });
                }


                noteModalElement.classList.add('show');
                if (typeof gsap !== 'undefined') {
                    gsap.fromTo(modalContentElement, {
                        opacity: 0,
                        y: -20
                    }, {
                        opacity: 1,
                        y: 0,
                        duration: 0.3
                    });
                }
                modalNoteContentTextarea.style.height = 'auto';
                modalNoteContentTextarea.style.height = (modalNoteContentTextarea.scrollHeight) + 'px';
                // Fokus ke konten jika ada, jika tidak ke judul
                if (modalNoteContentTextarea.value) modalNoteContentTextarea.focus();
                else modalNoteTitleInput.focus();
            }

            // --- Event Listeners untuk Elemen Modal ---
            if (modalNoteContentTextarea) {
                modalNoteContentTextarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }

            if (closeNoteModalBtn) {
                closeNoteModalBtn.addEventListener('click', function() {
                    if (typeof gsap !== 'undefined') {
                        gsap.to(modalContentElement, {
                            opacity: 0,
                            y: -20,
                            duration: 0.2,
                            onComplete: () => noteModalElement.classList.remove('show')
                        });
                    } else {
                        noteModalElement.classList.remove('show');
                    }
                });
            }

            window.addEventListener('click', function(event) {
                if (noteModalElement && event.target === noteModalElement) { // Klik di luar modal content
                    if (typeof gsap !== 'undefined') {
                        gsap.to(modalContentElement, {
                            opacity: 0,
                            y: -20,
                            duration: 0.2,
                            onComplete: () => noteModalElement.classList.remove('show')
                        });
                    } else {
                        noteModalElement.classList.remove('show');
                    }
                }
                if (modalColorPickerContainer && !modalColorPickerContainer.classList.contains('hidden') &&
                    !modalColorPickerContainer.contains(event.target) &&
                    event.target !== modalColorPickerToggleBtn && !modalColorPickerToggleBtn?.contains(event
                        .target)
                ) {
                    modalColorPickerContainer.classList.add('hidden');
                }
            });

            if (modalColorPickerToggleBtn && modalColorPickerContainer) {
                modalColorPickerToggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    modalColorPickerContainer.classList.toggle('hidden');
                });
            }

            modalColorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const color = this.dataset.color;
                    modalNoteColorInput.value = color;
                    setModalBackgroundColor(color);
                    if (modalColorPickerContainer) modalColorPickerContainer.classList.add(
                        'hidden');
                    modalColorOptions.forEach(opt => opt.classList.remove('ring-2', 'ring-offset-2',
                        'ring-blue-500'));
                    this.classList.add('ring-2', 'ring-offset-2', 'ring-blue-500');
                });
            });

            if (noteModalForm) {
                noteModalForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Kumpulkan data sebagai objek JavaScript
                    const noteData = {
                        title: modalNoteTitleInput.value,
                        content: modalNoteContentTextarea.value,
                        color: modalNoteColorInput.value,
                        is_archived: modalIsArchivedInput.value, // Ini akan '0' atau '1'
                        _token: csrfToken // CSRF token juga bisa ditaruh di header, tapi di body juga oke
                    };

                    // Ambil label yang dipilih dari TomSelect
                    const selectedLabels = [];
                    if (modalTomSelectInstance && modalTomSelectInstance.items) {
                        modalTomSelectInstance.items.forEach(labelId => selectedLabels.push(labelId));
                    }
                    // Selalu kirim field 'labels', bisa berupa array kosong jika tidak ada yang dipilih
                    noteData.labels = selectedLabels;


                    const method = noteModalMethodInput.value.toUpperCase();
                    if (method === 'PUT' || method === 'PATCH') {
                        noteData._method = method; // Untuk spoofing method
                    }

                    if (!noteData.title && !noteData.content) {
                        alert('Error: Title or content must be provided.');
                        return;
                    }
                    if (!noteData.color) {
                        alert('Error: Color is missing.');
                        return;
                    }

                    if (modalSaveNoteBtn) {
                        modalSaveNoteBtn.disabled = true;
                        modalSaveNoteBtn.innerHTML =
                            `<span class="loading-spinner-small inline-block border-2 border-gray-500 border-t-white rounded-full w-4 h-4 animate-spin mr-1"></span> Saving...`;
                    }

                    fetch(noteModalForm.action, {
                            method: 'POST', // Selalu POST, _method akan melakukan spoofing
                            headers: {
                                'Content-Type': 'application/json', // <--- UBAH KE JSON
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(noteData) // <--- KIRIM SEBAGAI JSON STRING
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(errData => {
                                    // Jika errData tidak memiliki struktur errors, buat struktur standar
                                    const errors = errData.errors || (errData.message ? {
                                        'general': [errData.message]
                                    } : {
                                        'general': ['An unknown error occurred.']
                                    });
                                    throw {
                                        status: response.status,
                                        data: {
                                            ...errData,
                                            errors: errors
                                        }
                                    };
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // ... (logika sukses, misal reload halaman) ...
                                window.location.reload();
                            } else {
                                // Ini seharusnya tidak tercapai jika penanganan error di atas sudah benar
                            }
                        })
                        .catch(errorInfo => {
                            console.error('Save/Update Error Details:', errorInfo);
                            let errorMessages = [
                                'An error occurred while saving the note.'
                            ]; // Default message
                            if (errorInfo && errorInfo.data && errorInfo.data.errors) {
                                errorMessages = Object.values(errorInfo.data.errors).flat().map(err =>
                                    `- ${err}`);
                            } else if (errorInfo && errorInfo.data && errorInfo.data.message) {
                                errorMessages = [`Error: ${errorInfo.data.message}`];
                            }
                            alert("Please correct the following errors:\n" + errorMessages.join('\n'));
                        })
                        .finally(() => {
                            if (modalSaveNoteBtn) {
                                modalSaveNoteBtn.disabled = false;
                                modalSaveNoteBtn.textContent = currentOpenNoteId ? 'Save Changes' :
                                    'Create Note';
                            }
                        });
                });
            }

            if (modalDeleteNoteBtn) {
                modalDeleteNoteBtn.addEventListener('click', function() {
                    if (!currentOpenNoteId) return;
                    if (confirm('Are you sure you want to move this note to trash?')) {
                        fetch(`/notes/${currentOpenNoteId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    noteModalElement.classList.remove('show');
                                    window.location.reload();
                                } else {
                                    alert('Error: ' + (data.message || 'Could not move to trash.'));
                                }
                            })
                            .catch(error => {
                                console.error('Delete Error:', error);
                                alert('An error occurred.');
                            });
                    }
                });
            }

            if (modalArchiveToggleBtn) {
                modalArchiveToggleBtn.addEventListener('click', function() {
                    if (!currentOpenNoteId) return;
                    const newArchivedState = !currentIsArchived;

                    fetch(`/notes/${currentOpenNoteId}/archive-toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                is_archived: newArchivedState
                            }) // Kirim state baru
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                updateArchiveButtonStateInModal(data.is_archived);
                                // Tidak perlu submit form utama, state is_archived akan dikirim saat save
                                // Jika ingin langsung update di DB tanpa save, backend harus handle ini
                            } else {
                                alert('Error: ' + (data.message || 'Could not toggle archive.'));
                            }
                        })
                        .catch(error => {
                            console.error('Archive Toggle Error:', error);
                            alert('An error occurred.');
                        });
                });
            }
        });

        document.addEventListener('labelsUpdated', function (event) {
    if (!modalTomSelectInstance) return; // Jika TomSelect belum diinisialisasi

    const { action, label, labelId } = event.detail;
    console.log('[NoteModal] Received labelsUpdated event for TomSelect:', event.detail);

    if (action === 'created' && label) {
        modalTomSelectInstance.addOption({
            value: label.id.toString(),
            text: label.name
        });
        // Anda mungkin ingin otomatis memilih label baru ini jika konteksnya sesuai
        // modalTomSelectInstance.addItem(label.id.toString());
        console.log('[NoteModal] Added new label to TomSelect:', label.name);
    } else if (action === 'updated' && label) {
        modalTomSelectInstance.updateOption(label.id.toString(), {
            value: label.id.toString(),
            text: label.name
        });
        // Jika label ini sedang dipilih, TomSelect akan otomatis update tampilannya
        console.log('[NoteModal] Updated label in TomSelect:', label.name);
    } else if (action === 'deleted' && labelId) {
        modalTomSelectInstance.removeOption(labelId.toString());
        // Jika label ini sedang dipilih, TomSelect akan otomatis menghapusnya dari pilihan
        console.log('[NoteModal] Removed label from TomSelect, ID:', labelId);
    }
    // Penting untuk me-refresh item jika ada perubahan pada opsi yang mungkin sedang dipilih
    // modalTomSelectInstance.refreshItems();
});
    </script>
    <style>
        /* Style spesifik modal, bisa dipindah ke file CSS utama jika diinginkan */
        .loading-spinner-small {
            border-top-color: transparent;
            /* Agar terlihat seperti spinner, bukan lingkaran penuh */
        }
    </style>
@endPushOnce
