<?php

namespace ReesMcIvor\Chat\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use ReesMcIvor\Chat\Http\Resources\MessageResource;
use ReesMcIvor\Chat\Models\Message;
use ReesMcIvor\Chat\Models\Conversation;

class NewChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Conversation $conversation;

    public array $user = [];
    public array $message;

    public function __construct( Message $message )
    {
        $this->conversation = $message->conversation;
        $this->message = MessageResource::make($message)->resolve();
    }

    public function broadcastOn(): array
    {
        return $this->conversation->broadcastOn();
    }
}
