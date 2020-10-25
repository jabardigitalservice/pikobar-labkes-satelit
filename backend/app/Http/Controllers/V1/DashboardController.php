<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\Register;
use App\Models\Sampel;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function tracking()
    {
        $data['register'] = $this->__getRegistrasi();
        $data['sampel'] = $this->__getRegistrasi();
        $data['positif'] = $this->__getKesimpulanPemeriksaan('positif');
        $data['negatif'] = $this->__getKesimpulanPemeriksaan('negatif');
        $data['inkonklusif'] = $this->__getKesimpulanPemeriksaan('inkonklusif');
        $data['invalid'] = $this->__getKesimpulanPemeriksaan('invalid');
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    private function __getKesimpulanPemeriksaan($hasilPemeriksaan, $isChart = null, $date = null)
    {
        $user = Auth::user();
        $models = Sampel::leftJoin('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->leftJoin('register', 'sampel.register_id', 'register.id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'register.id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id')
            ->whereNull('register.deleted_at')
            ->where('sampel.sampel_status', 'pcr_sample_analyzed')
            ->where('register.lab_satelit_id', $user->lab_satelit_id)
            ->where('sampel.lab_satelit_id', $user->lab_satelit_id)
            ->where('pasien.lab_satelit_id', $user->lab_satelit_id)
            ->where('pemeriksaansampel.kesimpulan_pemeriksaan', $hasilPemeriksaan);
        if ($isChart) {
            switch ($isChart) {
                case 'Daily':
                    $models->whereDate('waktu_pcr_sample_analyzed', date('Y-m-d', strtotime(date('Y-m-' . $date))));
                    break;
                case 'Monthly':
                    $models->whereMonth('waktu_pcr_sample_analyzed', $date);
                    break;
                default:
                    break;
            }
        }
        return $models->count('pemeriksaansampel.kesimpulan_pemeriksaan');
    }

    private function __getRegistrasi()
    {
        $user = Auth::user();
        $models = Register::leftJoin('pasien_register', 'register.id', 'pasien_register.register_id')
            ->leftJoin('pasien', 'pasien.id', 'pasien_register.pasien_id')
            ->leftJoin('sampel', 'register.id', 'sampel.register_id')
            ->whereNull('sampel.deleted_at')
            ->where('register.lab_satelit_id', $user->lab_satelit_id)
            ->where('sampel.lab_satelit_id', $user->lab_satelit_id)
            ->where('pasien.lab_satelit_id', $user->lab_satelit_id)
            ->whereNull('sampel.deleted_at')
            ->count();
        return $models;
    }

    public function pasienDiperiksa()
    {
        $data = [];
        foreach (STATUSES as $key => $value) {
            $data[str_replace(' ', '_', strtolower($value))] = $this->__getPasienStatus($key);
        }
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }

    private function __getPasienStatus($statusPasien)
    {
        $user = Auth::user();
        return Register::leftJoin('pasien_register', 'register.id', 'pasien_register.register_id')
            ->leftJoin('pasien', 'pasien.id', 'pasien_register.pasien_id')
            ->leftJoin('sampel', 'register.id', 'sampel.register_id')
            ->whereNull('sampel.deleted_at')
            ->where('register.lab_satelit_id', $user->lab_satelit_id)
            ->where('sampel.lab_satelit_id', $user->lab_satelit_id)
            ->where('pasien.lab_satelit_id', $user->lab_satelit_id)
            ->where('register.status', $statusPasien)
            ->whereNull('sampel.deleted_at')
            ->count();
    }

    public function instansi_pengirim(Request $request)
    {
        $user = Auth::user();
        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 500);
        $type = $request->get('type', 'fasyankes');

        switch ($type) {
            case 'kota':
                $searchByTipe = 'nama_kabupaten';
                $models = Pasien::where('lab_satelit_id', $user->lab_satelit_id)
                    ->whereNotNull($searchByTipe);
                break;
            default:
                $searchByTipe = 'instansi_pengirim_nama';
                $models = Register::where('lab_satelit_id', $user->lab_satelit_id)
                    ->whereNotNull($searchByTipe);
                break;
        }

        $models = $models->select(DB::raw("upper($searchByTipe) as name"), DB::raw('count(*) as y'))
        ->orderBy('y', 'desc')
        ->groupBy('name');

        $count = count($models->get());
        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();
        return response()->json([
            'status' => 200,
            'data' => $models,
            'count' => $count,
        ]);
    }

    public function chartPcr(Request $request)
    {
        $tipe = $request->get('tipe', 'Daily');
        $data['labels'] = [];
        $data['positif'] = [];
        $data['negatif'] = [];
        $data['inkonklusif'] = [];
        $data['invalid'] = [];
        switch ($tipe) {
            case "Daily":
                $days = Carbon::parse(date('Y-m-d'))->daysInMonth;
                foreach (range(1, $days) as $row) {
                    $key = $row - 1;
                    $data['labels'][$key] = date('Y-m-d', strtotime(date('Y-m-' . $row)));
                    $data['positif'][$key] = $this->__getKesimpulanPemeriksaan('positif', 'Daily', $row);
                    $data['negatif'][$key] = $this->__getKesimpulanPemeriksaan('negatif', 'Daily', $row);
                    $data['inkonklusif'][$key] = $this->__getKesimpulanPemeriksaan('inkonklusif', 'Daily', $row);
                    $data['invalid'][$key] = $this->__getKesimpulanPemeriksaan('invalid', 'Daily', $row);
                }
                break;
            case "Monthly":
                foreach (range(0, count(MONTH) - 1) as $key) {
                    $bulan = $key;
                    ++$bulan;
                    $data['labels'][$key] = MONTH[$key];
                    $data['positif'][$key] = $this->__getKesimpulanPemeriksaan('positif', 'Monthly', $bulan);
                    $data['negatif'][$key] = $this->__getKesimpulanPemeriksaan('negatif', 'Monthly', $bulan);
                    $data['inkonklusif'][$key] = $this->__getKesimpulanPemeriksaan('inkonklusif', 'Monthly', $bulan);
                    $data['invalid'][$key] = $this->__getKesimpulanPemeriksaan('invalid', 'Monthly', $bulan);
                }
                break;
        }
        return response()->json([
            'result' => $data,
            'status' => 200,
        ]);
    }
}
