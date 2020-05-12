<?php

use App\Models\Fasyankes;
use Illuminate\Database\Seeder;

class FasyankesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            Fasyankes::query()->updateOrCreate(
                \Illuminate\Support\Arr::only($item, ['nama', 'tipe', 'kota_id']), 
                $item
            );
        }
    }

    public function items(): array
    {
        return [
            [
                "nama"=> "RSUP Dr. Hasan Sadikin",
                "tipe"=> "rumah_sakit",
                "kota_id"=> 3273
            ],[
                "nama"=> "RSUD Kota Bogor",
                "tipe"=> "rumah_sakit",
                "kota_id"=> 3271
            ],[
                "nama"=> "RSP Dr. H.A. Rotinsulu",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSP Dr. Goenawan P",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Dr. Slamet",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD R. Syamsudin, SH",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Indramayu",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Gunungjati",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "Rumkit Tk. ll Dustira",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Cibinong",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Ciawi",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Cibabat",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Al Ihsan",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RS Bhayangkara Sartika Asih",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD dr. Soekardjo",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD SMC Kab. Tasik",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RS Paru Prov. Jabar Sidawangi",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Bayu Asih",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Karawang",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Sekarwangi",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Subang",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Waled",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Arjawinangun",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD 45 Kuningan",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Kab Bekasi",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Sumedang",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Banjar",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Ciamis",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Cideres",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Majalaya",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RS Lanud dr. M. Salamun",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Kota Depok",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],[
                "nama"=> "RSUD Sayang",
                "tipe"=> "rumah_sakit",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Bandung",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Bandung",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Bandung Barat",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Banjar",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Cimahi",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Bekasi",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Bekasi",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Bogor",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Bogor",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Ciamis",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Cianjur",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Cirebon",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Cirebon",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Depok",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Garut",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Indramayu",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Karawang",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Kuningan",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Majalengka",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Pangandaran",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Purwakarta",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Subang",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Sukabumi",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Sukabumi",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten SUmedang",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kota Tasikmalaya",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Tasikmalaya",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
            [
                "nama"=> "Kabupaten Tasikmalaya",
                "tipe"=> "dinkes",
                "kota_id"=> null
            ],
        ];
    }
}
