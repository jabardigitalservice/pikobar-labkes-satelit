<?php

use App\Models\Fasyankes;
use Illuminate\Database\Seeder;

class FasyankesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            Fasyankes::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['nama', 'tipe', 'kota_id']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "nama"=> "RSUP Dr. Hasan Sadikin",
                "tipe"=> "rumah_sakit",
                "kota_id"=> 3273
            ],[
                "nama"=> "RSUD Kota Bogor",
                "tipe"=> "rumah_sakit",
                "kota_id"=> 3271
            ],
        ];
    }
}
