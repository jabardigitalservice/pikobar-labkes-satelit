<?php

use App\Models\LabSatelit;
use Illuminate\Database\Seeder;

class LabSatelitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->items() as $item) {
            LabSatelit::query()->updateOrCreate($item);
        }
    }

    public function items(): array
    {
        $csv = array_map('str_getcsv', file(base_path() . "/database/seeds/csv/mst_lab_jabar.csv"));
        $items = [];

        foreach (array_slice($csv,1) as $value) {
            $item['id'] = $value[0];
            $item['nama'] = $value[2];
            $item['kode_lab'] = $value[1];
            $item['provinsi_id'] = $value[3];
            $item['type'] = $value[3];
            $item['is_check'] = $value[6];
            array_push($items, $item);
        }

        return $items;
    }
}
