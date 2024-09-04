<?php

namespace ReesMcIvor\Chat\Models;

use App\Models\Role;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use ReesMcIvor\Chat\Actions\NewMessage;
use ReesMcIvor\Chat\Database\Factories\MessageFactory;
use App\Models\User;
use ReesMcIvor\Chat\Events\NewChatMessage;
use ReesMcIvor\Chat\Http\Resources\MessageResource;
use ReesMcIvor\Chat\Notifications\NewMessageNotification;
use Wildside\Userstamps\Userstamps;
use ReesMcIvor\Chat\Notifications\NewConversationNotification;

class Message extends Model implements ShouldBroadcast
{
    use HasFactory;
    use Userstamps;
    use BroadcastsEvents;

    public $afterCommit = true;

    protected $guarded = ['id'];
    protected $table = "messages";

    protected static function boot()
    {
        parent::boot();

        static::created(function ($message) {
            $message->conversation->touchQuietly();
            (new NewMessage)->handle($message);
        });
    }

    public function broadcastOn() : array
    {
        $broadcastOn = [];
        if($this?->conversation?->participants) {
            $broadcastOn = $this?->conversation?->participants->map(function ($participant) {
                return new PrivateChannel('App.Models.User.' . $participant->id);
            })->toArray();
        }

        return $broadcastOn;
    }

    public function broadcastQueue() : string
    {
        return 'chat';
    }


    public function broadcastWith() : array {
        return [
            'model' => MessageResource::make($this)->resolve()
        ];
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
