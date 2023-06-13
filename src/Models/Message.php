<?php

namespace ReesMcIvor\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReesMcIvor\Chat\Database\Factories\MessageFactory;
use App\Models\User;
use ReesMcIvor\Chat\Events\NewChatMessage;

class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = "messages";

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($message) {
            event(new NewChatMessage($message));
        });
        static::deleted(function ($message) {
            event(new NewChatMessage($message));
        });
        static::updated(function ($message) {
            event(new NewChatMessage($message));
        });
    }

    protected static function newFactory()
    {
        return MessageFactory::new();
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
