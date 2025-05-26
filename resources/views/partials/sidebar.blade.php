<aside
    class="w-64 h-screen bg-sidebar flex-shrink-0 border-r border-gray-200 fixed left-0 top-0 z-30
           overflow-y-auto transition-transform duration-300 ease-in-out
           -translate-x-full md:translate-x-0"
    {{-- Default state: hidden di mobile, visible di desktop --}} id="sidebar">
    <div class="p-4">
        <a href="{{ route('home') }}" class="flex items-center mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="ml-2 text-lg font-google-sans font-medium text-gray-800">MyNotes</span>
        </a>

        <nav class="space-y-1 mb-8">
            <a href="{{ route('home') }}"
                class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }} flex items-center text-gray-700 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Notes</span>
            </a>
            <a href="{{ route('reminders.index') }}"
                class="sidebar-item {{ request()->routeIs('reminders.index') ? 'active' : '' }} flex items-center text-gray-700 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span>Reminders</span>
            </a>

            <div class="pt-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase px-4 mb-2">Labels</h3>
                <div id="sidebarLabelList" class="max-h-40 overflow-y-auto mb-1 ">
                    {{-- PENGGUNAAN @forelse --}}
                    @forelse($labelsForSidebar as $labelObj)
                        {{-- BUKA FORELSE --}}
                        <a href="{{ route('labels.show', $labelObj->id) }}"
                            class="sidebar-item gap-3 {{ request()->routeIs('labels.show') && request()->route('label') && request()->route('label')->id == $labelObj->id ? 'active' : '' }} flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="truncate">{{ $labelObj->name }}</span>
                        </a>
                    @empty {{-- BAGIAN @empty UNTUK @forelse --}}
                        <p class="px-4 text-sm text-gray-500 dark:text-gray-400 italic ">No labels yet.</p>
                    @endforelse {{-- TUTUP @forelse --}}
                </div>
                <a href="#" id="openLabelManagerBtn"
                    class="sidebar-item {{-- request()->routeIs('labels.manage') ? 'active' : '' --}} flex items-center mt-1 gap-3"> {{-- Dikomentari karena labels.manage mungkin tidak ada --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span>Edit labels</span>
                </a>
            </div>

            <a href="{{ route('archive.index') }}"
                class="sidebar-item {{ request()->routeIs('archive.index') ? 'active' : '' }} flex items-center text-gray-700 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                <span>Archive</span>
            </a>
            <a href="{{ route('trash.index') }}"
                class="sidebar-item {{ request()->routeIs('trash.index') ? 'active' : '' }} flex items-center text-gray-700 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Trash</span>
            </a>
        </nav>

        <div class="border-t border-gray-300 pt-4 mt-4">
            <a href="{{ route('settings.index') }}"
                class="sidebar-item {{ request()->routeIs('settings.index') ? 'active' : '' }} flex items-center text-gray-700 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Settings</span>
            </a>
            <a href="{{ route('help') }}"
                class="sidebar-item {{ request()->routeIs('help') ? 'active' : '' }} flex items-center text-gray-700 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Help & Feedback</span>
            </a>
            {{-- <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit"
                    class="sidebar-item w-full flex items-center text-gray-700 hover:text-gray-900 text-left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form> --}}
        </div>
    </div>
</aside>

@pushOnce('scripts') {{-- Gunakan @pushOnce agar skrip hanya di-include sekali --}}
<script>
document.addEventListener('labelsUpdated', async function (event) {
    const sidebarLabelList = document.getElementById('sidebarLabelList');
    if (!sidebarLabelList) {
        console.warn('[Sidebar] Element "sidebarLabelList" not found for dynamic update.');
        return;
    }

    console.log('[Sidebar] Received labelsUpdated event, refreshing sidebar labels list...', event.detail);

    try {
        // Panggil endpoint API yang mengembalikan HTML partial untuk daftar label di sidebar
        // Pastikan route 'api.sidebar.labels' sudah didefinisikan
        const response = await fetch("{{ Auth::check() ? route('api.sidebar.labels') : '#' }}", { // Cek Auth::check()
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken // Jika endpoint API Anda memerlukannya (biasanya GET tidak)
            }
        });

        if (response.ok) {
            const data = await response.json();
            if (data.html !== undefined) {
                sidebarLabelList.innerHTML = data.html; // Ganti konten dengan HTML baru
                console.log('[Sidebar] Labels list updated via AJAX.');
            } else {
                console.error('[Sidebar] Invalid response from API for sidebar labels. Missing "html" key.', data);
            }
        } else {
            console.error('[Sidebar] Failed to fetch sidebar labels. Status:', response.status);
            // Anda bisa mencoba mengambil data error dari response jika ada
            // const errorData = await response.json().catch(() => ({}));
            // console.error('[Sidebar] Error data:', errorData);
        }
    } catch (error) {
        console.error('[Sidebar] Error refreshing sidebar labels via AJAX:', error);
    }
});
</script>
@endPushOnce
