<?php

namespace ReesMcIvor\Chat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
        ];
    }
}
