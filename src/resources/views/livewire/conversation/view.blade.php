<div>
    Last Refreshed {{ now()->diffForHumans() }}
    <div class="max-w-7xl mx-auto">
        @if($conversation)

            <div class="bg-white p-6 flex justify-between mb-2 ">
                <div class="w-full">
                    <textarea name="content" wire:model.defer="message" required class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-100"></textarea>
                </div>
                <div class="flex space-x-2 ml-4">
                    <div>
                        <button variant="secondary" class="rounded-full border-2 flex p-3 relative" wire:click="save">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>
                </div>
            </div>

            @if($messages = $conversation->messages)
                @foreach($conversation->messages as $message)
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
                                <button variant="secondary" type="submit" onclick="return confirm('Are you sure?')" class="rounded-full border-2 flex p-3 relative"
                                    wire:click="delete({{ $message->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        @endif
    </div>
</div>