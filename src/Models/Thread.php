<?php

namespace ReesMcIvor\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReesMcIvor\Chat\Database\Factories\ThreadFactory;
use App\Models\User;

class Thread extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = "chat_threads";

    protected static function newFactory()
    {
        return ThreadFactory::new();
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
