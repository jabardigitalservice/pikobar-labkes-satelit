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
        $response = $pelaporan->pendaftar_rdt($request->get('keyword'))->json();
        return response()->json($response, 200);
    }
}
