@extends('layouts.tenant', ['title' => 'Conversations'])

@section('content')

    <div class="">
        <div class="max-w-7xl mx-auto">

            @if($conversation->messages)
                @foreach($conversation->messages as $message)
                    <div class="mb-5 border p-2">
                        <div>{{ $message->content }}</div>
                        <div class="text-xs pt-5">By {{ $message->user->name }}</div>
                    </div>

                @endforeach
            @endif

            <form action="{{ route('tenant.messages.store', $conversation) }}" method="POST">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <label for="title">Conversation Title:</label>
                <input type="text" id="content" name="content" required>
                <button type="submit">Create Message</button>
            </form>

        </div>
    </div>

@endsection
