<?php

namespace ReesMcIvor\Chat\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use ReesMcIvor\Chat\Database\Factories\MessageFactory;
use App\Models\User;
use ReesMcIvor\Chat\Events\NewChatMessage;
use Wildside\Userstamps\Userstamps;

class Message extends Model
{
    use HasFactory;
    use Userstamps;
    use BroadcastsEvents;

    protected $guarded = ['id'];
    protected $table = "messages";

    protected static function boot()
    {
        parent::boot();

        static::created(function ($message) {
            $message->conversation->touch();
            event(new NewChatMessage($message));
        });
        static::deleted(function ($message) {
            event(new NewChatMessage($message));
        });
        static::updated(function ($message) {
            event(new NewChatMessage($message));
        });
    }

    public function broadcastOn(string $event) : array
    {
        return $this->conversation->participants->map(function($participant) {
            return new PrivateChannel('chat.' . $participant->id);
        })->toArray();
    }

    protected static function newFactory()
    {
        return MessageFactory::new();
    }

    public function isAutoCloseWarning()
    {
        return $this->content == 'This conversation will be closed in 10 minutes due to inactivity.';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

}
