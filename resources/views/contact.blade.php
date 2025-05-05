<!-- resources/views/contact/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-24 bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Contact Admin</h2>

    @if(session('success'))
        <div class="mb-4 text-green-600 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}">
        @csrf

        <div class="mb-4">
            <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
            <textarea name="message" id="message" rows="6"
                      class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:border-blue-400"
                      required>{{ old('message') }}</textarea>
            @error('message')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Send
        </button>
    </form>

    @if(auth()->user()->contacts->count())
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-2">Your Messages</h3>
        <ul class="space-y-4">
            @foreach(auth()->user()->contacts as $c)
                <li class="border rounded p-4">
                    <p class="text-gray-800 mb-1"><strong>Message:</strong> {{ $c->message }}</p>
                    <p class="text-green-700"><strong>Reply:</strong> {{ $c->admin_reply ?? 'No reply yet' }}</p>
                </li>
            @endforeach
        </ul>
    </div>
@endif

</div>
@endsection
