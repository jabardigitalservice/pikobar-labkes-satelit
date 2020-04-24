<?php

namespace App\Http\Resources;

use App\Models\PenyakitPenyerta;
use Illuminate\Http\Resources\Json\JsonResource;

class PenyakitPenyertaResource extends JsonResource
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
            'daftar_penyakit'=> $this->getDaftarPenyakit($this->daftar_penyakit),
            'keterangan_lain'=> $this->keterangan_lain
        ];
    }

    private function getDaftarPenyakit(array $daftarPenyakit) : array
    {
        foreach ($daftarPenyakit as $key => &$penyakit) {
            $dataPenyakit = PenyakitPenyerta::find($penyakit['penyakit_penyerta_id']);

            $penyakit['penyakit_penyerta_nama'] = $dataPenyakit->getAttribute('nama_penyakit');
        }
        
        return $daftarPenyakit;
    }
}
