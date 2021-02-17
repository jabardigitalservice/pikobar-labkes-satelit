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
            'last_login_at' => $this->last_login_at,
            'lab' => optional($this->lab_satelit)->nama ?? '-',
            'alamat_lab' => optional($this->lab_satelit)->alamat ?? '-',
            'dinkes' => optional($this->kota)->nama ?? optional($this->lab_satelit)->nama,
        ];
    }
}
