<?php

namespace ReesMcIvor\Chat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ReesMcIvor\Chat\Database\Factories\ConversationFactory;
use App\Models\User;

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
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants');
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
