<?php

use App\Models\Negara;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class NegaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            foreach ($this->items() as $item) {
                Negara::updateOrCreate(
                    \Illuminate\Support\Arr::only($item, ['id']),
                    $item
                );
            }
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function items(): array
    {
        $response = Curl::to('https://restcountries.eu/rest/v2/all')
            ->get();
        $response = json_decode($response);
        $data = [];
        foreach ($response as $key => $value) {
            $data[] = [
                'id' => ++$key,
                'nama' => $value->name,
                'inisial' => $value->alpha3Code,
            ];
        }
        return $data;
    }
}
