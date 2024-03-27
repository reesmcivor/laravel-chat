<?php

namespace ReesMcIvor\Chat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => UserResource::make($this->user),
            'owner' => $this->owner,
            'creator' => UserResource::make($this->creator),
            'created_at' => $this?->created_at?->timestamp ?? now()->timestamp,
        ];
    }
}
