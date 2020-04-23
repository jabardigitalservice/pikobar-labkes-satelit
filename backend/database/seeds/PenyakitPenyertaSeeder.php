<?php

use App\Models\PenyakitPenyerta;
use Illuminate\Database\Seeder;

class PenyakitPenyertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            PenyakitPenyerta::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['nama_penyakit']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "nama_penyakit"=> "Penyakit Kardiovaskuler/Hipertensi",
                "is_active"=> true,
            ],[
                "nama_penyakit"=> "Diabetes Mellitus",
                "is_active"=> true,
            ],[
                "nama_penyakit"=> "Liver",
                "is_active"=> true,
            ],[
                "nama_penyakit"=> "Kronik neurologi / neuromuskula",
                "is_active"=> true,
            ],[
                "nama_penyakit"=> "Imunodefisiensi / HIV",
                "is_active"=> true,
            ],[
                "nama_penyakit"=> "Penyakit paru kronik",
                "is_active"=> true,
            ],[
                "nama_penyakit"=> "Penyakit ginjal",
                "is_active"=> true,
            ],
        ];
    }
}
