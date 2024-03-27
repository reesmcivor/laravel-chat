<?php

namespace ReesMcIvor\Chat\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReesMcIvor\Chat\Http\Requests\CreateConversationRequest;
use ReesMcIvor\Chat\Http\Resources\ConversationResource;
use ReesMcIvor\Chat\Http\Requests\CloseConversationRequest;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;

class ConversationController extends Controller
{

    public function firstOrCreate(Request $request)
    {
        $conversation = Conversation::where('status', '!=', 'closed')
            ->where('created_by', auth()->user()->id)
            ->firstOrCreate([], ['subject' => '', 'status' => 'open']);

        $conversation->participants()->sync([ auth()->user()->id ]);
        return ConversationResource::make($conversation);
    }

    public function list(Request $request)
    {
        return ConversationResource::collection(
            Conversation
                ::with(['participants', 'messages', 'messages.creator'])
                ->whereHas('participants', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })
                ->orderBy('updated_at', 'desc')
                ->get()
        );
    }

    public function create(CreateConversationRequest $request)
    {
        $conversation = Conversation::create(['subject' => $request->subject ?? '']);
        $conversation->participants()->sync([$request?->user()?->id]);

        return ConversationResource::make($conversation);
    }

    public function close(CloseConversationRequest $request, Conversation $conversation)
    {
        $conversation->close();
        return response()->json(['message' => 'Conversation closed successfully.']);
    }

    public function view(Request $request, $conversationId)
    {
        $conversation = Conversation::find($conversationId);

        return Message
            ::with('user')
            ->where('conversation_id', $conversation->id)
            //->orderBy('created_at', 'desc')
            ->get();
    }
}
