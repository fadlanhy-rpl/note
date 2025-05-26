<header class="h-16 border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 bg-white z-20">
    <div class="flex items-center flex-1">
        <button id="sidebarToggle"
            class="mr-4 p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <form action="{{ route('notes.search') }}" method="GET" class="w-full max-w-md">
            <div class="search-container flex items-center px-4 py-2 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="search" name="query" placeholder="Search notes" id="searchInput"
                    class="bg-transparent border-none outline-none ml-2 text-sm w-full text-gray-700 placeholder-gray-500"
                    value="{{ request('query') }}">
            </div>
        </form>
    </div>
    <div class="flex items-center space-x-2">
        {{-- <button class="p-2 rounded-full hover:bg-gray-100" title="Refresh">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
        </button> --}}
        <a href="{{ route('notes.create') }}" class="p-2 rounded-full hover:bg-gray-100" title="Create New Note">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </a>
        {{-- <button class="p-2 rounded-full hover:bg-gray-100" title="View options">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
        </button> --}}
        {{-- Dropdown Profil Pengguna --}}
        @auth
            <div class="relative" x-data="{ dropdownOpen: false }">
                <button @click="dropdownOpen = !dropdownOpen"
                    class="flex items-center space-x-2 focus:outline-none p-1 rounded-full hover:bg-gray-100">
                    <img src="{{ Auth::user()->profile_image_path ? Storage::url(Auth::user()->profile_image_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff&size=32&rounded=true' }}"
                        alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover">
                    <span class="hidden md:inline text-sm text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                    <svg class="hidden md:inline w-4 h-4 text-gray-500 transition-transform duration-200"
                        :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-xl py-1 z-50 border border-gray-200"
                    style="display: none;"> {{-- style="display: none;" untuk FOUC (Flash of Unstyled Content) --}}

                    <div class="px-4 py-3 border-b flex border-gray-200">
                        <img src="{{ Auth::user()->profile_image_path ? Storage::url(Auth::user()->profile_image_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff&size=32&rounded=true' }}"
                            alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover">
                        <div class="translate-x-3">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    {{-- @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="ri-shield-user-line mr-2"></i>Admin Panel
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="ri-dashboard-line mr-2"></i>My Dashboard
                        </a>
                    @endif --}}

                    <a href="{{ route('profile.show') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="ri-user-line mr-2"></i>My Profile
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="ri-settings-3-line mr-2"></i>Account Settings
                    </a>
                    <hr class="my-1 border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="ri-logout-box-r-line mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</header>
