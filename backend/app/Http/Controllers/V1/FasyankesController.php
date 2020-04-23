<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Fasyankes;
use Illuminate\Http\Request;

class FasyankesController extends Controller
{
    public function listByProvinsi($provinsi = 32)
    {
        $listFaskes = Fasyankes::whereHas('kota', function($query) use ($provinsi){
            $query->where('provinsi_id', $provinsi);
        })
        ->get();

        return response()->json($listFaskes);
    }
}
