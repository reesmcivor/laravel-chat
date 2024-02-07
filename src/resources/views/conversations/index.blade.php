<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar for ' . isset($therapist) ?? ' All ') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto">
            <a class="px-5" as="a" href="{{ route('tenant.conversations.create') }}">New conversation</a>

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

                            <div>
                                <span class="text-bold">{{ __('Status: ') }}</span>{{ $conversation->status }}
                            </div>

                            <div class="flex">

                                @foreach($conversation->participants as $participant)
                                    <div class="rounded-full border-2 flex p-3 relative -ml-5 w-12 h-12">
                                        <img src="{{ $participant->getPhoto() }}"
                                            style="width: 30px; height: 30px; " />
                                    </div>
                                @endforeach

                            </div>

                            <div class="actions flex space-x-2">
                                <div>
                                    <form method="POST" action="{{ route('tenant.conversations.destroy', $conversation->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button variant="secondary" type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>

                                @if($conversation?->paricipants?->contains(auth()->user()))
                                    <div>
                                        <a href="{{ route('tenant.conversations.join', $conversation->id) }}">
                                            Join
                                        </a>
                                    </div>
                                @else
                                    <div>
                                        <a href="{{ route('tenant.conversations.show', $conversation->id) }}">
                                            View
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @endforeach
</x-app-layout>