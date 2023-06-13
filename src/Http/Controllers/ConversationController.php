<?php

namespace ReesMcIvor\Chat\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        return view('chat::conversations.index', [
            'conversations' => Conversation::paginate()
        ]);
    }

    public function create(Request $request)
    {
        Conversation::create(['subject' => 'test'])->participants()->attach([$request->user()->id]);
        return response()->json(['message' => 'Conversation created successfully.']);
    }

    public function show(Request $request, Conversation $conversation)
    {
        return view('chat::conversations.view', [
            'conversation' => $conversation
        ]);
    }

    public function destroy(Request $request, Conversation $conversation)
    {
        $conversation->delete();
        return response()->json(['message' => 'Conversation deleted successfully.']);
    }
}
