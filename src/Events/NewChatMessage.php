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

    public string $id = "1";
    public int $userId = "1";
    public string $content;
    public int $sender;
    public string $message;

    public function __construct( Message $message )
    {
        $this->userId = 2;
        $this->content = "Test message";
        $this->message = "Another test message";
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("chat"),
            new PrivateChannel('App.User.' . $this->userId),
        ];
    }
}
