<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;

class SampelController extends Controller
{

    public function cekNomorSampel(Request $request)
    {
        $user = $request->user();
        $register = Register::where('nomor_sampel', $request->nomor_sampel)->first();
        if (!$register) {
            return response()->json([
                'valid' => false,
                'error' => 'Nomor Sampel tidak ditemukan',
            ]);
        }
        if ($register->register_status != $request->register_status) {
            return response()->json([
                'valid' => false,
                'error' => 'Status sampel sudah pada tahap '.($register->status ? $register->status->deskripsi : $register->register_status),
            ]);
        }
        if (!empty($user->lab_pcr_id)) {
            if ($register->lab_pcr_id != $user->lab_pcr_id) {
                return response()->json([
                    'valid' => false,
                    'error' => 'Sampel ini tidak diarahkan ke Lab Anda, namun ke Lab PCR di ' . $register->lab_pcr_nama. '.<br>Silakan hubungi Admin Ekstraksi (Labkesda Jabar)',
                ]);
            }
        }
        return response()->json([
            'valid' => true,
            'error' => '',
        ]);
    }
    
}
