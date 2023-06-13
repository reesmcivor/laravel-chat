<?php

namespace ReesMcIvor\Chat\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use ReesMcIvor\Chat\Models\Message;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $message;

    public function __construct( Message $message )
    {
        $this->message['id'] = $message->id;
        $this->message = [...$this->message, ...$message->toArray()];

    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.User.1'),
        ];
    }
}
