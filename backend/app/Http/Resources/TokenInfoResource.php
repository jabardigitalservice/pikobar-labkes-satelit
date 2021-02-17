<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenInfoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'token' => $this->token,
            'email' => $this->email,
            'username' => $this->user->username,
            'name' => $this->user->name,
            'admin_dinkes' => $this->user->koordinator,
            'dinkes' => optional($this->user->lab_satelit)->nama ?? optional($this->user->kota)->nama,
            'user' => $this->user
        ];
    }
}
