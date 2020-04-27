<?php

use Illuminate\Database\Seeder;
use App\Models\StatusSampel;

class StatusSampelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            StatusSampel::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['sampel_status']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "sampel_status" => "waiting_sample",
                "chamber" => "registrasi",
                "deskripsi" => "Registrasi Mandiri Masuk",
            ],
            [
                "sampel_status" => "sample_taken",
                "chamber" => "sample",
                "deskripsi" => "Sampel Diambil",
            ],
            [
                "sampel_status" => "extraction_sample_extracted",
                "chamber" => "extraction",
                "deskripsi" => "Ekstraksi Selesai",
            ],
            [
                "sampel_status" => "extraction_sample_reextract",
                "chamber" => "extraction",
                "deskripsi" => "Sampel butuh di-ekstraksi ulang",
            ],
            [
                "sampel_status" => "extraction_sample_sent",
                "chamber" => "extraction",
                "deskripsi" => "Hasil RNA Dikirim",
            ],
            [
                "sampel_status" => "pcr_sample_received",
                "chamber" => "pcr",
                "deskripsi" => "Sampel RNA Diterima di Lab PCR",
            ],
            [
                "sampel_status" => "pcr_sample_analyzed",
                "chamber" => "pcr",
                "deskripsi" => "Sampel Telah Dianalisa di Lab PCR",
            ],
            [
                "sampel_status" => "sample_verified",
                "chamber" => "verification",
                "deskripsi" => "Sampel Terverifikasi",
            ],
            [
                "sampel_status" => "sample_valid",
                "chamber" => "validation",
                "deskripsi" => "Sampel Valid",
            ],
        ];
    }
}
