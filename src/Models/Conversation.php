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
        event(new CloseConversation($this));
    }

    public static function getAutoCloseWarningAfterMinutes() : int
    {
        return 60;
    }

    public static function getAutoCloseAfterMinutes() : int
    {
        return 70;
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

}
