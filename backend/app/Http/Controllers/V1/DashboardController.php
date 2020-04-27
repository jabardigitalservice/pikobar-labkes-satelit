<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sampel;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function tracking()
    {
        $count_by_status = Sampel::groupBy('sampel_status')->select('sampel_status', DB::raw('count(*) as total'))->pluck('total','sampel_status');
        $registration_incomplete = Sampel::whereNull('nomor_register')->count();
        $tracking = [
            'registration_incomplete' => $registration_incomplete,
            'waiting_sample' => $count_by_status['waiting_sample'],
            'extraction' => $count_by_status['sample_taken'] + $count_by_status['extraction_sample_extracted'] + $count_by_status['extraction_sample_reextract'],
            'pcr' => $count_by_status['extraction_sample_sent'] + $count_by_status['pcr_sample_received'],
            'verification' => $count_by_status['pcr_sample_analyzed'],
            'validation' => $count_by_status['sample_verified'],
            'done' => $count_by_status['sample_valid'],
        ];

        return response()->json([
            'status' => $tracking,
        ]);
    }
    public function ekstraksi()
    {
        $count_by_status = Sampel::groupBy('sampel_status')->select('sampel_status', DB::raw('count(*) as total'))->pluck('total','sampel_status');
        $count_by_status['extraction_sent'] = 0
            + $count_by_status['extraction_sample_sent']
            + $count_by_status['pcr_sample_received']
            + $count_by_status['pcr_sample_analyzed']
            + $count_by_status['sample_verified']
            + $count_by_status['sample_valid'];
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
}
