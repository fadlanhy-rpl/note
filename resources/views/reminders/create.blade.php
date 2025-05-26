@extends('layouts.app')

@section('title', $pageTitle ?? 'Set Reminder')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 md:p-8 rounded-lg shadow-xl">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-xl font-google-sans font-medium text-gray-800">{{ $pageTitle }}</h1>
        <a href="{{ $note ? route('notes.show', $note) : route('reminders.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors" title="Cancel">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </a>
    </div>

    <form action="{{ route('reminders.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="note_id" class="block text-sm font-medium text-gray-700 mb-1">Note to Remind</label>
            @if($note)
                <input type="hidden" name="note_id" value="{{ $note->id }}">
                <p class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700">{{ $note->title ?: 'Untitled Note' }}</p>
            @else
                <select id="note_id" name="note_id" required class="mt-1 block w-full p-2 border {{ $errors->has('note_id') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">Select a note...</option>
                    @foreach($notes as $n)
                    <option value="{{ $n->id }}" {{ old('note_id') == $n->id ? 'selected' : '' }}>{{ $n->title ?: 'Untitled Note (ID: '.$n->id.')' }}</option>
                    @endforeach
                </select>
            @endif
            @error('note_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="remind_at" class="block text-sm font-medium text-gray-700 mb-1">Remind Date & Time</label>
            <input type="datetime-local" id="remind_at" name="remind_at" value="{{ old('remind_at', now()->addHour()->format('Y-m-d\TH:i')) }}" required
                   class="mt-1 block w-full p-2 border {{ $errors->has('remind_at') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            @error('remind_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
            <textarea id="description" name="description" rows="3"
                      class="mt-1 block w-full p-2 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                      placeholder="E.g., Follow up on email">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{route('home')}}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Cancel</a>
            <button type="submit" class="px-6 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Set Reminder</button>
        </div>
    </form>
</div>
@endsection