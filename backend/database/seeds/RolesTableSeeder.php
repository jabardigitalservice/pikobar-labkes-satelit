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
                "roles_name"=> "Admin Lab Satelit",
            ],
        ];
    }
}
