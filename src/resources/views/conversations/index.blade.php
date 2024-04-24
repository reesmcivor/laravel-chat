<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conversations') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" href="{{ route('tenant.conversations.create') }}">New conversation</a>

                @foreach($conversations as $conversation)
                    <div class="block mt-8 rounded-lg shadow overflow-hidden">
                        <div class="bg-white p-6 flex items-center justify-between">
                            <a href="{{ route('tenant.conversations.view', $conversation->id) }}">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Conversation with {{ $conversation->creator->name }}
                                </h3>
                                <span class="text-xs">{{ $conversation->updated_at->setTimezone('Europe/London')->format('d/m/Y H:i') }}</span>
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

                                @php($showJoin = true)
                                @foreach($conversation->participants as $participant)
                                    <div>
                                        @if($participant->id == auth()->user()->id)
                                            @php($showJoin = false)
                                        @endif
                                        <div class="rounded-full border-2 flex relative -ml-5 w-12 h-12 overflow-hidden justify-center items-center">
                                            <?php if($photo = $participant->getPhoto()) : ?>
                                                <img class="cover" src="{{ $photo }}" alt="{{ $participant->name }}" />
                                            <?php else : ?>
                                                <i class="fas fa-user"></i>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-xs">
                                            <?php
                                            $words = explode(" ", $participant->name);
                                            $initials = null;
                                            foreach ($words as $w) {
                                                 $initials .= $w[0];
                                            }
                                            echo $initials;
                                            ?>
                                        </span>
                                    </div>
                                @endforeach

                            </div>

                            <div class="actions flex space-x-2">


                                @if($showJoin)
                                    <div>
                                        <a href="{{ route('tenant.conversations.join', $conversation->id) }}" class="rounded-full border-2 flex p-3 relative" title="Join Conversation">
                                            <i class="fas fa-user"></i>
                                        </a>
                                    </div>
                                @else
                                    <div>
                                        <a href="{{ route('tenant.conversations.view', $conversation->id) }}" class="rounded-full border-2 flex p-3 relative" title="View Conversation">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                @endif

                                    <div>
                                        <form method="POST" action="{{ route('tenant.conversations.destroy', $conversation->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button variant="secondary" type="submit" onclick="return confirm('Are you sure you want to remove this conversation?')" class="rounded-full border-2 flex p-3 relative" title="Delete Conversation">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div>
                                        <form method="POST" action="{{ route('tenant.conversations.close', $conversation->id) }}">
                                            @csrf
                                            @method('POST')
                                            <button variant="secondary" type="submit" onclick="return confirm('Are you sure you want to close this conversation?')" class="rounded-full border-2 flex p-3 relative" title="Close Conversation">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                
                            </div>
                        </div>
                    </div>

                @endforeach
</x-app-layout>