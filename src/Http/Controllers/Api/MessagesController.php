<?php

namespace ReesMcIvor\Chat\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReesMcIvor\Chat\Models\Conversation;

class MessagesController extends Controller
{
    public function create(Request $request, Conversation $conversation)
    {
        $message = $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'content' => $request->get('content')
        ]);

        return response()->json(['message' => 'Message created successfully.', 'data' => $message]);
    }
}
