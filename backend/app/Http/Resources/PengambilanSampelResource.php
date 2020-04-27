<?php

namespace App\Http\Resources;

use App\Models\Sampel;
use Illuminate\Http\Resources\Json\JsonResource;

class PengambilanSampelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $listSampel = $this->sampel_id ? Sampel::whereIn('id', explode(',', $this->sampel_id))->get() : [];

        return [
            'id'=> $this->id,
            'sampel_diambil' => $this->sampel_diambil,
            'sampel_diterima' => $this->sampel_diterima,
            'diterima_dari_faskes' => $this->diterima_dari_faskes,
            'penerima_sampel' => $this->penerima_sampel,
            'catatan' => $this->catatan,
            'status' => $this->status,
            'nomor_ekstraksi' => $this->nomor_ekstraksi,
            'sampel_rdt' => $this->sampel_rdt,
            'sampel'=> $listSampel
        ];
    }
}
