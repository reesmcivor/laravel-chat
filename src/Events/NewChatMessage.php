<?php

namespace ReesMcIvor\Chat\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use ReesMcIvor\Chat\Models\Message;
use ReesMcIvor\Chat\Models\Conversation;

class NewChatMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Conversation $conversation;

    public array $user = [];
    public array $message;

    public function __construct( Message $message )
    {
        $this->conversation = $message->conversation;
        $this->message = $message::with('user')->get()->first()->toArray();
    }
    
}
