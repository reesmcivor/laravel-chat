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

    public string $id;
    public int $userId;
    public string $content;
    public int $sender;
    public Message $message;

    public function __construct( Message $message )
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("chat"),
            new PrivateChannel('App.User.' . $this->userId),
        ];
    }
}
