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
                "roles_name"=> "admin",
            ],
        ];
    }
}
