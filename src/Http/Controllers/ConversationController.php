<?php

namespace ReesMcIvor\Chat\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ReesMcIvor\Chat\Http\Resources\ConversationResource;
use ReesMcIvor\Chat\Models\Conversation;
use ReesMcIvor\Chat\Models\Message;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        return view('chat::conversations.index', [
            'conversations' => Conversation::orderBy('updated_at', 'ASC')->paginate()
        ]);
    }

    public function create(Request $request)
    {
        Conversation::create(['subject' => 'test'])->participants()->sync([$request->user()->id]);
        return redirect(route('tenant.conversations.index'))
            ->with('success', __('Conversation created successfully.'));
    }

    public function join(Request $request, Conversation $conversation)
    {
        $conversation->join( $request->user() );
        return response()->json(['message' => 'Conversation joined successfully.']);
    }

    public function leave(Request $request, Conversation $conversation)
    {
        $conversation->leave( $request->user() );
        return response()->json(['message' => 'Conversation left successfully.']);
    }

    public function close(Request $request, Conversation $conversation)
    {
        $conversation->close();
        return redirect()->back()->with('success', __('Conversation closed successfully.'));
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
        return redirect(route('tenant.conversations.index'))
            ->with('success', __('Conversation deleted successfully.'));
    }
}
