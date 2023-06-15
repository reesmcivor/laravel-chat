@extends('layouts.tenant', ['title' => 'Conversations'])

@section('content')

    <div class="">
        <div class="max-w-7xl mx-auto">
            <x-button class="px-5" as="a" href="{{ route('tenant.conversations.create') }}">New conversation</x-button>

                @foreach($conversations as $conversation)
                    <div class="block mt-8 rounded-lg shadow overflow-hidden">
                        <div class="bg-white p-6">
                            <a href="{{ route('tenant.conversations.show', $conversation->id) }}">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    {{ $conversation->subject ?? "Subject NA" }}
                                </h3>
                            </a>

                            <div class="actions flex space-x-2">
                                <div>
                                    <form method="POST" action="{{ route('tenant.conversations.destroy', $conversation->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-button variant="secondary" type="submit">Delete</x-button>
                                    </form>
                                </div>
                                <div>
                                    <a href="{{ route('tenant.conversations.show', $conversation->id) }}">
                                        <x-button type="submit">View</x-button>
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('tenant.conversations.edit', $conversation->id) }}">
                                        <x-button type="submit">Edit</x-button>
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('tenant.conversations.join', $conversation->id) }}">
                                        <x-button type="submit">Join</x-button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

                {{ $conversations->links() }}
            </div>
        </div>
    </div>

@endsection
