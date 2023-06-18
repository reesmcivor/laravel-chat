<?php

namespace ReesMcIvor\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use ReesMcIvor\Chat\Database\Factories\ConversationFactory;
use App\Models\User;
use ReesMcIvor\Chat\Events\CloseConversation;

class Conversation extends Model
{
    use HasFactory;
    use Userstamps;
    use SoftDeletes;

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
            'content' => 'This conversation has been closed.'
        ]);

        event(new CloseConversation($this));
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
            'content' => 'This conversation will be closed in 10 minutes due to inactivity.'
        ]);
    }

    public function join( User $user )
    {
        $this->participants()->attach($user);
        $this->touch();

        $this->messages()->create([
            'user_id' => $user->id,
            'content' => sprintf('%s has joined the conversation.', $user->name)
        ]);

        if($this->status == "pending") {
            $this->update(['status' => 'open']);
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
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

}
