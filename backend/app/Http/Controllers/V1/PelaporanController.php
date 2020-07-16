<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\PelaporanService;
use Illuminate\Http\Request;

class PelaporanController extends Controller
{
    public function fetch_data(Request $request)
    {
        $pelaporan = new PelaporanService;
        $response = $pelaporan->pendaftar_rdt($request->get('keyword'), $request->get('limit', 20))->json();
        return response()->json($response, 200);
    }
}
