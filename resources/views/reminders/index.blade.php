@extends('layouts.app')

@section('title', $pageTitle ?? 'Reminders')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-google-sans font-medium text-gray-800">{{ $pageTitle }}</h1>
    <a href="{{ route('reminders.create') }}" class="btn-primary p-2 rounded-lg text-sm">Set New Reminder</a>
</div>

{{-- Filter Tabs --}}
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <a href="{{ route('reminders.index', ['filter' => 'upcoming']) }}"
           class="{{ $filter == 'upcoming' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
            Upcoming
        </a>
        <a href="{{ route('reminders.index', ['filter' => 'past']) }}"
           class="{{ $filter == 'past' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
            Past & Due
        </a>
        <a href="{{ route('reminders.index', ['filter' => 'completed']) }}"
           class="{{ $filter == 'completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
            Completed
        </a>
    </nav>
</div>


@if($reminders->isEmpty())
    <div class="text-center py-10 bg-gray-50 rounded-lg">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No reminders found for this filter.</h3>
        <p class="mt-1 text-sm text-gray-500">
            @if($filter == 'upcoming')
                Set a new reminder to stay organized!
            @else
                Check other filters or set new reminders.
            @endif
        </p>
    </div>
@else
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($reminders as $reminder)
            <li class="p-4 hover:bg-gray-50 transition-colors duration-150">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center">
                            @if(!$reminder->is_completed && $reminder->remind_at->isPast())
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-red-500 mr-2 animate-pulse" title="Past Due"></span>
                            @elseif(!$reminder->is_completed)
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-500 mr-2" title="Upcoming"></span>
                            @else
                            <span class="inline-block h-2.5 w-2.5 rounded-full bg-green-500 mr-2" title="Completed"></span>
                            @endif
                            <a href="{{ route('notes.show', $reminder->note_id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 truncate" title="View Note: {{ $reminder->note->title ?? 'Note' }}">
                                {{ $reminder->note->title ?: 'Note ID: '.$reminder->note_id }}
                            </a>
                        </div>
                        @if($reminder->description)
                        <p class="text-sm text-gray-500 mt-1 truncate">{{ $reminder->description }}</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="font-medium">Remind at:</span> {{ $reminder->remind_at->format('M d, Y H:i A') }}
                            ({{ $reminder->remind_at->diffForHumans() }})
                        </p>
                        @if($reminder->is_completed && $reminder->completed_at)
                            <p class="text-sm text-green-600 mt-1">Completed: {{ $reminder->completed_at->format('M d, Y H:i A') }}</p>
                        @endif
                    </div>
                    <div class="ml-4 flex-shrink-0 flex space-x-2">
                        @if(!$reminder->is_completed)
                        <form action="{{ route('reminders.complete', $reminder) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-green-300 shadow-sm text-xs font-medium rounded text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" title="Mark as complete">
                                <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Done
                            </button>
                        </form>
                        @else
                         <form action="{{ route('reminders.uncomplete', $reminder) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-yellow-400 shadow-sm text-xs font-medium rounded text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" title="Mark as not completed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Undo
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('reminders.edit', $reminder) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" title="Edit reminder">
                            Edit
                        </a>
                        <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reminder?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" title="Delete reminder">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="mt-6">
        {{ $reminders->appends(request()->query())->links() }}
    </div>
@endif
@endsection