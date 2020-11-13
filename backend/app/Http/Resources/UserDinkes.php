<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDinkes extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'dinkes' => $this->lab_satelit->nama,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'has_data' => $this->has_data,
            'last_login_at' => $this->last_login_at,
        ];
    }
}