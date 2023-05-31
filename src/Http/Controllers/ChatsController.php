<?php

namespace ReesMcIvor\Chat\Http\Controllers;

use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function index()
    {
        return view('chat::index');
    }
}