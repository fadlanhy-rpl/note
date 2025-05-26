@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h2 class="auth-title text-2xl font-semibold text-center text-gray-700 mb-6">
        Sign in to MyNotes
    </h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   class="form-input w-full @error('email') border-red-500 @enderror"
                   placeholder="you@example.com">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <div class="flex justify-between items-center">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                {{-- @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-500">
                        Forgot password?
                    </a>
                @endif --}}
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="form-input w-full @error('password') border-red-500 @enderror"
                   placeholder="Enter your password">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div>
            <button type="submit" class="btn-primary w-full flex justify-center">
                Sign In
            </button>
        </div>
    </form>
@endsection