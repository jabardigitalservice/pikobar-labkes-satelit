<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sampel;
use Illuminate\Http\Request;

class DashboardVerifikasiController extends Controller
{
    /**
     * Jumlah Belum diverifikasi
     * 
     */
    public function getCountUnverify()
    {
        $unverifySampel = $this->counterQuery()
            ->whereIn('sampel_status', [
                'pcr_sample_analyzed'
            ])->count();

        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> $unverifySampel
        ]);
    }

    /**
     * Jumlah Terverifikasi
     * 
     */
    public function getCountVerified()
    {
        $verified = $this->counterQuery()
            ->whereHas('pemeriksaanSampel')
            ->where('sampel_status', '!=', 'sample_invalid')
            ->whereIn('sampel_status', ['sample_verified', 'sample_valid'])
            ->count();

        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> $verified
        ]);
    }

    protected function counterQuery()
    {
        return Sampel::query()
            ->whereHas('logs')
            ->whereHas('pemeriksaanSampel');
    }
}
