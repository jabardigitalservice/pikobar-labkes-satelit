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
                \Illuminate\Support\Arr::only($item, ['roles_name']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "id" => "1",
                "roles_name"=> "admin",
            ],
            [
                "id" => "2",
                "roles_name"=> "admin_registrasi",
            ],
            [
                "id" => "3",
                "roles_name"=> "admin_sample",
            ],
            [
                "id" => "4",
                "roles_name"=> "admin_ekstraksi",
            ],
            [
                "id" => "5",
                "roles_name"=> "admin_pcr",
            ],
            [
                "id" => "6",
                "roles_name"=> "verifikator",
            ],
            [
                "id" => "7",
                "roles_name"=> "validator",
            ],
        ];
    }
}
