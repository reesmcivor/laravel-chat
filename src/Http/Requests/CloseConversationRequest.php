<?php

namespace ReesMcIvor\Chat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CloseConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
