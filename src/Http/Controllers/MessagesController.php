<?php

namespace ReesMcIvor\Chat\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReesMcIvor\Chat\Models\Conversation;

class MessagesController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        $message = $conversation->messages()->create([
            'user_id' => Auth::user()->id,
            'conversation_id' => $request->get('conversation_id'),
            'content' => $request->get('content')
        ]);

        return redirect()->route('conversations.show', $conversation)
            ->with('Message sent successfully.');
    }
}
