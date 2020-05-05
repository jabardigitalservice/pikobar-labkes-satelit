<?php

use App\Models\Validator;
use Illuminate\Database\Seeder;

class ValidatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            Validator::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['nip']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "nama" => "dr. RYAN B. RISTANDI, Sp.PK., MMRS",
                "nip"=> "198205072009021004",
                "is_active"=> true,
            ],[
                "nama" => "dr. CUT NUR CINTHIA ALAMANDA, Sp.PK., M.Kes",
                "nip"=> "197409062014122001",
                "is_active"=> true,
            ]
        ];
    }
}
