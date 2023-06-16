<?php

namespace ReesMcIvor\Chat\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ReesMcIvor\Chat\Models\Conversation;

class CloseConversation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversationId = null;

    public function __construct( Conversation $conversation )
    {
        $this->conversationId = $conversation->id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(sprintf('App.User.1', $this->conversationId)),
        ];
    }
}
