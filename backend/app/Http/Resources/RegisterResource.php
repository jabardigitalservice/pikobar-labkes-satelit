<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
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
            'id'=> $this->id,
            'nomor_register'=> $this->nomor_register,
            'nomor_rekam_medis'=> $this->nomor_rekam_medis,
            'nama_dokter'=> $this->nama_dokter,
            'fasyankes'=> new FasyankesResource($this->fasyankes),
            'no_telp'=> $this->no_telp,
            'created_at'=> $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=> $this->updated_at->format('Y-m-d H:i:s'),
            'pasien'=> $this->pasiens()->first(),
            'riwayat_kunjungan'=> $this->riwayatKunjungan->getAttribute('riwayat'),
            'tanda_gejala'=> $this->gejalaPasien()->first()->pivot,
            'pemeriksaan_penunjang'=> $this->pemeriksaanPenunjang()->first()->pivot,
            'riwayat_kontak'=> $this->riwayatKontak->map(function($item){
                return $item->pivot;
            }),
            'riwayat_lawatan'=> $this->riwayatLawatan->map(function($item){
                return $item->pivot;
            }),
            'penyakit_penyerta'=> $this->riwayatPenyakitPenyerta->only([
                'daftar_penyakit', 
                'keterangan_lain'
            ])
        ];
    }
}
