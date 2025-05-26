<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class=""> {{-- Kelas 'dark' akan ditambahkan/dihapus oleh JS --}}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $pageTitle ?? 'MyNotes') - MyNotes</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    

    {{-- Fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Roboto:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- GSAP --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    {{-- TomSelect --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.10/dist/cdn.min.js"></script>

    {{-- Remixicon --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <script>
        (function() {
            const theme = localStorage.getItem('theme') || '{{ auth()->user()->theme_preference ?? "system" }}';
            console.log('[HEAD SCRIPT] Initial theme check...');
            let appliedTheme = 'system'; // Default jika tidak ada yang lain
            const localStorageTheme = localStorage.getItem('theme'); // light, dark, system, atau null
            const userDbPreference = "{{ Auth::check() && Auth::user()->theme_preference ? Auth::user()->theme_preference : 'system' }}";

            console.log(`[HEAD SCRIPT] LocalStorage: ${localStorageTheme}, UserDB: ${userDbPreference}`);

            // Prioritas 1: localStorage (jika bukan 'system' dan valid)
            if (localStorageTheme === 'light' || localStorageTheme === 'dark') {
                appliedTheme = localStorageTheme;
                console.log(`[HEAD SCRIPT] Using theme from localStorage: ${appliedTheme}`);
            }
            // Prioritas 2: Preferensi Database (jika localStorage 'system' atau tidak valid)
            else if (userDbPreference === 'light' || userDbPreference === 'dark') {
                appliedTheme = userDbPreference;
                console.log(`[HEAD SCRIPT] Using theme from User DB: ${appliedTheme}`);
            }
            // Prioritas 3 (atau jika appliedTheme masih 'system'): Preferensi OS
            // Ini juga akan menangani kasus di mana localStorage dan userDbPreference keduanya 'system'

            if (appliedTheme === 'system') {
                console.log('[HEAD SCRIPT] Resolved to "system", checking OS preference...');
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                    console.log('[HEAD SCRIPT] Applied .dark (from OS system preference)');
                } else {
                    document.documentElement.classList.remove('dark');
                    console.log('[HEAD SCRIPT] Applied .light (from OS system preference or default)');
                }
            } else if (appliedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                console.log(`[HEAD SCRIPT] Applied .dark (from explicit ${localStorageTheme ? 'localStorage' : 'User DB'})`);
            } else { // appliedTheme === 'light'
                document.documentElement.classList.remove('dark');
                console.log(`[HEAD SCRIPT] Applied .light (from explicit ${localStorageTheme ? 'localStorage' : 'User DB'})`);
            }
        })();
    

        // Konfigurasi Tailwind
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'sidebar': '#f5f5f5',
                        'note-white': '#ffffff',
                        'note-red': '#f28b82',
                        'note-orange': '#fbbc04',
                        'note-yellow': '#fff475',
                        'note-green': '#ccff90',
                        'note-teal': '#a7ffeb',
                        'note-blue': '#cbf0f8',
                        'note-purple': '#d7aefb',
                        'note-pink': '#fdcfe8',
                        'note-brown': '#e6c9a8',
                        'note-gray': '#e8eaed',
                        // Tambahkan warna lain jika perlu
                    },
                    fontFamily: {
                        'google-sans': ['"Google Sans"', 'sans-serif'],
                        'roboto': ['Roboto', 'sans-serif'],
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
        }

        html.dark {
            color-scheme: dark;
            /* Memberi tahu browser bahwa ini mode gelap, bisa mempengaruhi UI default browser seperti scrollbar */
        }

        html.dark body {
            background-color: #1f2937;
            bg-gray-800 / color: #d1d5db;
            text-gray-300
        }

        html.dark .bg-white {
            background-color: #374151 !important;/ bg-gray-700,
            !important jika ada override /
        }

        html.dark .bg-gray-50 {
            background-color: #374151 !important;
        }

        html.dark .bg-gray-100 {
            background-color: #4b5563 !important;/ bg-gray-600 /
        }

        html.dark .bg-sidebar {
            background-color: #111827 !important;/ bg-gray-900
        }

        html.dark .text-gray-900 {
            color: #f9fafb !important;
            text-gray-50 /
        }

        html.dark .text-gray-800 {
            color: #f3f4f6 !important;/ text-gray-100 /
        }

        html.dark .text-gray-700 {
            color: #e5e7eb !important;/ text-gray-200 /
        }

        html.dark .text-gray-600 {
            color: #d1d5db !important;/ text-gray-300 /
        }

        html.dark .text-gray-500 {
            color: #9ca3af !important;/ text-gray-400
        }

        html.dark .border-gray-200 {
            border-color: #4b5563 !important;
        }

        html.dark .border-gray-300 {
            border-color: #6b7280 !important;
        }

        /* ... dan seterusnya untuk semua warna yang Anda gunakan ... */
        /* Contoh untuk sidebar item aktif / */
        html.dark .sidebar-item.active {
            background-color: #4a3a00;/ Contoh warna gelap untuk item aktif / color: #fde047;
        }

        html.dark .sidebar-item:hover {
            background-color: #374151;/ Contoh hover gelap */
        }

        /* TomSelect di Dark Mode / */
        html.dark .ts-control {
            background-color: #374151 !important;
            border-color: #6b7280 !important;
            color: #d1d5db !important;
        }

        html.dark .ts-control input {
            color: #d1d5db !important;
        }

        html.dark .ts-dropdown {
            background-color: #374151 !important;
            border-color: #6b7280 !important;
        }

        html.dark .ts-dropdown .option {
            color: #d1d5db !important;
        }

        html.dark .ts-dropdown .active {
            background-color: #4b5563 !important;
        }

        html.dark .item {
            / Item yang dipilih di TomSelect background-color: #4b5563 !important;
            color: #f3f4f6 !important;
        }

        html.dark .item .remove {
            color: #f3f4f6 !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-google-sans {
            font-family: 'Google Sans', sans-serif;
        }

        .sidebar-item {
            transition: all 0.2s ease;
            border-radius: 0 25px 25px 0;
            padding: 8px 24px;
        }

        .sidebar-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .sidebar-item.active {
            background-color: #feefc3;
            color: #202124;
            font-weight: 500;
        }

        .note-card {
            transition: all 0.2s ease;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
        }

        .note-card:hover {
            box-shadow: 0 1px 2px 0 rgba(60, 64, 67, 0.302), 0 1px 3px 1px rgba(60, 64, 67, 0.149);
        }

        .search-container {
            transition: all 0.3s ease;
            background-color: #f1f3f4;
            border-radius: 8px;
        }

        .search-container:focus-within {
            background-color: #ffffff;
            box-shadow: 0 1px 1px 0 rgba(65, 69, 73, 0.3), 0 1px 3px 1px rgba(65, 69, 73, 0.15);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 8% auto;
            padding: 24px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px) scale(0.95);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .modal.show {
            opacity: 1;
            display: block;
        }

        .modal.show .modal-content {
            transform: translateY(0) scale(1);
        }

        .close {
            color: #5f6368;
            float: right;
            font-size: 28px;
            font-weight: bold;
            transition: color 0.2s;
        }

        .close:hover,
        .close:focus {
            color: #202124;
            text-decoration: none;
            cursor: pointer;
        }

        .color-option {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.1s ease-out, box-shadow 0.1s ease-out;
            border: 2px solid transparent;
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .color-option.selected {
            border-color: #4285f4;
            box-shadow: 0 0 0 2px #4285f4;
        }

        .masonry-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            grid-gap: 16px;
        }

        .label-chip {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 16px;
            background-color: rgba(0, 0, 0, 0.08);
            color: #3c4043;
            margin-right: 6px;
            margin-bottom: 6px;
            display: inline-block;
        }

        .note-actions-bar {
            display: flex;
            gap: 4px;
            margin-top: 12px;
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }

        .note-card:hover .note-actions-bar {
            opacity: 1;
        }

        .note-action-button {
            background: none;
            border: none;
            padding: 6px;
            border-radius: 50%;
            cursor: pointer;
            color: #5f6368;
        }

        .note-action-button:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 1px #1a73e8;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: #1a73e8;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1765cc;
        }

        .btn-secondary {
            background-color: #f1f3f4;
            color: #202124;
        }

        .btn-secondary:hover {
            background-color: #e8eaed;
        }

        .btn-danger {
            background-color: #d93025;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c5221f;
        }

        /* Alerts */
        .alert {
            @apply p-4 mb-4 rounded-md shadow-sm border-l-4;
        }

        .alert-success {
            @apply bg-green-50 dark:bg-green-900/30 border-green-500 dark:border-green-600 text-green-700 dark:text-green-300;
        }

        .alert-danger {
            @apply bg-red-50 dark:bg-red-900/30 border-red-500 dark:border-red-600 text-red-700 dark:text-red-300;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            @apply bg-gray-100 dark:bg-gray-700 rounded-full;
        }

        ::-webkit-scrollbar-thumb {
            @apply bg-gray-300 dark:bg-gray-500 rounded-full;
        }

        ::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-400 dark:bg-gray-400;
        }
    </style>
    @stack('styles')
</head>

<body class="antialiased"> {{-- Hapus text-gray-800, biarkan diatur oleh html atau html.dark --}}
    <div class="flex h-screen bg-white dark:bg-dark-bg">
        @auth
            @include('partials.sidebar') {{-- Sidebar di-include di sini --}}
        @endauth

        {{-- Area Konten Utama --}}
        <div class="flex-1 flex flex-col overflow-hidden
                    @auth md:ml-64 @endauth {{-- Default margin untuk desktop jika auth --}}
                    transition-all duration-300 ease-in-out"
            {{-- Ganti ke transition-margin jika hanya margin yg dianimasikan --}} id="mainContentArea">

            @auth
                @include('partials.navbar') {{-- Navbar di-include di sini --}}
            @endauth

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6 bg-gray-100 dark:bg-dark-bg">
                @if (session('success'))
                    <div id="alert-success"
                        class="alert alert-success p-4 mb-4 rounded-md shadow-sm animate__animated animate__fadeInDown"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div id="alert-errors"
                        class="alert alert-danger p-4 mb-4 rounded-md shadow-sm animate__animated animate__fadeInDown"
                        role="alert">
                        <p class="font-bold">Please correct the following errors:</p>
                        <ul class="mt-1 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Modals --}}
    @auth
        @if (View::exists('partials.note-modal'))
            @include('partials.note-modal')
        @endif
        @if (View::exists('partials.user-profile-modal'))
            @include('partials.user-profile-modal')
        @endif
        @if (View::exists('partials.label-manager-modal'))
            @include('partials.label-manager-modal')
        @endif
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[App Layout] DOMContentLoaded - Main Script');

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const MD_BREAKPOINT = 768; // Tailwind's default md breakpoint

            // Fungsi helper untuk AJAX
            async function fetchApi(url, options = {}) {
                const defaultOptions = {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...options.headers,
                    },
                };

                if (options.body && !(options.body instanceof FormData)) {
                    defaultOptions.headers['Content-Type'] = 'application/json';
                    options.body = JSON.stringify(options.body);
                }

                try {
                    const response = await fetch(url, {
                        ...defaultOptions,
                        ...options
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({
                            message: response.statusText
                        }));
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    }

                    return response.json();
                } catch (error) {
                    console.error('Fetch API Error:', error);
                    throw error;
                }
            }

            // Expose fetchApi to global scope
            window.fetchApi = fetchApi;

            // Modal helper functions
            window.openModal = function(modalElement) {
                if (modalElement) {
                    modalElement.classList.add('show');
                }
            };

            window.closeModal = function(modalElement) {
                if (modalElement) {
                    modalElement.classList.remove('show');
                }
            };

            // Auto-remove alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.add('animate__fadeOutUp');
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });

            // Authentication-specific functionality
            @auth
            console.log('[App Layout] Auth section JS loading...');

            // Sidebar functionality
            initializeSidebar();

            // User profile modal
            initializeUserProfileModal();

            // Label manager modal
            initializeLabelManagerModal();

            // Global modal click-outside handler
            initializeModalClickOutside();

            console.log('[App Layout] Auth section JS finished.');
        @endauth

        console.log('[App Layout] App JS initialized.');

        // ========== FUNCTIONS ==========

        function initializeSidebar() {
            const sidebarToggleBtn = document.getElementById('sidebarToggle');
            const sidebarElement = document.getElementById('sidebar');
            const mainContentAreaElement = document.getElementById('mainContentArea');

            if (!sidebarToggleBtn || !sidebarElement || !mainContentAreaElement) {
                console.warn('[App Layout] Sidebar elements not found:');
                if (!sidebarToggleBtn) console.warn('- sidebarToggleBtn missing');
                if (!sidebarElement) console.warn('- sidebarElement missing');
                if (!mainContentAreaElement) console.warn('- mainContentAreaElement missing');
                return;
            }

            console.log('[App Layout] Sidebar toggle elements found.');

            const applySidebarState = (isDesktopHidden, isMobileHidden) => {
                console.log(
                    `[App Layout] Applying state - Desktop Hidden: ${isDesktopHidden}, Mobile Hidden: ${isMobileHidden}`
                );

                // Desktop logic
                if (isDesktopHidden) {
                    sidebarElement.classList.add('md:-translate-x-full');
                    sidebarElement.classList.remove('md:translate-x-0');
                    mainContentAreaElement.classList.add('md:ml-0');
                    mainContentAreaElement.classList.remove('md:ml-64');
                } else {
                    sidebarElement.classList.remove('md:-translate-x-full');
                    sidebarElement.classList.add('md:translate-x-0');
                    mainContentAreaElement.classList.remove('md:ml-0');
                    mainContentAreaElement.classList.add('md:ml-64');
                }

                // Mobile logic
                if (isMobileHidden) {
                    sidebarElement.classList.add('-translate-x-full');
                } else {
                    sidebarElement.classList.remove('-translate-x-full');
                }
            };

            const toggleAndSaveSidebarState = () => {
                const isMobileView = window.innerWidth < MD_BREAKPOINT;
                let desktopHidden = localStorage.getItem('desktopSidebarHidden') === 'true';
                let mobileHidden = sidebarElement.classList.contains('-translate-x-full');

                console.log(
                    `[App Layout] Before toggle - Mobile View: ${isMobileView}, Desktop Hidden: ${desktopHidden}, Mobile Hidden: ${mobileHidden}`
                );

                if (isMobileView) {
                    // Mobile: toggle mobile state only
                    applySidebarState(desktopHidden, !mobileHidden);
                } else {
                    // Desktop: toggle desktop state and save to localStorage
                    desktopHidden = !desktopHidden;
                    localStorage.setItem('desktopSidebarHidden', desktopHidden.toString());
                    applySidebarState(desktopHidden, true); // Mobile always hidden on desktop
                }

                console.log(
                    `[App Layout] After toggle - Desktop Hidden: ${localStorage.getItem('desktopSidebarHidden')}, Mobile Hidden: ${sidebarElement.classList.contains('-translate-x-full')}`
                );
            };

            // Initialize sidebar state on page load
            const initialDesktopHidden = localStorage.getItem('desktopSidebarHidden') === 'true';
            applySidebarState(initialDesktopHidden, true); // Mobile starts hidden

            // Toggle button event
            sidebarToggleBtn.addEventListener('click', toggleAndSaveSidebarState);

            // Close mobile sidebar when clicking outside
            document.addEventListener('click', function(event) {
                if (window.innerWidth < MD_BREAKPOINT &&
                    !sidebarElement.classList.contains('-translate-x-full') &&
                    !sidebarElement.contains(event.target) &&
                    !sidebarToggleBtn.contains(event.target)) {

                    console.log('[App Layout] Closing mobile sidebar on outside click.');
                    const desktopHidden = localStorage.getItem('desktopSidebarHidden') === 'true';
                    applySidebarState(desktopHidden, true);
                }
            });
        }

        function initializeUserProfileModal() {
            const userProfileBtn = document.getElementById('userProfileBtn');
            const userProfileModal = document.getElementById('userProfileModal');
            const closeProfileModal = document.getElementById('closeProfileModal');

            if (userProfileBtn && userProfileModal && closeProfileModal) {
                userProfileBtn.addEventListener('click', () => {
                    userProfileModal.classList.add('show');
                });

                closeProfileModal.addEventListener('click', () => {
                    userProfileModal.classList.remove('show');
                });
            }
        }

        function initializeLabelManagerModal() {
            const openLabelManagerBtn = document.getElementById('openLabelManagerBtn');
            const labelManagerModal = document.getElementById('labelManagerModal');

            if (openLabelManagerBtn && labelManagerModal) {
                openLabelManagerBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    labelManagerModal.classList.add('show');

                    // GSAP animation if available
                    if (typeof gsap !== 'undefined') {
                        const modalContent = labelManagerModal.querySelector('.modal-content');
                        if (modalContent) {
                            gsap.fromTo(modalContent, {
                                opacity: 0,
                                y: -20
                            }, {
                                opacity: 1,
                                y: 0,
                                duration: 0.3
                            });
                        }
                    }
                });
            }
        }

        function initializeModalClickOutside() {
            window.addEventListener('click', function(event) {
                // User Profile Modal
                const userProfileModal = document.getElementById('userProfileModal');
                if (userProfileModal && event.target === userProfileModal) {
                    userProfileModal.classList.remove('show');
                }

                // Note Modal
                const noteModal = document.getElementById('noteModal');
                if (noteModal && event.target === noteModal) {
                    noteModal.classList.remove('show');
                }

                // Label Manager Modal
                const labelManagerModal = document.getElementById('labelManagerModal');
                if (labelManagerModal && event.target === labelManagerModal) {
                    labelManagerModal.classList.remove('show');
                }
            });
        }
        });
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            // ... (Logika sidebar toggle, user profile modal, mobile search) ...

            const openLabelManagerBtn = document.getElementById('openLabelManagerBtn');
            const labelManagerModal = document.getElementById('labelManagerModal');
            const closeLabelManagerModalBtn = labelManagerModal?.querySelector('#closeLabelManagerModalBtn');
            const doneLabelManagerBtn = labelManagerModal?.querySelector('#doneLabelManagerBtn');

            const createLabelForm = document.getElementById('createLabelForm');
            const newLabelNameInput = document.getElementById('newLabelNameInput');
            const createLabelError = document.getElementById('createLabelError');
            const labelsListEl = document.getElementById('labelsList'); // Ganti nama variabel agar tidak konflik
            const labelItemTemplateEl = document.getElementById('labelItemTemplate'); // Ganti nama variabel
            const noLabelsMessageEl = document.getElementById('noLabelsMessage'); // Ganti nama variabel

            // Fungsi untuk merender satu item label dan menambahkannya ke DOM
            function renderAndAppendLabelItem(label) {
                if (!labelItemTemplateEl || !labelsListEl || !label) return;

                const templateContent = labelItemTemplateEl.content.cloneNode(true);
                const listItem = templateContent.querySelector('li');
                if (!listItem) return;

                listItem.dataset.labelId = label.id;

                const colorDot = listItem.querySelector('.label-color-dot');
                if (colorDot) colorDot.style.backgroundColor = label.color || '#A0AEC0';

                const nameDisplay = listItem.querySelector('.label-name-display');
                if (nameDisplay) nameDisplay.textContent = label.name;

                const editInput = listItem.querySelector('.edit-label-input');
                if (editInput) editInput.value = label.name;

                const deleteForm = listItem.querySelector('.delete-label-form');
                if (deleteForm) deleteForm.action = `/labels/${label.id}`;

                attachLabelItemEventListeners(listItem); // Pasang event listener ke item baru
                labelsListEl.appendChild(listItem);

                if (noLabelsMessageEl) noLabelsMessageEl.classList.add('hidden'); // Sembunyikan pesan "no labels"
            }

            // Fungsi untuk me-refresh seluruh daftar label di modal (misalnya setelah membuka modal)
            async function refreshLabelsInModal() {
                if (!labelsListEl) return;
                // Ini adalah contoh sederhana, idealnya Anda punya endpoint API untuk get all labels user
                // Untuk sekarang, kita akan mengandalkan $allUserLabels yang di-pass saat modal di-include
                // Jika ingin AJAX, Anda perlu endpoint GET /labels (misalnya)
                // const data = await fetchApi('/labels/user-labels'); // Contoh endpoint
                // if (data && data.labels) {
                //     labelsListEl.innerHTML = ''; // Kosongkan list
                //     if (data.labels.length > 0) {
                //         data.labels.forEach(label => renderAndAppendLabelItem(label));
                //         if (noLabelsMessageEl) noLabelsMessageEl.classList.add('hidden');
                //     } else {
                //         if (noLabelsMessageEl) noLabelsMessageEl.classList.remove('hidden');
                //     }
                // }
                console.log("Modal label list would be refreshed here if AJAX endpoint existed.");
            }


            function attachLabelItemEventListeners(listItem) {
                const labelId = listItem.dataset.labelId;
                const nameDisplay = listItem.querySelector('.label-name-display');
                const editInput = listItem.querySelector('.edit-label-input');
                const editBtn = listItem.querySelector('.edit-label-btn');
                const saveBtn = listItem.querySelector('.save-label-btn');
                const cancelBtn = listItem.querySelector('.cancel-edit-label-btn');
                const deleteForm = listItem.querySelector('.delete-label-form');
                const deleteSubmitButton = deleteForm?.querySelector(
                    'button[type="submit"]'); // Targetkan tombol submit di dalam form

                editBtn?.addEventListener('click', function() {
                    // Tutup semua mode edit lain
                    labelsListEl.querySelectorAll('li.editing').forEach(editingLi => {
                        if (editingLi !== listItem) {
                            editingLi.querySelector('.cancel-edit-label-btn')?.click();
                        }
                    });
                    listItem.classList.add('editing');
                    nameDisplay.classList.add('hidden');
                    editInput.classList.remove('hidden');
                    editInput.focus();
                    editInput.select();
                    this.classList.add('hidden');
                    saveBtn?.classList.remove('hidden');
                    cancelBtn?.classList.remove('hidden');
                    deleteForm?.classList.add('hidden'); // Sembunyikan form delete saat edit
                });

                cancelBtn?.addEventListener('click', function() {
                    listItem.classList.remove('editing');
                    nameDisplay.classList.remove('hidden');
                    editInput.classList.add('hidden');
                    editInput.value = nameDisplay.textContent;
                    editBtn?.classList.remove('hidden');
                    saveBtn?.classList.add('hidden');
                    cancelBtn.classList.add('hidden');
                    deleteForm?.classList.remove('hidden');
                });

                saveBtn?.addEventListener('click', async function() {
                    const newName = editInput.value.trim();
                    if (!newName) {
                        alert('Label name cannot be empty.');
                        editInput.focus();
                        return;
                    }
                    if (newName === nameDisplay.textContent) {
                        cancelBtn.click();
                        return;
                    }

                    this.disabled = true;
                    this.innerHTML = `...`; // Loading state

                    try {
                        const data = await fetchApi(`/labels/${labelId}`, { // Rute labels.update
                            method: 'PUT', // Laravel handle ini dari _method di body jika method: 'POST'
                            body: {
                                name: newName,
                                _method: 'PUT'
                            } // Kirim sebagai JSON
                        });
                        if (data.success && data.label) {
                            nameDisplay.textContent = data.label.name;
                            // TODO: Dispatch event untuk update sidebar dan TomSelect
                            document.dispatchEvent(new CustomEvent('labelUpdated', {
                                detail: {
                                    label: data.label
                                }
                            }));
                        } else {
                            alert(data.message || (data.errors && data.errors.name ? data.errors.name[
                                0] : 'Could not update label.'));
                            editInput.value = nameDisplay.textContent;
                        }
                    } catch (error) {
                        alert(error.message || 'An error occurred while updating label.');
                        editInput.value = nameDisplay.textContent;
                    } finally {
                        this.disabled = false;
                        this.innerHTML = `<svg><!-- Save Icon --></svg>`;
                        cancelBtn.click();
                    }
                });

                if (data.success && data.label) {
                    nameDisplay.textContent = data.label.name;
                    console.log('[LabelManager] Dispatching labelsUpdated (updated) event.');
                    document.dispatchEvent(new CustomEvent('labelsUpdated', {
                        detail: {
                            action: 'updated',
                            label: data.label
                        }
                    }));
                }

                // Contoh setelah label di-delete
                if (data.success) {
                    listItem.remove();
                    // ... (update pesan no labels) ...
                    console.log('[LabelManager] Dispatching labelsUpdated (deleted) event.');
                    document.dispatchEvent(new CustomEvent('labelsUpdated', {
                        detail: {
                            action: 'deleted',
                            labelId: labelId
                        }
                    }));
                }

                deleteForm?.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    if (!confirm(
                            `Delete label "${nameDisplay.textContent}"? This will remove the label from all notes.`
                        )) return;

                    // Simpan referensi ke tombol sebelum mengubah innerHTML
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalButtonContent = submitButton.innerHTML;
                    submitButton.innerHTML = `...`; // Loading
                    submitButton.disabled = true;

                    try {
                        const data = await fetchApi(this.action, {
                            method: 'DELETE'
                        }); // this.action dari HTML form
                        if (data.success) {
                            listItem.remove();
                            if (labelsListEl.children.length === 0 && noLabelsMessageEl) {
                                noLabelsMessageEl.classList.remove('hidden');
                            }
                            // TODO: Dispatch event untuk update sidebar dan TomSelect
                            document.dispatchEvent(new CustomEvent('labelDeleted', {
                                detail: {
                                    labelId: labelId
                                }
                            }));
                        } else {
                            alert(data.message || 'Could not delete label.');
                        }
                    } catch (error) {
                        alert(error.message || 'An error occurred while deleting label.');
                    } finally {
                        if (submitButton) { // Cek lagi karena elemen mungkin sudah dihapus
                            submitButton.innerHTML = originalButtonContent;
                            submitButton.disabled = false;
                        }
                    }
                });
            }

            // Attach event listeners untuk item label yang sudah ada saat modal pertama kali dimuat
            labelsListEl?.querySelectorAll('li[data-label-id]').forEach(item => attachLabelItemEventListeners(
                item));


            if (openLabelManagerBtn && labelManagerModal) {
                openLabelManagerBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // refreshLabelsInModal(); // Panggil ini jika Anda punya endpoint API untuk get labels
                    window.openModal(labelManagerModal);
                });
            }
            if (closeLabelManagerModalBtn || doneLabelManagerBtn) {
                [closeLabelManagerModalBtn, doneLabelManagerBtn].forEach(btn => {
                    btn?.addEventListener('click', () => window.closeModal(labelManagerModal));
                });
            }

            if (createLabelForm && newLabelNameInput && labelsListEl && labelItemTemplateEl) {
                createLabelForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const labelName = newLabelNameInput.value.trim();
                    if (!labelName) {
                        if (createLabelError) createLabelError.textContent =
                            'Label name cannot be empty.';
                        return;
                    }
                    if (createLabelError) createLabelError.textContent = '';
                    const submitButton = this.querySelector('button[type="submit"]');
                    // ... (Logika loading state tombol) ...
                    submitButton.disabled = true; /* ... */

                    try {
                        const data = await fetchApi(this.action, { // route('labels.store')
                            method: 'POST',
                            body: {
                                name: labelName
                            }
                        });
                        if (data.success && data.label) {
                            renderAndAppendLabelItem(data.label); // Update UI modal
                            newLabelNameInput.value = '';
                            if (noLabelsMessageEl) noLabelsMessageEl.classList.add('hidden');

                            // Dispatch event agar sidebar dan komponen lain bisa update
                            console.log('[LabelManager] Dispatching labelsUpdated (created) event.');
                            document.dispatchEvent(new CustomEvent('labelsUpdated', {
                                detail: {
                                    action: 'created',
                                    label: data.label
                                }
                            }));

                        } else {
                            if (createLabelError) createLabelError.textContent = data.message || (data
                                .errors && data.errors.name ? data.errors.name[0] :
                                'Could not create label.');
                        }
                    } catch (error) {
                        if (createLabelError) createLabelError.textContent = error.message ||
                            'An error occurred.';
                    } finally {
                        submitButton.disabled = false; /* ... (reset innerHTML) ... */
                    }
                });
            }
        @endauth
        });


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
                if (isQuickCreateExpanded) return;
                isQuickCreateExpanded = true;

                console.log('Expanding quick create area');
                if (quickCreateNoteContainer) quickCreateNoteContainer.classList.add('shadow-xl');
                if (quickCreateContentArea) quickCreateContentArea.classList.remove('hidden');
                if (quickCreateActions) quickCreateActions.classList.remove('hidden');

                if (quickCreateNoteContentTextarea) {
                    quickCreateNoteContentTextarea.style.height = 'auto';
                    quickCreateNoteContentTextarea.style.height = (quickCreateNoteContentTextarea.scrollHeight) +
                        'px';
                }
            }

            function collapseQuickCreate() {
                isQuickCreateExpanded = false;
                if (quickCreateNoteContainer) {
                    quickCreateNoteContainer.classList.remove('shadow-xl');
                    // Reset background color to default
                    quickCreateNoteContainer.className = quickCreateNoteContainer.className.replace(/bg-note-\w+/g,
                        '');
                    quickCreateNoteContainer.classList.add('bg-white', 'dark:bg-dark-surface');
                }
                if (quickCreateContentArea) quickCreateContentArea.classList.add('hidden');
                if (quickCreateActions) quickCreateActions.classList.add('hidden');
                if (quickCreateForm) quickCreateForm.reset();
                if (quickCreateNoteColorInput) quickCreateNoteColorInput.value = 'white';
                if (quickCreateColorPalette) quickCreateColorPalette.classList.add('hidden');
            }

            // Title input focus and input events
            if (quickCreateNoteTitleInput) {
                quickCreateNoteTitleInput.addEventListener('focus', function() {
                    console.log('Title input focused');
                    expandQuickCreate();
                });

                quickCreateNoteTitleInput.addEventListener('input', function() {
                    console.log('Title input value:', this.value);
                    if (this.value.trim() !== '' && !isQuickCreateExpanded) {
                        expandQuickCreate();
                    }
                });
            }

            // Content textarea auto-resize
            if (quickCreateNoteContentTextarea) {
                quickCreateNoteContentTextarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });

                quickCreateNoteContentTextarea.addEventListener('focus', function() {
                    if (!isQuickCreateExpanded) {
                        expandQuickCreate();
                    }
                });
            }

            // Close button
            if (closeQuickCreateBtn) {
                closeQuickCreateBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    collapseQuickCreate();
                });
            }

            // Color picker functionality
            if (quickCreateColorToggle && quickCreateColorPalette) {
                quickCreateColorToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Color toggle clicked');
                    quickCreateColorPalette.classList.toggle('hidden');
                });

                // Color selection
                quickCreateColorPalette.querySelectorAll('.quick-color-option').forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const color = this.dataset.color;
                        console.log('Color selected:', color);

                        // Update hidden input
                        if (quickCreateNoteColorInput) {
                            quickCreateNoteColorInput.value = color;
                        }

                        // Update container background with live preview
                        if (quickCreateNoteContainer) {
                            // Remove existing color classes
                            quickCreateNoteContainer.className = quickCreateNoteContainer.className
                                .replace(/bg-note-\w+/g, '');
                            quickCreateNoteContainer.className = quickCreateNoteContainer.className
                                .replace(/bg-white|dark:bg-dark-surface/g, '');

                            // Add new color class
                            quickCreateNoteContainer.classList.add(`bg-note-${color}`);

                            // Special handling for white color in dark mode
                            if (color === 'white') {
                                quickCreateNoteContainer.classList.add('dark:bg-dark-surface');
                            }
                        }

                        // Hide color palette
                        quickCreateColorPalette.classList.add('hidden');
                    });
                });
            }

            // Reminder button (placeholder functionality)
            const reminderBtn = quickCreateActions?.querySelector('button[title*="reminder"]');
            if (reminderBtn) {
                reminderBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // For now, show a message that this feature opens the full editor
                    alert(
                        'Reminder feature is available in the full note editor. This quick note will be saved and you can add reminders by editing it.'
                    );

                    // Alternatively, you could redirect to the full create page:
                    // window.location.href = '/notes/create';
                });
            }

            // Click outside to collapse
            document.addEventListener('click', function(event) {
                if (quickCreateNoteContainer && !quickCreateNoteContainer.contains(event.target)) {
                    // Only collapse if both inputs are empty
                    const titleEmpty = !quickCreateNoteTitleInput?.value.trim();
                    const contentEmpty = !quickCreateNoteContentTextarea?.value.trim();

                    if (titleEmpty && contentEmpty) {
                        collapseQuickCreate();
                    }
                }

                // Hide color palette if clicking outside
                if (quickCreateColorPalette &&
                    !quickCreateColorPalette.contains(event.target) &&
                    event.target !== quickCreateColorToggle &&
                    !quickCreateColorToggle?.contains(event.target)) {
                    quickCreateColorPalette.classList.add('hidden');
                }
            });

            // Form submission
            if (quickCreateForm) {
                quickCreateForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(quickCreateForm);
                    const title = formData.get('title')?.trim();
                    const content = formData.get('content')?.trim();
                    const saveButton = document.getElementById('saveQuickCreateBtn');

                    // Validation
                    if (!title && !content) {
                        alert('Please enter a title or content.');
                        return;
                    }

                    // Show loading state
                    if (saveButton) {
                        saveButton.disabled = true;
                        saveButton.innerHTML = `
                    <span class="inline-block border-2 border-gray-500 border-t-white rounded-full w-4 h-4 animate-spin mr-1"></span> 
                    Saving...
                `;
                    }

                    // Submit the form
                    fetch(quickCreateForm.action, {
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
                                // Show success message briefly before reload
                                if (saveButton) {
                                    saveButton.innerHTML = '✓ Saved!';
                                    saveButton.classList.add('bg-green-600');
                                }
                                setTimeout(() => {
                                    window.location.reload();
                                }, 500);
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
                            alert('An error occurred while saving the note.');
                        })
                        .finally(() => {
                            // Reset button state
                            if (saveButton) {
                                saveButton.disabled = false;
                                saveButton.innerHTML = 'Save';
                                saveButton.classList.remove('bg-green-600');
                            }
                        });
                });
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Escape key to close quick create
                if (e.key === 'Escape' && isQuickCreateExpanded) {
                    const titleEmpty = !quickCreateNoteTitleInput?.value.trim();
                    const contentEmpty = !quickCreateNoteContentTextarea?.value.trim();

                    if (titleEmpty && contentEmpty) {
                        collapseQuickCreate();
                    }
                }

                // Ctrl/Cmd + Enter to save
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter' && isQuickCreateExpanded) {
                    e.preventDefault();
                    quickCreateForm?.dispatchEvent(new Event('submit'));
                }
            });

            // --- Note Card Click to open Modal & Actions ---
            const noteModal = document.getElementById('noteModal');
            document.querySelectorAll('.note-card').forEach(card => {
                // Click on card to open modal
                card.addEventListener('click', function(event) {
                    if (event.target.closest('form, button, .note-card-actions')) {
                        return; // Don't open modal if clicking on action buttons
                    }

                    if (typeof openNoteModalWithData === 'function') {
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

                // Keyboard navigation for cards
                card.addEventListener('keydown', function(event) {
                    if ((event.key === 'Enter' || event.key === ' ') &&
                        !event.target.closest('form, button, .note-card-actions')) {
                        event.preventDefault();
                        if (typeof openNoteModalWithData === 'function') {
                            openNoteModalWithData({
                                id: this.dataset.id,
                                title: this.dataset.title,
                                content: this.dataset.content,
                                color: this.dataset.color,
                                is_archived: this.dataset.archived === 'true',
                                labels: JSON.parse(this.dataset.labels || '[]')
                            });
                        }
                    }
                });

                // Edit button in card
                card.querySelectorAll('.open-in-modal-btn').forEach(button => {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation();
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

            // --- Search functionality ---
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const currentForm = this.closest('form');
                    if (this.value.length > 1 || this.value.length === 0) {
                        searchTimeout = setTimeout(() => {
                            currentForm.submit();
                        }, 600);
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
