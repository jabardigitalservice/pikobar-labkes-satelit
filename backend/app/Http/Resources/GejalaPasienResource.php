<?php

namespace App\Http\Resources;

use App\Models\Gejala;
use Illuminate\Http\Resources\Json\JsonResource;

class GejalaPasienResource extends JsonResource
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
            'pasien_rdt'=> $this->pivot->pasien_rdt,
            'hasil_rdt_positif'=> $this->pivot->hasil_rdt_positif,
            'tanggal_rdt'=> optional($this->pivot->tanggal_rdt)->format('Y-m-d'),
            'keterangan_rdt'=> $this->pivot->keterangan_rdt,
            'tanggal_onset_gejala'=> $this->pivot->tanggal_onset_gejala->format('Y-m-d'),
            'daftar_gejala'=> $this->getDaftarGejala($this->pivot->daftar_gejala),
            'gejala_lain'=> $this->pivot->gejala_lain,
        ];
    }

    private function getDaftarGejala(array $daftarGejala) : array 
    {
        foreach ($daftarGejala as $key => &$gejala) {
            $dataGejala = Gejala::find($gejala['gejala_id'])->first();

            $gejala['gejala_nama'] = $dataGejala->getAttribute('nama');
        }

        return $daftarGejala;
    }
}
