<?php

namespace ReesMcIvor\Chat\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use ReesMcIvor\Chat\Http\Requests\CreateMessageRequest;
use ReesMcIvor\Chat\Http\Resources\MessageResource;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\ChatGPT\Services\ChatGPT;

class MessagesController extends Controller
{
    public function create($conversationId, CreateMessageRequest $request)
    {
        $conversation = Conversation::find($conversationId);

        $message = $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'content' => $request->get('content')
        ]);

        return response()->json(['message' => 'Message created successfully.', 'data' => new MessageResource($message)]);
    }
}
