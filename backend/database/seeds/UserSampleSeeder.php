<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            User::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['email']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "email" => "superadmin",
                "name" => "Super Admin",
                "password" => bcrypt('jabarjuara'),
                "role_id" => 1,
                "username" => "superadmin",
            ],
            [
                "email" => "websatelit",
                "name" => "websatelit",
                "password" => bcrypt('jabarjuara'),
                "role_id" => 8,
                "username" => "websatelit",
                "lab_satelit_id" => 1,
            ]
        ];
    }
}
