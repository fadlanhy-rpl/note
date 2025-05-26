<div class="modal" id="userProfileModal">
    <div class="modal-content" style="max-width: 400px;">
        <button class="close" id="closeProfileModal" aria-label="Close profile modal">×</button>
        <div class="flex items-center mb-6">
            <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center mr-4 overflow-hidden">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($currentUser->name) }}&background=random&color=fff&size=64" alt="{{ $currentUser->name }}" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-xl font-google-sans font-medium text-gray-800">{{ $currentUser->name }}</h2>
                <p class="text-sm text-gray-500">{{ $currentUser->email }}</p>
            </div>
        </div>
        <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Account Statistics</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-gray-100 p-3 rounded-lg text-center">
                    <p class="text-2xl font-google-sans font-bold text-gray-700">{{ $notesCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Notes</p>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg text-center">
                    <p class="text-2xl font-google-sans font-bold text-gray-700">{{ $labelsCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Labels</p>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg text-center">
                    <p class="text-2xl font-google-sans font-bold text-gray-700">{{ $sharedCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Shared</p>
                </div>
            </div>
        </div>
        {{-- <div class="mb-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Storage</h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 35%"></div>
            </div>
            <p class="text-xs text-gray-500">35% of 15GB used</p>
        </div> --}}
        <div class="flex justify-end space-x-2 mt-8">
            <a href="{{ route('settings.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">Settings</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">Logout</button>
            </form>
            {{-- <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Manage Account</a> --}}
        </div>
    </div>
</div>