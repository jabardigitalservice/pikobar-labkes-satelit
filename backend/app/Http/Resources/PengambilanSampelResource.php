<?php

namespace App\Http\Resources;

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
        return [
            'sampel_diambil' => $this->sampel_diambil,
            'sampel_diterima' => $this->sampel_diterima,
            'diterima_dari_faskes' => $this->diterima_dari_faskes,
            'penerima_sampel' => $this->penerima_sampel,
            'catatan' => $this->catatan,
            'status' => $this->status,
            'nomor_ekstraksi' => $this->nomor_ekstraksi,
            'sampel_rdt' => $this->sampel_rdt,
            'sampel'=> $this->sampel
        ];
    }
}
