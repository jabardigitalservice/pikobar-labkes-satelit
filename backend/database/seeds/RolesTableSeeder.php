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
                "id" => "8",
                "roles_name"=> "Admin Lab Satelit",
            ],
            [
                "id" => "1",
                "roles_name"=> "SuperAdmin",
            ],
            [
                "id" => "9",
                "roles_name"=> "Perujuk",
            ],
        ];
    }
}
