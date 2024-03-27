<?php

namespace ReesMcIvor\Chat\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;

class MessagesController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        $message = $conversation->messages()->create([
            'user_id' => Auth::user()->id,
            'conversation_id' => $request->get('conversation_id'),
            'content' => $request->get('content')
        ]);

        return redirect(route('tenant.conversations.show', $conversation))
            ->with('Message sent successfully.');
    }

    public function update(Request $request, Message $message)
    {
        $message->update([
            'content' => $request->get('content')
        ]);

        return redirect()->back()
            ->with('Message updated successfully.');
    }

    public function destroy( Message $message )
    {
        $message->delete();

        return redirect()->back()
            ->with('Message deleted successfully.');
    }
}
