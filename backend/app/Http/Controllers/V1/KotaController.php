<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Negara;
use App\Models\Provinsi;

class KotaController extends Controller
{
    public function listProvinsi()
    {
        return response()->json(Provinsi::select('id', 'nama')->orderBy('nama')->get());
    }

    public function listKota($provinsi = 32)
    {
        return response()->json(Kota::select('id', 'nama')->orderBy('nama')->whereProvinsiId($provinsi)->get());
    }

    public function show(Kota $kota)
    {
        return response()->json($kota);
    }

    public function listKecamatan($kota)
    {
        return response()->json(Kecamatan::select('id', 'nama')->orderBy('nama')->whereKotaId($kota)->get());

    }

    public function listKelurahan($kecamatan)
    {
        return response()->json(Kelurahan::select('id', 'nama')->orderBy('nama')->whereKecamatanId($kecamatan)->get());

    }

    public function listNegara()
    {
        return response()->json(Negara::orderBy('nama')->get());
    }
}
