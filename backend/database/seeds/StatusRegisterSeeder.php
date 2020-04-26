<?php

use Illuminate\Database\Seeder;
use App\Models\StatusRegister;

class StatusRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            StatusRegister::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['register_status']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "register_status" => "waiting_sample",
                "chamber" => "registrasi",
                "deskripsi" => "Registrasi Mandiri Masuk",
            ],
            [
                "register_status" => "sample_taken",
                "chamber" => "sample",
                "deskripsi" => "Sampel Diambil",
            ],
            [
                "register_status" => "extraction_sample_extracted",
                "chamber" => "extraction",
                "deskripsi" => "Ekstraksi Selesai",
            ],
            [
                "register_status" => "extraction_sample_reextract",
                "chamber" => "extraction",
                "deskripsi" => "Sampel butuh di-ekstraksi ulang",
            ],
            [
                "register_status" => "extraction_sample_sent",
                "chamber" => "extraction",
                "deskripsi" => "Hasil RNA Dikirim",
            ],
            [
                "register_status" => "pcr_sample_received",
                "chamber" => "pcr",
                "deskripsi" => "Sampel RNA Diterima di Lab PCR",
            ],
            [
                "register_status" => "pcr_sample_analyzed",
                "chamber" => "pcr",
                "deskripsi" => "Sampel Telah Dianalisa di Lab PCR",
            ],
            [
                "register_status" => "sample_verified",
                "chamber" => "verification",
                "deskripsi" => "Sampel Terverifikasi",
            ],
            [
                "register_status" => "sample_valid",
                "chamber" => "validation",
                "deskripsi" => "Sampel Valid",
            ],
        ];
    }
}
