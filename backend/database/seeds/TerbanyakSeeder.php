<?php

use App\Models\Fasyankes;
use App\Models\FasyankesTerbanyak;
use App\Models\Kota;
use App\Models\KotaTerbanyak;
use App\Models\Labkes\Pasien as PasienLabkes;
use App\Models\Labkes\Register as RegisterLabkes;
use App\Models\Pasien as PasienSatelit;
use App\Models\Register as RegisterSatelit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TerbanyakSeeder extends Seeder
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
            foreach ($this->kotaTerbanyak() as $item) {
                KotaTerbanyak::updateOrCreate(
                    \Illuminate\Support\Arr::only($item, ['id']),
                    $item
                );
            }
            foreach ($this->fasyankesTerbanyak() as $item) {
                FasyankesTerbanyak::updateOrCreate(
                    \Illuminate\Support\Arr::only($item, ['id']),
                    $item
                );
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function kotaTerbanyak()
    {
        $models = Kota::all();
        $data = [];
        foreach ($models as $row) {
            $satelit = PasienSatelit::where('kota_id', $row->id)->count();
            $labkes = PasienLabkes::where('kota_id', $row->id)->where('is_from_migration', false)->count();
            $data[] = (object) [
                'id' => $row->id,
                'nama' => $row->nama,
                'total' => $satelit + $labkes,
            ];
        }
        $data = collect($data)->SortByDesc('total')->slice(0, 5);
        return $this->generateData($data, 'kota_id');
    }

    public function fasyankesTerbanyak()
    {
        $models = Fasyankes::all();
        $data = [];
        foreach ($models as $row) {
            $satelit = RegisterSatelit::where('fasyankes_id', $row->id)->count();
            $labkes = RegisterLabkes::where('fasyankes_id', $row->id)->where('is_from_migration', false)->count();
            $data[] = (object) [
                'id' => $row->id,
                'nama' => $row->nama,
                'total' => $satelit + $labkes,
            ];
        }
        $data = collect($data)->SortByDesc('total')->slice(0, 5);
        return $this->generateData($data, 'fasyankes_id');
    }

    public function generateData($data, $foreignKey)
    {
        $id = 1;
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'id' => $id,
                $foreignKey => $row->id,
                'nama' => $row->nama,
                'total' => $row->total,
            ];
            $id++;
        }
        return $result;
    }
}
