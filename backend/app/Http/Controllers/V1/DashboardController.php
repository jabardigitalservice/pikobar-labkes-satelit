<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sampel;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function notifications(Request $request)
    {
        $user = $request->user();
        $data = [];
        $count = 0;
        if ($user->role_id == 4 || $user->role_id == 1) {
            $jumlah = Sampel::where('sampel_status','extraction_sample_reextract')->count();
            if ($jumlah) {
                $data[] = [
                    'link' => '/ekstraksi/dikembalikan',
                    'message' => "Ada $jumlah sampel yang dikembalikan",
                    'icon' => 'uil-flask',
                ];
                $count += $jumlah;
            }
        }
        return response()->json([
            'data' => $data,
            'count' => $count,
        ]);
    }
    public function tracking()
    {
        $count_by_status = Sampel::groupBy('sampel_status')->select('sampel_status', DB::raw('count(*) as total'))->pluck('total','sampel_status');
        $registration_incomplete = Sampel::whereNull('nomor_register')->count();
        $tracking = [
            'registration_incomplete' => $registration_incomplete,
            'waiting_sample' => @$count_by_status['waiting_sample'],
            'extraction' => @$count_by_status['sample_taken'] + @$count_by_status['extraction_sample_extracted'] + @$count_by_status['extraction_sample_reextract'],
            'pcr' => @$count_by_status['extraction_sample_sent'] + @$count_by_status['pcr_sample_received'],
            'verification' => @$count_by_status['pcr_sample_analyzed'],
            'validation' => @$count_by_status['sample_verified'],
            'done' => @$count_by_status['sample_valid'],
            'invalid' => @$count_by_status['sample_invalid'],
        ];

        return response()->json([
            'status' => $tracking,
        ]);
    }
    public function ekstraksi()
    {
        $count_by_status = Sampel::groupBy('sampel_status')->select('sampel_status', DB::raw('count(*) as total'))->pluck('total','sampel_status');
        $count_by_status['extraction_sent'] = 0
            + @$count_by_status['extraction_sample_sent']
            + @$count_by_status['pcr_sample_received']
            + @$count_by_status['pcr_sample_analyzed']
            + @$count_by_status['sample_verified']
            + @$count_by_status['sample_valid'];
        $count_by_status = $count_by_status->only(['extraction_sent','sample_taken','extraction_sample_extracted','extraction_sample_reextract']);
        $count_by_labs = Sampel::groupBy('lab_pcr_nama')->orderByRaw('count(*) desc')
            ->select('lab_pcr_nama', DB::raw('count(*) as total'))
            ->whereIn('sampel_status',['extraction_sample_sent','pcr_sample_received','pcr_sample_analyzed','sample_verified','sample_valid'])
            ->get();

        return response()->json([
            'status' => $count_by_status,
            'labs' => $count_by_labs,
        ]);
    }
    public function pcr(Request $request)
    {
        $user = $request->user();
        $query = Sampel::groupBy('sampel_status')
            ->select('sampel_status', DB::raw('count(*) as total'))
            ;
        if (!empty($user->lab_pcr_id)) {
            $query->where('lab_pcr_id', $user->lab_pcr_id);
        }
        $count_by_status = cctor($query)->pluck('total','sampel_status');
        $count_by_status = $count_by_status->only(['extraction_sample_sent','pcr_sample_received']);
        $counter = Sampel::leftJoin('pemeriksaansampel','pemeriksaansampel.sampel_id','=','sampel.id')
            ->whereIn('sampel_status', [
                'pcr_sample_analyzed',
                'sample_verified',
                'sample_valid',
            ]);
        $count_by_status['sample_valid'] = cctor($counter)->where(function($q) {
            $q->where('kesimpulan_pemeriksaan', '<>', 'inkonklusif')->orWhereNull('kesimpulan_pemeriksaan');
        })->count();
        $count_by_status['sample_inconclusive'] = cctor($counter)
            ->where('kesimpulan_pemeriksaan', 'inkonklusif')
            ->count();

        return response()->json([
            'status' => $count_by_status,
        ]);
    }
}
