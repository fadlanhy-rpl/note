@extends('layouts.app')

@section('title', $pageTitle ?? 'Help & Feedback')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-google-sans font-medium text-gray-800">{{ $pageTitle ?? 'Help & Feedback' }}</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 space-y-8">
        {{-- Bagian FAQ (Frequently Asked Questions) --}}
        <section>
            <h2 class="text-lg font-google-sans font-medium text-gray-700 mb-4 border-b pb-2">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <details class="group bg-gray-50 p-4 rounded-lg cursor-pointer">
                    <summary class="flex justify-between items-center font-medium text-gray-900 group-hover:text-indigo-600">
                        How do I create a new note?
                        <svg class="w-5 h-5 transform transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <p class="mt-3 text-sm text-gray-600">
                        You can create a new note by clicking the "Take a note..." input field on the main notes page, or by clicking the "+" button in the top navigation bar and selecting "Create New Note".
                    </p>
                </details>
                <details class="group bg-gray-50 p-4 rounded-lg cursor-pointer">
                    <summary class="flex justify-between items-center font-medium text-gray-900 group-hover:text-indigo-600">
                        How do I use labels?
                        <svg class="w-5 h-5 transform transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <p class="mt-3 text-sm text-gray-600">
                        Labels help you organize your notes. You can create new labels from the "Edit labels" section in the sidebar. When creating or editing a note, you can assign one or more labels to it. Clicking a label in the sidebar will filter your notes to show only those with that label.
                    </p>
                </details>
                {{-- Tambahkan FAQ lainnya --}}
            </div>
        </section>

        {{-- Bagian Contact Support / Send Feedback --}}
        <section>
            <h2 class="text-lg font-google-sans font-medium text-gray-700 mb-4 border-b pb-2">Contact Support or Send Feedback</h2>
            <p class="text-sm text-gray-600 mb-4">
                If you need further assistance or have feedback, please let us know.
            </p>
            <form action="#" method="POST"> {{-- Ganti # dengan endpoint feedback Anda --}}
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="feedback_subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" name="feedback_subject" id="feedback_subject" required class="form-input mt-1">
                    </div>
                    <div>
                        <label for="feedback_message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="feedback_message" id="feedback_message" rows="4" required class="form-input mt-1"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            Send Feedback
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection