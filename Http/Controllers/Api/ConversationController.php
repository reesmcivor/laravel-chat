<?php

namespace ReesMcIvor\Chat\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function list(Request $request)
    {
        return $request->user()->conversations;
    }
}
