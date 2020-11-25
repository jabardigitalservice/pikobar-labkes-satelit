<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Fasyankes;
use Illuminate\Http\Request;

class FasyankesController extends Controller
{
    public function listByProvinsi(Request $request)
    {
        $listFaskes = Fasyankes::where('tipe', $request->get('tipe'))->get();
        return response()->json($listFaskes);
    }

    public function show(Fasyankes $fasyankes)
    {
        return response()->json($fasyankes);
    }
}
