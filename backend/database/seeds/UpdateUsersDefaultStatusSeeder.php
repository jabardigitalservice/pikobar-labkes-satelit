<?php

use App\Enums\UserStatusEnum;
use App\User;
use Illuminate\Database\Seeder;

class UpdateUsersDefaultStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::whereNull('status')->update(['status' => UserStatusEnum::ACTIVE()]);
    }
}
