<?php

use Illuminate\Database\Seeder;
use App\Models\Register;
use App\Models\LabPCR;
use App\Models\StatusRegister;
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
        foreach ($this->items() as $item) {
            Register::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['id']), 
                $item
            );
        }
    }

    public function items(): array
    {
        $id = 999990001;
        $idsampel = 10000001;
        $idregister = 1;
        $statuses = StatusRegister::get();
        $items = [];
        $status_order = ['waiting_sample','sample_taken','extraction_sample_extracted','extraction_sample_sent','pcr_sample_received','extraction_sample_reextract','pcr_sample_analyzed','sample_verified','sample_valid'];
        foreach ($statuses as $sr) {
            for ($n = 1; $n <= 3; $n++) {
                $lab_pcr = LabPCR::find($n);
                $item = [
                    "id" => $id,
                    "fasyankes_id" => rand(1,2),
                    "register_uuid" => (string) Str::uuid(),
                    "nomor_register"=> "20200425L" . str_pad($idregister,4,"0",STR_PAD_LEFT),
                    "nomor_sampel"=> $idsampel,
                    "register_status" => $sr->register_status,
                    "lab_pcr_id" => $n,
                    "lab_pcr_nama" => $lab_pcr->nama,
                    "creator_user_id" => 1,
                ];
                $t = date('Y-m-d H:i:s', strtotime('-1 day'));
                $s = '';
                $set = true;
                foreach ($status_order as $s) {
                    if ($set) {
                        $item['waktu_'.$s] = $t;
                    } else {
                        $item['waktu_'.$s] = null;
                    }
                    $t = date('Y-m-d H:i:s', strtotime($t.' +1 hour'));
                    if ($s == $sr->register_status) {
                        $set = false;
                    }
                }
                $items[] = $item;
                $id++;
                $idregister++;
                $idsampel++;
            }
        }
        return $items;
    }
}
