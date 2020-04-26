<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;

class SampelController extends Controller
{

    public function cekNomorSampel(Request $request)
    {
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
        return response()->json([
            'valid' => true,
            'error' => '',
        ]);
    }
    
}
