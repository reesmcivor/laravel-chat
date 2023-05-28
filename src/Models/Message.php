<?php

namespace ReesMcIvor\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReesMcIvor\Chat\Database\Factories\MessageFactory;
use App\Models\User;

class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = "chat_threads";

    protected static function newFactory()
    {
        return MessageFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

}
