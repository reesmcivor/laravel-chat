<?php

namespace ReesMcIvor\Chat\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;

trait HasChat
{
    public function conversations(): MorphMany
    {
        return $this->morphMany(Conversation::class, 'participant');
    }

    public function sendMessage(string $content, Conversation $conversation): void
    {
        if ($conversation->role_id === $this->role_id) {
            $this->messages()->create([
                'content' => $content,
                'conversation_id' => $conversation->id,
            ]);
        } else {
            // Handle error or throw an exception
        }
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'sender');
    }
}
