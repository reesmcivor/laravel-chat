<?php

namespace ReesMcIvor\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ReesMcIvor\Chat\Database\Factories\ConversationFactory;
use App\Models\User;
use ReesMcIvor\Chat\Events\CloseConversation;

class Conversation extends Model
{
    use HasFactory;
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
        event(new CloseConversation($message));
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
