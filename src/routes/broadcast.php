<?php

use Illuminate\Support\Facades\Broadcast;
use ReesMcIvor\Chat\Models\Conversation;

Broadcast::channel('App.Chat.Conversation.{id}', function ($user, $id) {
    $conversation = Conversation::find($id);
    if($conversation && $conversation->hasParticipant($user->id)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
    return null;
});
