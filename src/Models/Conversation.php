<?php

namespace ReesMcIvor\Chat\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use ReesMcIvor\Chat\Http\Resources\ConversationResource;
use ReesMcIvor\Chat\Http\Resources\MessageResource;
use Wildside\Userstamps\Userstamps;
use ReesMcIvor\Chat\Database\Factories\ConversationFactory;
use App\Models\User;
use ReesMcIvor\Chat\Events\CloseConversation;

class Conversation extends Model implements ShouldBroadcast
{
    use HasFactory;
    use Userstamps;
    use SoftDeletes;

    use BroadcastsEvents;

    public $afterCommit = true;

    protected $guarded = ['id'];
    protected $table = "conversations";

    protected $orderBy = 'updated_at';
    protected $orderDirection = 'DESC';

    protected static function newFactory()
    {
        return ConversationFactory::new();
    }

    public function close()
    {
        $this->update(['status' => 'closed']);

        $this->messages()->create([
            'user_id' => 1,
            'is_system_' => true,
            'content' => 'This conversation has been closed.'
        ]);

        event(new CloseConversation($this));
    }

    public function getParticipantNames()
    {
        return $this->participants()->pluck('name')->implode(', ');
    }

    public function getAutoCloseMins() : int
    {
        return config('chat.conversations.close.after_minutes');
    }

    public function getAutoCloseLeniency()
    {
        return config('chat.conversations.close.leniency');
    }

    public function isClosable()
    {
        return
            $this->status != 'closed' &&
            $this->updated_at->diffInMinutes(now()) >
            $this->getAutoCloseMins();
    }

    public function isClosableAfterLeniency()
    {
        return
            $this->status != 'closed' &&
            $this->updated_at->diffInMinutes(now()) > $this->getAutoCloseLeniency() &&
            $this->lastMessage->isAutoCloseWarning();
    }

    public function sendAuthCloseWarningMsg()
    {
        $this->messages()->create([
            'user_id' => 1,
            'is_system' => true,
            'content' => 'This conversation will be closed in 10 minutes due to inactivity.'
        ]);
    }

    public function join( User $user )
    {
        $this->participants()->syncWithoutDetaching([$user->id]);
        $this->touch();

        $this->messages()->create([
            'user_id' => $user->id,
            'is_system' => true,
            'content' => sprintf('%s has joined the conversation.', $user->name)
        ]);

        if($this->status == "pending") {
            $this->update(['status' => 'open']);
        }
    }

    public function leave( User $user )
    {
        $this->participants()->detach([$user->id]);
        $this->touch();

        $this->messages()->create([
            'user_id' => $user->id,
            'is_system' => true,
            'content' => sprintf('%s has left the conversation.', $user->name)
        ]);

        if($this->participants()->count() == 0) {
            $this->close();
        }
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function hasParticipant($userId)
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'DESC');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function broadcastOn() : array
    {
        Log::info('Broadcasting to conversation ' . $this->id);
        Log::debug('Participants: ' . $this->participants()->pluck('user_id'));
        return $this->participants()->pluck('user_id')->each(function($userId) {
            return new PrivateChannel('App.Models.User.' . $userId);
        })->toArray();
    }

    public function broadcastQueue() : string
    {
        return 'chat';
    }

    public function broadcastWith() : array {
        return [
            'model' => [
                'id' => $this->id,
                'participants' => $this->participants()->get(),
            ]
        ];
    }

}
