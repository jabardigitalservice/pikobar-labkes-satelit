<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sampel;

class SampelController extends Controller
{

    public function cekNomorSampel(Request $request)
    {
        $user = $request->user();
        $sampel = Sampel::where('nomor_sampel', $request->nomor_sampel)->first();
        if (!$sampel) {
            return response()->json([
                'valid' => false,
                'error' => 'Nomor Sampel tidak ditemukan',
            ]);
        }
        if ($request->sampel_status && $sampel->sampel_status != $request->sampel_status) {
            return response()->json([
                'valid' => false,
                'error' => 'Status sampel sudah pada tahap '.($sampel->status ? $sampel->status->deskripsi : $sampel->sampel_status),
            ]);
        }
        if (!empty($user->lab_pcr_id)) {
            if ($sampel->lab_pcr_id != $user->lab_pcr_id) {
                return response()->json([
                    'valid' => false,
                    'error' => 'Sampel ini tidak diarahkan ke Lab Anda, namun ke Lab PCR di ' . $sampel->lab_pcr_nama. '.<br>Silakan hubungi Admin Ekstraksi (Labkesda Jabar)',
                ]);
            }
        }
        return response()->json([
            'valid' => true,
            'error' => '',
        ]);
    }
}
