<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ProvinsiSeeder::class);
        $this->call(KotaSeeder::class);
        $this->call(FasyankesSeeder::class);
        $this->call(GejalaSeeder::class);
        $this->call(PenyakitPenyertaSeeder::class);
        $this->call(LabPCRSeeder::class);
        $this->call(StatusSampelSeeder::class);
        $this->call(JenisSampelSeeder::class);
        $this->call(ValidatorSeeder::class);

    }
}
