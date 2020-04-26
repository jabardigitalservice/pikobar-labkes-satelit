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
                "email" => "admin_registrasi",
                "name" => "Admin Registrasi",
                "password" => bcrypt('admin_registrasi'),
                "role_id" => 2,
                "username" => "admin_registrasi",
            ],
            [
                "email" => "admin_sampel",
                "name" => "Admin sampel",
                "password" => bcrypt('admin_sampel'),
                "role_id" => 3,
                "username" => "admin_sampel",
            ],
            [
                "email" => "admin_ekstraksi",
                "name" => "Admin ekstraksi",
                "password" => bcrypt('admin_ekstraksi'),
                "role_id" => 4,
                "username" => "admin_ekstraksi",
            ],
            [
                "email" => "admin_pcr_labkesda",
                "name" => "Admin PCR Labkesda",
                "password" => bcrypt('admin_pcr_labkesda'),
                "role_id" => 5,
                "lab_pcr_id" => 1,
                "username" => "admin_pcr_labkesda",
            ],
            [
                "email" => "admin_pcr_itb",
                "name" => "Admin PCR ITB",
                "password" => bcrypt('admin_pcr_itb'),
                "role_id" => 5,
                "lab_pcr_id" => 2,
                "username" => "admin_pcr_itb",
            ],
            [
                "email" => "admin_pcr_unpad",
                "name" => "Admin PCR UNPAD",
                "password" => bcrypt('admin_pcr_unpad'),
                "role_id" => 5,
                "lab_pcr_id" => 3,
                "username" => "admin_pcr_unpad",
            ],
            [
                "email" => "admin_pcr_lainnya",
                "name" => "Admin PCR lainnya",
                "password" => bcrypt('admin_pcr_lainnya'),
                "role_id" => 5,
                "lab_pcr_id" => 999999,
                "username" => "admin_pcr_lainnya",
            ],
            [
                "email" => "verifikator",
                "name" => "Verifikator",
                "password" => bcrypt('verifikator'),
                "role_id" => 6,
                "username" => "verifikator",
            ],
            [
                "email" => "validator",
                "name" => "Validator",
                "password" => bcrypt('validator'),
                "role_id" => 7,
                "username" => "validator",
            ],
        ];
    }
}
