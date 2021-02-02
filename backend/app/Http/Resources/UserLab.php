<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserLab extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'koordinator' => $this->koordinator,
            'status' => $this->status,
            'has_data' => $this->has_data,
            'last_login_at' => $this->last_login_at,
            'lab' => $this->lab_satelit ? $this->lab_satelit->nama : '-',
            'alamat_lab' => $this->lab_satelit ? $this->lab_satelit->alamat : '-',
        ];
    }
}
