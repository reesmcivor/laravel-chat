<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conversation') }}
        </h2>
    </x-slot>

    <div class="" x-data="{
        submitForm(formId) {
            document.getElementById(formId).submit();
        }
    }">
        <div class="max-w-7xl mx-auto">

            <form action="{{ route('tenant.messages.store', $conversation) }}" method="POST">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <label for="title">Conversation Title:</label>
                <input type="text" id="content" name="content" required class="form-input rounded-md shadow-sm mt-1 block w-full">
                <button type="submit">Create Message</button>
            </form>

            @if($conversation->participants)
                <div class="mb-5 border p-2 flex">
                    @foreach($conversation->participants as $participant)
                        <div class="mr-5">
                            <div class="text-xs">Participants: {{ $participant->name }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($conversation->messages)
                @foreach($conversation->messages as $message)
                    @php $uniqueFormId = 'message-form-' . $message->id; @endphp
                    <div class="bg-white p-6 flex justify-between mb-2 ">
                        <div class="w-full">
                            <form id="{{ $uniqueFormId }}" method="post" action="{{ route('tenant.messages.update', $message->id) }}">
                                @csrf
                                @method('PUT')
                                <textarea name="content" required class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-100">{{ $message->content }}</textarea>
                            </form>
                            <div class="text-xs pt-5">By {{ $message->user->name }} | {{ $message->updated_at->diffForHumans() }}</div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <div>
                                <button variant="secondary" class="rounded-full border-2 flex p-3 relative" @click="submitForm('{{ $uniqueFormId }}')">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                            <div>
                                <form method="post" action="{{ route('tenant.messages.destroy', $message->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button variant="secondary" type="submit" onclick="return confirm('Are you sure?')" class="rounded-full border-2 flex p-3 relative">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

    <script>
        import Echo from 'laravel-echo';

        window.Pusher = require('pusher-js');

        const options = {
            broadcaster: 'pusher',
            key: process.env.MIX_PUSHER_APP_KEY,
            wsHost: process.env.MIX_PUSHER_HOST,
            wsPort: process.env.MIX_PUSHER_PORT,
            wssPort: process.env.MIX_PUSHER_PORT,
            cluster: '',
            forceTLS: true,
            encrypted: true,
            disableStats: false,
            enabledTransports: ['wss', 'wss'],
        }
        console.log(options);

        let laravelEcho = new Echo(options);

        Pusher.logToConsole = true;

        console.log(laravelEcho);
        laravelEcho.private(`App.Models.User.1`)
            .listen('.MessageCreated', (e) => {
                console.log(e);
            });


        alert('got here');
    </script>
</x-app-layout>