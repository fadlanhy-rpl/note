@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3 p-6 py-8 bg-gray-50 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200">
                    <div class="relative mb-4">
                        <img class="h-32 w-32 rounded-full object-cover shadow-md ring-2 ring-offset-2 ring-indigo-300"
                             src="{{ $user->profile_image_path ? Storage::url($user->profile_image_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff&size=128&font-size=0.33&bold=true&rounded=true' }}"
                             alt="{{ $user->name }}">
                        {{-- Badge Role --}}
                        @if(method_exists($user, 'isAdmin') && $user->isAdmin()) {{-- Cek jika method isAdmin ada --}}
                        <span class="absolute bottom-1 right-1 block h-7 w-7 rounded-full ring-2 ring-white
                                    bg-red-500 flex items-center justify-center text-white text-xs font-bold"
                              title="Administrator">A</span>
                        @else
                        <span class="absolute bottom-1 right-1 block h-7 w-7 rounded-full ring-2 ring-white
                                    bg-indigo-500 flex items-center justify-center text-white text-xs font-bold"
                              title="User">U</span>
                        @endif
                    </div>
                    <h2 class="text-xl font-google-sans font-semibold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <p class="text-xs text-gray-400 mt-1">Joined: {{ $user->created_at->format('M d, Y') }}</p>
                </div>

                <div class="md:w-2/3 p-6 py-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-google-sans font-medium text-gray-700">Profile Information</h3>
                        <a href="{{ route('profile.edit') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium flex items-center py-1 px-3 rounded-md hover:bg-indigo-50 transition-colors">
                            <i class="ri-pencil-line mr-1.5"></i>Edit Profile
                        </a>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $user->email }}</dd>
                        </div>
                        {{-- <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ method_exists($user, 'isAdmin') && $user->isAdmin() ? 'Administrator' : 'User' }}</dd>
                        </div> --}}
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('F d, Y') : 'Not set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Age</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $user->age ? $user->age . ' years old' : 'Not set' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection