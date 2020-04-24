<?php

use App\Models\Kota;
use Illuminate\Database\Seeder;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->items() as $item)
        {
            Kota::query()->updateOrCreate($item);
        }
    }

    public function items(): array
    {
        $csv = array_map('str_getcsv', file(base_path()."/database/seeds/csv/districts.csv"));
        $cities = [];

        foreach ($csv as $value) {
            $item['id'] = $value[0];
            $item['provinsi_id'] = $value[1];
            $item['nama'] = $value[2];
      
            if ($item['id'] && $item['nama'] &&  $item['provinsi_id'] &&
                $item['id'] != 'id' && $item['provinsi_id'] != 'province_id' && $item['nama'] != 'nama') {
                array_push($cities, $item);
            }
        }

        return $cities;
        
    }
}
