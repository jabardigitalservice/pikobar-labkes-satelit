<?php

use App\Models\Provinsi;
use Illuminate\Database\Seeder;

class ProvinsiSeeder extends Seeder
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
            Provinsi::query()->updateOrCreate($item);
        }
    }

    public function items(): array
    {
        $csv = array_map('str_getcsv', file(base_path()."/database/seeds/csv/provinces.csv"));
        $provinces = [];

        foreach ($csv as $value) {
            $item['id'] = $value[0];
            $item['nama'] = $value[1];
      
            if ($item['id'] && $item['nama'] && 
                $item['id'] != 'id' && $item['nama'] != 'nama') {
                array_push($provinces, $item);
            }
        }

        return $provinces;
        
    }
}
