<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
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
            'has_data_perujuk' => $this->has_data_perujuk,
            'last_login_at' => $this->last_login_at,
            'role_id' => $this->role_id,
            'lab' => $this->lab_satelit,
            'dinkes' => $this->dinkes,
            'perujuk' => $this->perujuk,
            'kota_perujuk' => optional($this->perujuk)->kota,
        ];
    }
}
