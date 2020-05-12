<?php

use Illuminate\Database\Seeder;
use App\Models\Register;
use App\Models\Sampel;
use App\Models\LabPCR;
use App\Models\StatusSampel;
use App\Models\JenisSampel;
use App\User;
use Illuminate\Support\Str;

class DummySampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        list($register_items, $sample_items) = $this->items();
        foreach ($register_items as $items) {
            Register::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($items, ['id']), 
                $items
            );
        }
        foreach ($sample_items as $items) {
            Sampel::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($items, ['register_id']), 
                $items
            );
        }
    }

    public function items(): array
    {
        $id = 999990001;
        $idsampel = 10000001;
        $idregister = 1;
        $statuses = StatusSampel::get();
        $jenis_sampel = JenisSampel::find(1);
        $register_items = [];
        $sampel_items = [];
        $user = User::first();
        $status_order = ['waiting_sample','sample_taken','extraction_sample_extracted','extraction_sample_sent','pcr_sample_received','extraction_sample_reextract','pcr_sample_analyzed','sample_verified','sample_valid'];
        foreach ($statuses as $sr) {
            for ($n = 1; $n <= 3; $n++) {
                $lab_pcr = LabPCR::find($n);
                $register = [
                    "id" => $id,
                    "fasyankes_id" => rand(1,2),
                    "register_uuid" => (string) Str::uuid(),
                    "nomor_register"=> "L20200425" . str_pad($idregister,4,"0",STR_PAD_LEFT),
                    "creator_user_id" => $user->id,
                ];
                $sampel = [
                    "register_id" => $id,
                    "nomor_sampel"=> $idsampel,
                    "nomor_register"=> $register['nomor_register'],
                    "jenis_sampel_id" => $jenis_sampel->id,
                    "jenis_sampel_nama" => $jenis_sampel->nama,
                    "sampel_status" => $sr->sampel_status,
                    "lab_pcr_id" => $n,
                    "lab_pcr_nama" => $lab_pcr->nama,
                    "creator_user_id" => $user->id,
                ];
                $t = date('Y-m-d H:i:s', strtotime('-1 day'));
                $s = '';
                $set = true;
                foreach ($status_order as $s) {
                    if ($set) {
                        $sampel['waktu_'.$s] = $t;
                    } else {
                        $sampel['waktu_'.$s] = null;
                    }
                    $t = date('Y-m-d H:i:s', strtotime($t.' +1 hour'));
                    if ($s == $sr->sampel_status) {
                        $set = false;
                    }
                }
                $register_items[] = $register;
                $sampel_items[] = $sampel;
                $id++;
                $idregister++;
                $idsampel++;
            }
        }
        return [$register_items, $sampel_items];
    }
}
