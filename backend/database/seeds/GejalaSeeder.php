<?php

use App\Models\Gejala;
use Illuminate\Database\Seeder;

class GejalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            Gejala::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['nama']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "nama"=> "Panas",
                "is_active"=> true,
            ],[
                "nama"=> "Tanda Pneumonia",
                "is_active"=> true,
            ],[
                "nama"=> "Batuk",
                "is_active"=> true,
            ],[
                "nama"=> "Nyeri Tenggorokan",
                "is_active"=> true,
            ],[
                "nama"=> "Sesak Nafas",
                "is_active"=> true,
            ],[
                "nama"=> "Pilek",
                "is_active"=> true,
            ],[
                "nama"=> "Lesu",
                "is_active"=> true,
            ],[
                "nama"=> "Sakit Kepala",
                "is_active"=> true,
            ],[
                "nama"=> "Diare",
                "is_active"=> true,
            ],[
                "nama"=> "Mual/Muntah",
                "is_active"=> true,
            ],
        ];
    }
}
