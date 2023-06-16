<?php

namespace ReesMcIvor\Chat\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReesMcIvor\Chat\Http\Requests\CreateConversationRequest;
use ReesMcIvor\Chat\Http\Resources\ConversationResource;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;

class ConversationController extends Controller
{

    public function list(Request $request)
    {
        return ConversationResource::collection(
            Conversation::with(['participants', 'messages'])->get()
        );
    }

    public function create(CreateConversationRequest $request)
    {
        $conversation = Conversation::create(['subject' => $request->subject ?? '']);
        $conversation->participants()->attach([$request?->user()?->id]);

        return ConversationResource::make($conversation);
    }

    public function close(CloseConversationRequest $request, Conversation $conversation)
    {
        $conversation->close();
        return response()->json(['message' => 'Conversation closed successfully.']);
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
