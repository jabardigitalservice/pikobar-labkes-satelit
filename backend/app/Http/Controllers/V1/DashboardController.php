<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
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
        $user = Auth::user();

        $sampel_masuk = Sampel::query();
        $positif = Sampel::join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->where('kesimpulan_pemeriksaan', 'positif');
        $negatif = Sampel::join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->where('kesimpulan_pemeriksaan', 'negatif');
        $inkonklusif = Sampel::join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->where('kesimpulan_pemeriksaan', 'inkonklusif');
        $invalid = Sampel::join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->where('kesimpulan_pemeriksaan', 'invalid');

        $sampel_masuk->where('lab_satelit_id', $user->lab_satelit_id);
        $positif->where('lab_satelit_id', $user->lab_satelit_id);
        $negatif->where('lab_satelit_id', $user->lab_satelit_id);
        $inkonklusif->where('lab_satelit_id', $user->lab_satelit_id);
        $invalid->where('lab_satelit_id', $user->lab_satelit_id);

        $register_otg = Register::where('lab_satelit_id', $user->lab_satelit_id)->where('status', 'otg')->count();
        $register_odp = Register::where('lab_satelit_id', $user->lab_satelit_id)->where('status', 'odp')->count();
        $register_pdp = Register::where('lab_satelit_id', $user->lab_satelit_id)->where('status', 'pdp')->count();
        $register_positif = Register::where('lab_satelit_id', $user->lab_satelit_id)->where('status', 'positif')->count();
        $register_tanpa_status = Register::where('lab_satelit_id', $user->lab_satelit_id)->where('status', 'tanpa status')->count();
        $register_instansi_pengirim = 0;

        $register = Register::where('lab_satelit_id', $user->lab_satelit_id)->count();
        $sampel_masuk = $sampel_masuk->count();
        $positif = $positif->count();
        $negatif = $negatif->count();
        $inkonklusif = $inkonklusif->count();
        $invalid = $invalid->count();

        $instansi_pengirim = Register::select(DB::raw('upper(instansi_pengirim) as name'), DB::raw('count(*) as y'))
            ->where('lab_satelit_id', $user->lab_satelit_id)
            ->groupBy('instansi_pengirim')
            ->orderBy('y', 'desc')
            ->whereNotNull('instansi_pengirim')
            ->get();
        $tracking = [
            'register' => $register,
            'sampel_masuk' => $sampel_masuk,
            'positif' => $positif,
            'negatif' => $negatif,
            'inkonklusif' => $inkonklusif,
            'invalid' => $invalid,
            'register_otg' => $register_otg,
            'register_odp' => $register_odp,
            'register_pdp' => $register_pdp,
            'register_positif' => $register_positif,
            'register_tanpa_status' => $register_tanpa_status,
            'register_instansi_pengirim' => $register_instansi_pengirim,
            'instansi_pengirim' => $instansi_pengirim,
        ];

        return response()->json([
            'status' => $tracking,
        ]);
    }

    public function instansi_pengirim(Request $request)
    {
        $user = Auth::user();
        $models = Register::where('lab_satelit_id', $user->lab_satelit_id)
            ->whereNotNull('instansi_pengirim');

        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 500);

        $count = count(Register::select(DB::raw('upper(instansi_pengirim_nama) as name'), DB::raw('count(*) as y'))
                ->where('lab_satelit_id', $user->lab_satelit_id)
                ->groupBy('name')
                ->orderBy('y', 'desc')
                ->whereNotNull('instansi_pengirim_nama')
                ->get());

        $models = $models->select(DB::raw('upper(instansi_pengirim_nama) as name'), DB::raw('count(*) as y'));
        $models = $models->orderBy('y', 'desc');
        $models = $models->groupBy('name');
        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        return response()->json([
            'data' => $models,
            'count' => $count,
        ]);
    }

    public function chartPcr(Request $request)
    {
        $user = Auth::user();
        $models = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
            ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id');
        if ($user->lab_satelit_id != null) {
            $models->where('lab_satelit_id', $user->lab_satelit_id);
        }
        $tipe = $request->get('tipe', 'Daily');
        $data = [];
        $data['label'] = [];
        $data['data'][0]['label'] = 'positif';
        $data['data'][0]['data'] = [];
        $data['data'][0]['backgroundColor'] = '#c41a1a';
        $data['data'][0]['borderColor'] = '#c41a1a';
        $data['data'][1]['label'] = 'negatif';
        $data['data'][1]['data'] = [];
        $data['data'][1]['backgroundColor'] = '#c4c2c2';
        $data['data'][1]['borderColor'] = '#c4c2c2';
        $data['data'][2]['label'] = 'inkonklusif';
        $data['data'][2]['data'] = [];
        $data['data'][2]['backgroundColor'] = '#403d3d';
        $data['data'][2]['borderColor'] = '#403d3d';
        $data['data'][3]['label'] = 'invalid';
        $data['data'][3]['data'] = [];
        $data['data'][3]['backgroundColor'] = '#32427b';
        $data['data'][3]['borderColor'] = '#32427b';
        switch ($tipe) {
            case "Daily":
                $days = Carbon::parse(date('Y-m-d'))->daysInMonth;
                $key = 0;
                foreach (range(1, $days) as $row) {
                    $data['label'][$key] = date('d', strtotime(date('Y-m-' . $row)));
                    $data['data'][0]['data'][$key] = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
                        ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
                        ->where('kesimpulan_pemeriksaan', 'positif')
                        ->whereDate('waktu_pcr_sample_analyzed', date('Y-m-d', strtotime(date('Y-m-' . $row))))
                        ->where('lab_satelit_id', $user->lab_satelit_id)
                        ->count();
                    $data['data'][1]['data'][$key] = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
                        ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
                        ->where('kesimpulan_pemeriksaan', 'negatif')
                        ->whereDate('waktu_pcr_sample_analyzed', date('Y-m-d', strtotime(date('Y-m-' . $row))))
                        ->where('lab_satelit_id', $user->lab_satelit_id)
                        ->count();
                    $data['data'][2]['data'][$key] = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
                        ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
                        ->where('kesimpulan_pemeriksaan', 'inkonklusif')
                        ->whereDate('waktu_pcr_sample_analyzed', date('Y-m-d', strtotime(date('Y-m-' . $row))))
                        ->where('lab_satelit_id', $user->lab_satelit_id)
                        ->count();
                    $data['data'][3]['data'][$key] = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
                        ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
                        ->where('kesimpulan_pemeriksaan', 'invalid')
                        ->whereDate('waktu_pcr_sample_analyzed', date('Y-m-d', strtotime(date('Y-m-' . $row))))
                        ->where('lab_satelit_id', $user->lab_satelit_id)
                        ->count();

                    $key++;
                }

                break;
            case "Monthly":
                $month = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $key = 0;
                foreach (range(0, count($month) - 1) as $row) {
                    $bulan = $row;
                    ++$bulan;
                    $data['label'][$key] = $month[$row];
                    $data['data'][0]['data'][$key] = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
                        ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
                        ->where('kesimpulan_pemeriksaan', 'positif')
                        ->where('lab_satelit_id', $user->lab_satelit_id)
                        ->whereMonth('waktu_pcr_sample_analyzed', $bulan)->count();
                    $data['data'][1]['data'][$key] = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
                        ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
                        ->where('kesimpulan_pemeriksaan', 'negatif')
                        ->where('lab_satelit_id', $user->lab_satelit_id)
                        ->whereMonth('waktu_pcr_sample_analyzed', $bulan)->count();
                    $data['data'][2]['data'][$key] = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
                        ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
                        ->where('kesimpulan_pemeriksaan', 'inkonklusif')
                        ->where('lab_satelit_id', $user->lab_satelit_id)
                        ->whereMonth('waktu_pcr_sample_analyzed', $bulan)->count();
                    $data['data'][3]['data'][$key] = Sampel::whereNotNull('waktu_pcr_sample_analyzed')
                        ->join('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
                        ->where('kesimpulan_pemeriksaan', 'invalid')
                        ->where('lab_satelit_id', $user->lab_satelit_id)
                        ->whereMonth('waktu_pcr_sample_analyzed', $bulan)->count();
                    $key++;
                }

                break;
        }
        return response()->json($data);
    }
}
