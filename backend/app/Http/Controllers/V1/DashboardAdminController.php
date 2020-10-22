<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Labkes\DashboardCounter as DashboardCounterLabkes;
use App\Models\Labkes\Sampel as SampelLabkes;
use App\Models\Register;
use App\Models\Sampel as SampelSatelit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function tracking()
    {
        $sampelMasukSatelit = Register::leftJoin('pasien_register', 'register.id', 'pasien_register.register_id')
            ->leftJoin('pasien', 'pasien.id', 'pasien_register.pasien_id')
            ->leftJoin('sampel', 'register.id', 'sampel.register_id')
            ->whereNull('sampel.deleted_at')
            ->count();
        $sampelDiperiksaSatelit = $this->__getKesimpulanPemeriksaan();
        $data['total_masuk_sampel'] = DashboardCounterLabkes::where('nama', 'admin_sampel_total_sampel')->first()->total + $sampelMasukSatelit;
        $data['total_sampel_diperiksa'] = DashboardCounterLabkes::where('nama', 'pcr_hasil_pemeriksaan')->first()->total + $sampelDiperiksaSatelit;
        $data['rata_rata_waktu_pemeriksaan'] = $this->__getRataRataWaktuPemeriksaan();
        $data['total_sample_positif'] = DashboardCounterLabkes::where('nama', 'pasien_positif')->first()->total + $this->__getKesimpulanPemeriksaan('positif');
        $data['total_sample_negatif'] = DashboardCounterLabkes::where('nama', 'pasien_negatif')->first()->total + $this->__getKesimpulanPemeriksaan('negatif');
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    private function __getKesimpulanPemeriksaan($hasilPemeriksaan = null)
    {
        $models = SampelSatelit::leftJoin('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->leftJoin('register', 'sampel.register_id', 'register.id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'register.id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id')
            ->whereNull('register.deleted_at')
            ->where('sampel.sampel_status', 'pcr_sample_analyzed');
        if ($hasilPemeriksaan) {
            $models->where('pemeriksaansampel.kesimpulan_pemeriksaan', $hasilPemeriksaan);
        }
        return $models->count('pemeriksaansampel.kesimpulan_pemeriksaan');
    }

    private function __getRataRataWaktuPemeriksaan()
    {
        $satelit = SampelSatelit::selectRaw("DATE_PART('day', age(date(waktu_pcr_sample_analyzed), date(waktu_sample_taken)))::Int as days")
            ->whereNotNull('waktu_pcr_sample_analyzed')
            ->whereNotNull('waktu_sample_taken')
            ->whereRaw("date(waktu_pcr_sample_analyzed) > date(waktu_sample_taken)")
            ->pluck('days');

        $labkes = SampelLabkes::selectRaw("DATE_PART('day', age(date(waktu_pcr_sample_analyzed), date(waktu_sample_taken)))::Int as days")
            ->whereNotNull('waktu_pcr_sample_analyzed')
            ->whereNotNull('waktu_sample_taken')
            ->where('is_from_migration', false)
            ->whereRaw("date(waktu_pcr_sample_analyzed) > date(waktu_sample_taken)")
            ->pluck('days');

        $satelit = $satelit->sum() / $satelit->count();
        $labkes = $labkes->sum() / $labkes->count();
        return array_sum([$labkes, $satelit]) / 2;
    }

    public function chartHasilPemeriksaan(Request $request)
    {
        $tanggal = $request->get('tanggal_pemeriksaan');
        $kota = $request->get('kota');
        $data['positif'] = $this->__getChartHasilPemeriksaan('positif', $tanggal, $kota);
        $data['negatif'] = $this->__getChartHasilPemeriksaan('negatif', $tanggal, $kota);
        $data['lainnya'] = $this->__getChartHasilPemeriksaan('lainnya', $tanggal, $kota);
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    private function __getChartHasilPemeriksaan($hasilPemeriksaan, $tanggal, $kota)
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
        if ($tanggal) {
            $satelit->whereDate('waktu_pcr_sample_analyzed', date('Y-m-d', strtotime($tanggal)));
            $labkes->whereDate('waktu_sample_valid', date('Y-m-d', strtotime($tanggal)));
        }
        if ($kota) {
            $satelit->where('pasien.kota_id', $kota);
            $labkes->where('pasien.kota_id', $kota);
        }
        switch ($hasilPemeriksaan) {
            case 'positif':
                $satelit->where('pemeriksaansampel.kesimpulan_pemeriksaan', 'positif');
                $labkes->where('pemeriksaansampel.kesimpulan_pemeriksaan', 'positif');
                break;
            case 'negatif':
                $satelit->where('pemeriksaansampel.kesimpulan_pemeriksaan', 'negatif');
                $labkes->where('pemeriksaansampel.kesimpulan_pemeriksaan', 'negatif');
                break;
            default:
                $satelit->whereNotIn('pemeriksaansampel.kesimpulan_pemeriksaan', ['negatif', 'positif', null, '']);
                $labkes->whereNotIn('pemeriksaansampel.kesimpulan_pemeriksaan', ['negatif', 'positif', null, '']);
                break;
        }
        return $satelit->count('pemeriksaansampel.kesimpulan_pemeriksaan') + $labkes->count('pemeriksaansampel.kesimpulan_pemeriksaan');
    }

    public function chartTrendline(Request $request)
    {
        $tipe = $request->get('tipe', 'Weekly');
        $kota = $request->get('kota', null);
        $date = $this->__getDate($tipe);
        $data['positif'] = [];
        $data['negatif'] = [];
        $data['lainnya'] = [];
        $data['labels'] = [];
        foreach ($date as $item) {
            $data['positif'][] = $this->__getChartHasilPemeriksaan('positif', $item, $kota);
            $data['negatif'][] = $this->__getChartHasilPemeriksaan('negatif', $item, $kota);
            $data['lainnya'][] = $this->__getChartHasilPemeriksaan('lainnya', $item, $kota);
            switch ($tipe) {
                case 'Weekly':
                    $data['labels'][] = date('D', strtotime($item));
                    break;
                case 'Monthly':
                    $data['labels'][] = $item;
                break;
            }
        }
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    private function __getDate($date = 'Weekly')
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
