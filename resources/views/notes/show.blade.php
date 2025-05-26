@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            {{-- <h1 class="text-2xl font-google-sans font-medium text-gray-800">{{ $note->title ?: 'Note Details' }}</h1> --}}
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('notes.edit', $note->id) }}" class="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-colors" title="Edit Note">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
            </a>
            <a href="{{ route('home') }}" class="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-colors" title="Back to Notes">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
        </div>
    </div>

    <article class="bg-note-{{ $note->color }} p-6 md:p-8 rounded-lg shadow-lg">
        @if($note->title)
        <h2 class="text-2xl font-google-sans font-medium mb-4 text-gray-900">{{ $note->title }}</h2>
        @endif
        @if($note->content)
        <div class="prose prose-sm sm:prose lg:prose-lg xl:prose-xl max-w-none text-gray-800 mb-6 whitespace-pre-line break-words">
            {!! nl2br(e($note->content)) !!}
        </div>
        @endif

        @if($note->labels->isNotEmpty())
        <div class="mb-4">
            <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Labels:</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($note->labels as $labelObj)
                <span class="label-chip">{{ $labelObj->name }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="text-xs text-gray-500 flex flex-col sm:flex-row justify-between items-start sm:items-center border-t border-gray-300/50 pt-4 mt-6">
            <div class="mb-2 sm:mb-0">
                <p>Created: {{ $note->created_at->format('M d, Y g:i A') }}</p>
                <p>Last Updated: {{ $note->updated_at->format('M d, Y g:i A') }}</p>
                @if($note->is_archived) <span class="text-yellow-600 font-semibold"> (Archived)</span> @endif
            </div>

            <div class="flex space-x-2">
                <form action="{{ route('notes.archive-toggle', $note->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="p-2 rounded-full hover:bg-gray-400/20 text-gray-600" title="{{ $note->is_archived ? 'Unarchive' : 'Archive' }}">
                        @if($note->is_archived)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4m-4-4l4 4m0 0l4-4m-4 4V4" /></svg>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                        @endif
                    </button>
                </form>
                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to move this note to trash?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-full hover:bg-gray-400/20 text-red-500" title="Move to Trash">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </article>

    <!-- Collaboration Section (Placeholder) -->
    {{-- <div class="mt-8"> ... </div> --}}
</div>
@endsection

@push('styles')
@if (class_exists(\Tailwind पताCss\Typography\Typography::class)) Cek jika plugin typography ada
    {{-- Tidak perlu style tambahan jika plugin @tailwindcss/typography digunakan --}}
@else
    <style> /* Basic prose styling jika plugin typography tidak ada */
        .prose { font-size: 1rem; line-height: 1.75; }
        .prose p { margin-top: 1.25em; margin-bottom: 1.25em; }
        .prose ul, .prose ol { margin-top: 1.25em; margin-bottom: 1.25em; padding-left: 1.75em; }
        .prose li { margin-top: 0.5em; margin-bottom: 0.5em; }
        .prose h1, .prose h2, .prose h3, .prose h4 { margin-top: 1.5em; margin-bottom: 0.75em; font-weight: 600; line-height: 1.25; }
        .prose h2 { font-size: 1.5em; } .prose h3 { font-size: 1.25em; }
        .prose a { color: #2563eb; text-decoration: underline; }
        .prose pre { background-color: #f3f4f6; padding: 1em; border-radius: 0.375rem; overflow-x: auto; }
        .prose code { font-family: monospace; }
    </style>
@endif
@endpush