<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\FasyankesTerbanyak;
use App\Models\KotaTerbanyak;
use App\Models\Labkes\DashboardCounter as DashboardCounterLabkes;
use App\Models\Labkes\Sampel as SampelLabkes;
use App\Models\LabSatelit;
use App\Models\Register as RegisterSatelit;
use App\Models\Sampel as SampelSatelit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function tracking()
    {
        $sampelMasukSatelit = RegisterSatelit::leftJoin('pasien_register', 'register.id', 'pasien_register.register_id')
            ->leftJoin('pasien', 'pasien.id', 'pasien_register.pasien_id')
            ->leftJoin('sampel', 'register.id', 'sampel.register_id')
            ->whereNull('sampel.deleted_at')
            ->count();
        $sampelDiperiksaSatelit = $this->__getKesimpulanPemeriksaan();
        $data['total_masuk_sampel'] = DashboardCounterLabkes::where('nama', 'tracking_progress_sampel')->first()->total + $sampelMasukSatelit;
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

        $satelit = $satelit->count() ? $satelit->sum() / $satelit->count() : $satelit->count();
        $labkes = $labkes->sum() / $labkes->count();
        return array_sum([$labkes, $satelit]) / 2;
    }

    public function chartHasilPemeriksaan(Request $request)
    {
        $tanggal = $request->get('tanggal_pemeriksaan', null);
        $kota = $request->get('kota', null);
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
                $satelit->whereNotNull('pemeriksaansampel.kesimpulan_pemeriksaan');
                $satelit->whereNotIn('pemeriksaansampel.kesimpulan_pemeriksaan', ['negatif', 'positif']);
                $labkes->whereNotNull('pemeriksaansampel.kesimpulan_pemeriksaan');
                $labkes->whereNotIn('pemeriksaansampel.kesimpulan_pemeriksaan', ['negatif', 'positif']);
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

    public function chartHasilPemeriksaanByKota(Request $request)
    {
        $tipe = $request->get('tipe', 'Weekly');
        $date = $this->__getDate($tipe);
        $kota = KotaTerbanyak::where('tipe', $tipe)->orderBy('total', 'desc')->get();

        $data['positif'] = [];
        $data['negatif'] = [];
        $data['lainnya'] = [];
        $data['labels'] = [];
        foreach ($kota as $item) {
            $data['positif'][] = $this->__getChartHasilPemeriksaan('positif', $date, $item->kota_id);
            $data['negatif'][] = $this->__getChartHasilPemeriksaan('negatif', $date, $item->kota_id);
            $data['lainnya'][] = $this->__getChartHasilPemeriksaan('lainnya', $date, $item->kota_id);
            $data['labels'][] = $item->nama;
        }
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    public function chartRegisterByFasyankes(Request $request)
    {
        $tipe = $request->get('tipe', 'Weekly');
        $date = $request->get('tanggal_pemeriksaan', null) ? $request->get('tanggal_pemeriksaan') : $this->__getDate($tipe);
        $fasyankes = FasyankesTerbanyak::where('tipe', $tipe)->orderBy('total', 'desc')->get();
        $kota = $request->get('kota', null);
        $data['data'] = [];
        $data['labels'] = [];
        if ($date && !is_array($date)) {
            $fasyankes = $this->__getFasyankes($date, $kota);
        } elseif ($kota) {
            $fasyankes = $this->__getFasyankes($date, $kota);
        }
        foreach ($fasyankes as $item) {
            $data['data'][] = $item->total;
            $data['labels'][] = $item->nama;
        }

        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    private function __getFasyankes($date, $kota)
    {
        $fasyankes = [];
        $models = LabSatelit::all();
        $total = $this->__getLabkes($date, $kota);
        $fasyankes[] = (object) [
            'id' => 999,
            'nama' => 'Labkes',
            'tipe' => 'Weekly',
            'total' => $total,
        ];
        foreach ($models as $row) {
            $total = $this->__getLabSatelit($date, $row->id, $kota);
            $fasyankes[] = (object) [
                'id' => $row->id,
                'nama' => $row->nama,
                'tipe' => 'Weekly',
                'total' => $total,
            ];
        }
        return collect($fasyankes)->SortByDesc('total')->slice(0, 5);
    }

    private function __getLabSatelit($tanggal, $lab_satelit_id, $kota)
    {
        $satelit = SampelSatelit::leftJoin('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->leftJoin('register', 'sampel.register_id', 'register.id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'register.id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id')
            ->whereNull('register.deleted_at')
            ->where('sampel.sampel_status', 'pcr_sample_analyzed');
        if ($tanggal && !is_array($tanggal)) {
            $satelit->whereDate('waktu_pcr_sample_analyzed', date('Y-m-d', strtotime($tanggal)));
        } elseif ($tanggal && is_array($tanggal)) {
            $satelit->whereBetween('waktu_pcr_sample_analyzed', [date('Y-m-d', strtotime($tanggal[0])), date('Y-m-d', strtotime($tanggal[count($tanggal) - 1]))]);
        }
        if ($lab_satelit_id) {
            $satelit->where('pasien.lab_satelit_id', $lab_satelit_id);
        }
        if ($kota) {
            $satelit->where('pasien.kota_id', $kota);
        }
        return $satelit->count('pemeriksaansampel.kesimpulan_pemeriksaan');
    }

    private function __getLabkes($tanggal, $kota)
    {
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
        if ($kota) {
            $labkes->where('pasien.kota_id', $kota);
        }
        if ($tanggal && !is_array($tanggal)) {
            $labkes->whereDate('waktu_sample_valid', date('Y-m-d', strtotime($tanggal)));
        } elseif ($tanggal && is_array($tanggal)) {
            $labkes->whereBetween('waktu_sample_valid', [date('Y-m-d', strtotime($tanggal[0])), date('Y-m-d', strtotime($tanggal[count($tanggal) - 1]))]);
        }
        return $labkes->count('pemeriksaansampel.kesimpulan_pemeriksaan');
    }
}
