@extends('layouts.tenant', ['title' => 'Conversations'])

@section('content')

    <div class="">
        <div class="max-w-7xl mx-auto">
            <x-button class="px-5" as="a" href="{{ route('tenant.conversations.create') }}">New conversation</x-button>

                @foreach($conversations as $conversation)
                    <div class="block mt-8 rounded-lg shadow overflow-hidden">
                        <div class="bg-white p-6 flex items-center justify-between">
                            <a href="{{ route('tenant.conversations.show', $conversation->id) }}">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    {{ $conversation->subject ?? "Subject NA" }}
                                </h3>
                                <span class="text-xs">{{ $conversation->updated_at->format('d/m/Y H:i') }}</span>
                                @if($conversation->lastMessage)
                                    <p class="mt-3 text-base text-gray-500">
                                        {{ $conversation->lastMessage->user->name }} - {{ $conversation->lastMessage->created_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </a>

                            <div class="">
                                <span class="text-bold">{{ __('Status: ') }}</span>{{ $conversation->status }}
                                
                            </div>

                            <div class="actions flex space-x-2">
                                <div>
                                    <form method="POST" action="{{ route('tenant.conversations.destroy', $conversation->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="secondary" type="submit">Delete</x-button>
                                    </form>
                                </div>

                                <div>
                                    <a href="{{ route('tenant.conversations.edit', $conversation->id) }}">
                                        <x-button type="submit">Edit</x-button>
                                    </a>
                                </div>
                                @if($conversation?->paricipants?->contains(auth()->user()))
                                    <div>
                                        <a href="{{ route('tenant.conversations.join', $conversation->id) }}">
                                            <x-button type="submit">Join</x-button>
                                        </a>
                                    </div>
                                @else
                                    <div>
                                        <a href="{{ route('tenant.conversations.show', $conversation->id) }}">
                                            <x-button type="submit">View</x-button>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @endforeach

                {{ $conversations->links() }}
            </div>
        </div>
    </div>

@endsection
