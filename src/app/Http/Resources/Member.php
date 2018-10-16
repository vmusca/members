<?php

namespace LaravelEnso\Members\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Member extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'userIds' => $this->whenLoaded('users', $this->userIds()),
            'users' => $this->whenLoaded('users', $this->userList()),
            'edit' => false,
        ];
    }
}
