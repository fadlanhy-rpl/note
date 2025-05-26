@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <h2 class="auth-title text-2xl font-semibold text-center text-gray-700 mb-6">
        Create your MyNotes account
    </h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="form-input w-full @error('name') border-red-500 @enderror"
                   placeholder="John Doe">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                   class="form-input w-full @error('email') border-red-500 @enderror"
                   placeholder="you@example.com">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="form-input w-full @error('password') border-red-500 @enderror"
                   placeholder="Create a strong password">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="form-input w-full"
                   placeholder="Confirm your password">
        </div>

        <div>
            <button type="submit" class="btn-primary w-full flex justify-center">
                Register
            </button>
        </div>
    </form>
@endsection