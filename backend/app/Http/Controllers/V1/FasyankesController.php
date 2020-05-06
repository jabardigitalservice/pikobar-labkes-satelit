<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Fasyankes;
use Illuminate\Http\Request;

class FasyankesController extends Controller
{
    public function listByProvinsi(Request $request)
    {
        $tipe = $request->get('tipe','rumah_sakit');
        $provinsi = $request->get('provinsi',null);
        $listFaskes = Fasyankes::where('tipe',$tipe);
        if(!$provinsi) {
            $listFaskes = $listFaskes->whereHas('kota', function($query) use ($provinsi){
                $query->where('provinsi_id', $provinsi);
            });
        }
        $listFaskes = $listFaskes->get();

        return response()->json($listFaskes);
    }

    public function show(Fasyankes $fasyankes)
    {
        return response()->json($fasyankes);
    }
}
