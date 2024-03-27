<?php

namespace ReesMcIvor\Chat\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->profile->role ?? 'Premium Member',
            'thumbnail' => $this->profile_photo_path ?: $this->getPhoto(),
        ];
    }
}
