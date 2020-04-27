<?php

use Illuminate\Database\Seeder;
use App\Models\JenisSampel;

class JenisSampelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            JenisSampel::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['id']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "id" => "1",
                "nama" => "Usap Nasofaring & Orofaring",
            ],
            [
                "id" => "2",
                "nama" => "Sputum",
            ],
            [
                "id" => "3",
                "nama" => "Bronchoalveolar Lavage",
            ],
            [
                "id" => "4",
                "nama" => "Tracheal Aspirate",
            ],
            [
                "id" => "5",
                "nama" => "Nasal Wash",
            ],
            [
                "id" => "6",
                "nama" => "Jaringan Biopsi/Otopsi",
            ],
            [
                "id" => "7",
                "nama" => "Serum Akut (kurang dari 7 hari demam)",
            ],
            [
                "id" => "8",
                "nama" => "Serum konvalesen (2-3 minggu demam)",
            ],
            [
                "id" => "9",
                "nama" => "Whole blood",
            ],
            [
                "id" => "10",
                "nama" => "Plasma",
            ],
            [
                "id" => "11",
                "nama" => "Fingerprick",
            ],
            [
                "id" => "999999",
                "nama" => "Jenis Sampel Lainnya (Sebutkan)",
            ],
        ];
    }
}
