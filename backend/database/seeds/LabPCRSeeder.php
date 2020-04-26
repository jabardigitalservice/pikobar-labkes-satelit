<?php

use App\Models\LabPCR;
use Illuminate\Database\Seeder;

class LabPCRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            LabPCR::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['nama']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "id" => 1,
                "nama"=> "Labkesda Jabar",
            ],
            [
                "id" => 2,
                "nama"=> "ITB",
            ],
            [
                "id" => 3,
                "nama"=> "UNPAD",
            ],
            [
                "id" => 999999,
                "nama"=> "Lab Lainnya",
            ],
        ];
    }
}
