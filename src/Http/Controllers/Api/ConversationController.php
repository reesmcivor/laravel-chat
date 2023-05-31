<?php

namespace ReesMcIvor\Chat\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;

class ConversationController extends Controller
{
    public function list(Request $request)
    {
        return Conversation::with(['participants', 'messages'])->get();
    }

    public function create(Request $request)
    {

        Conversation::create(['subject' => 'test'])->participants()->attach([$request->user()->id]);
        return response()->json(['message' => 'Conversation created successfully.']);
    }

    public function view(Request $request, Conversation $conversation)
    {
        return Message
            ::with('user')
            ->where('conversation_id', $conversation->id)
            //->orderBy('created_at', 'desc')
            ->get();
    }
}
