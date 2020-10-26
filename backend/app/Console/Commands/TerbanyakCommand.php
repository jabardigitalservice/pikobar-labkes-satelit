<?php

namespace App\Console\Commands;

use App\Models\Fasyankes;
use App\Models\FasyankesTerbanyak;
use App\Models\Kota;
use App\Models\KotaTerbanyak;
use App\Models\Labkes\Register as RegisterLabkes;
use App\Models\Labkes\Sampel as SampelLabkes;
use App\Models\Register as RegisterSatelit;
use App\Models\Sampel as SampelSatelit;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TerbanyakCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:terbanyak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
        $dataWeekly = [];
        $dateWeekly = $this->__getDate('Weekly');
        $dataMonthly = [];
        $dateMonthly = $this->__getDate('Monthly');
        foreach ($models as $row) {
            $total = $this->__getChartHasilPemeriksaan($dateWeekly, $row->id);
            $dataWeekly[] = (object) [
                'id' => $row->id,
                'nama' => $row->nama,
                'tipe' => 'Weekly',
                'total' => $total,
            ];
            $total = $this->__getChartHasilPemeriksaan($dateMonthly, $row->id);
            $dataMonthly[] = (object) [
                'id' => $row->id,
                'nama' => $row->nama,
                'tipe' => 'Monthly',
                'total' => $total,
            ];
        }
        $dataWeekly = collect($dataWeekly)->SortByDesc('total')->slice(0, 5);
        $dataMonthly = collect($dataMonthly)->SortByDesc('total')->slice(0, 5);
        return $this->generateData(array_merge($dataWeekly->toArray(), $dataMonthly->toArray()), 'kota_id');
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
        foreach ($data as $key => $row) {
            $result[$key] = [
                'id' => $id,
                $foreignKey => $row->id,
                'nama' => $row->nama,
                'total' => $row->total,
            ];
            if ($foreignKey == 'kota_id') {
                $result[$key]['tipe'] = $row->tipe;
            }
            $id++;
        }
        return $result;
    }

    private function __getChartHasilPemeriksaan($tanggal, $kota)
    {
        $satelit = SampelSatelit::leftJoin('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->leftJoin('register', 'sampel.register_id', 'register.id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'register.id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id')
            ->whereNull('register.deleted_at')
            ->where('sampel.sampel_status', 'pcr_sample_analyzed');
        $labkes = SampelLabkes::leftJoin('pemeriksaansampel', 'pemeriksaansampel.sampel_id', 'sampel.id')
            ->leftJoin('register', 'register.id', 'sampel.register_id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'sampel.register_id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('ekstraksi', 'sampel.id', 'ekstraksi.sampel_id')
            ->where('sampel_status', 'sample_valid')
            ->where('sampel.is_from_migration', false)
            ->where('ekstraksi.is_from_migration', false)
            ->where('pemeriksaansampel.is_from_migration', false)
            ->where('register.is_from_migration', false)
            ->where('pasien.is_from_migration', false)
            ->where('pasien_register.is_from_migration', false)
            ->whereNull('register.deleted_at');
        if ($tanggal && !is_array($tanggal)) {
            $satelit->whereDate('waktu_pcr_sample_analyzed', date('Y-m-d', strtotime($tanggal)));
            $labkes->whereDate('waktu_sample_valid', date('Y-m-d', strtotime($tanggal)));
        } elseif ($tanggal && is_array($tanggal)) {
            $satelit->whereBetween('waktu_pcr_sample_analyzed', [date('Y-m-d', strtotime($tanggal[0])), date('Y-m-d', strtotime($tanggal[count($tanggal) - 1]))]);
            $labkes->whereBetween('waktu_sample_valid', [date('Y-m-d', strtotime($tanggal[0])), date('Y-m-d', strtotime($tanggal[count($tanggal) - 1]))]);
        }
        if ($kota) {
            $satelit->where('pasien.kota_id', $kota);
            $labkes->where('pasien.kota_id', $kota);
        }
        return $satelit->count('pemeriksaansampel.kesimpulan_pemeriksaan') + $labkes->count('pemeriksaansampel.kesimpulan_pemeriksaan');
    }

    private function __getDate($date = 'Monthly')
    {
        $now = Carbon::now();
        switch ($date) {
            case 'Weekly':
                $weekStartDate = $now->startOfWeek()->format('Y-m-d');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d');
                break;
            case 'Monthly':
                $weekStartDate = $now->startOfMonth()->format('Y-m-d');
                $weekEndDate = $now->endOfMonth()->format('Y-m-d');
                break;
        }
        $start = new Carbon($weekStartDate);
        $end = new Carbon($weekEndDate);
        $days = $start->diff($end)->days;
        $blackoutDays = [];
        for ($i = 0; $i <= $days; $i++) {
            $date = $start;
            $blackoutDays[] = $date->format('Y-m-d');
            $start->addDays(1);
        }
        return $blackoutDays;
    }
}
