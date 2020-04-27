<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            Role::query()->updateOrCreate(
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
                "roles_name"=> "Admin",
            ],
            [
                "id" => "2",
                "roles_name"=> "Admin Registrasi",
            ],
            [
                "id" => "3",
                "roles_name"=> "Admin Sample",
            ],
            [
                "id" => "4",
                "roles_name"=> "Admin Ekstraksi",
            ],
            [
                "id" => "5",
                "roles_name"=> "Admin PCR",
            ],
            [
                "id" => "6",
                "roles_name"=> "Verifikator",
            ],
            [
                "id" => "7",
                "roles_name"=> "Validator",
            ],
        ];
    }
}
