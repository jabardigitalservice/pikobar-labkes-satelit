<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sampel;
use Illuminate\Http\Request;

class DashboardValidasiController extends Controller
{
    protected function counterQuery()
    {
        return Sampel::query()
            ->whereHas('logs')
            ->whereHas('pemeriksaanSampel');
    }

    /**
     * Jumlah belum divalidasi
     * 
     */
    public function getCountUnvalidate()
    {
        $unvalidate = $this->counterQuery()
            ->where('sampel_status', '!=', 'sample_invalid')
            ->whereIn('sampel_status', ['sample_verified'])
            ->count();

        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> $unvalidate
        ]);
    }

    /**
     * Jumlah tervalidasi
     * 
     */
    public function getCountValidated()
    {
        $validated = $this->counterQuery()
            ->where('sampel_status', '!=', 'sample_invalid')
            ->whereIn('sampel_status', ['sample_valid'])
            ->count();

        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> $validated
        ]);
    }
}
