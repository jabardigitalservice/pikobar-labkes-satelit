<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EkstraksiController extends Controller
{
    public function getData(Request $request)
    {
        $dummy['data'] = array(
            (object) array(
                'pen_nomor_ekstraksi' => 1,
                'pen_noreg' => null,
                'pen_nik' => null,
                'pen_id_sampel'=>'111,112',
                'sampel' => [],
            ),
            
        );
        $dummy['count'] = 1;
        return response()->json($dummy);
    }

    public function getKirim(Request $request)
    {
        $dummy['data'] = array(
            (object) array(
                'pen_nomor_ekstraksi' => 2,
                'pen_noreg' => null,
                'pen_nik' => null,
                'pen_id_sampel'=>'111,112',
                'sampel' => [],
            ),
            
        );
        $dummy['count'] = 1;
        return response()->json($dummy);
    }
}
