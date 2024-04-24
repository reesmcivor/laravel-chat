<?php

namespace ReesMcIvor\Chat\Models;

use App\Models\Role;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
            $message->conversation->touch();

            if($message->conversation->refresh()->messages()->count() == 1) {
                User::whereIn('email',['hello@logicrises.co.uk','oli@optimal-movement.co.uk'])
                    ->get()->each(function ($admin) use ($message) {
                    $admin->notify(new NewConversationNotification($message));
                });
            }

            if($message?->user->is_premium) {
                $admins = $message->conversation->participants();
                $admins->where('role_id', [Role::STAFF_ROLE_ID])->each(function($staff) use ($message) {
                    $staff->notify(new NewMessageNotification($message));
                });
                User::whereIn('email', ['oli@optimal-movement.co.uk'])->get()->each(function ($admin) use ($message) {
                    $admin->notify(new NewMessageNotification($message));
                });

            }
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
