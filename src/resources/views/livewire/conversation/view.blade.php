<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" href="{{ route('tenant.conversations.index') }}">Back to Conversations</a>

        @if($conversation)
            <div id="messages" style="height: calc(100vh - 400px);" class=" overflow-scroll mt-10">
                @if($messages = $conversation->messages)
                    @foreach($conversation->messages->reverse() as $message)
                        <div class="bg-white p-6 flex justify-between mb-2 ">
                            <div class="w-full">
                                <textarea name="content" wire:model.defer="messages.{{ $message->id }}.content"  required class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-100">{{ $message->content }}</textarea>
                                <div class="text-xs pt-5">By {{ $message->user->name }} | {{ $message->updated_at->diffForHumans() }}</div>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <div>
                                    <button variant="secondary" class="rounded-full border-2 flex p-3 relative" wire:click="updateMessage({{ $message->id }})">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                                <div>
                                    <button variant="secondary" type="submit" class="rounded-full border-2 flex p-3 relative"
                                        wire:click="delete({{ $message->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="bg-white p-6 flex justify-between mb-2 ">
                <div class="w-full">
                    <textarea name="content" wire:model.defer="message" required class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-100 mb-5 "></textarea>
                    <a wire:click="save" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150" href="#">
                        Add Message
                    </a>
                </div>
            </div>

        @endif
    </div>
</div>

@push('scripts')
    <script>
        window.Livewire.on('saved', function() {
            let div = document.getElementById('messages');
            div.scrollTop = div.scrollHeight;
        });
    </script>
@endpush