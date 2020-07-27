<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;

class KotaController extends Controller
{
    public function listProvinsi()
    {
        return response()->json(Provinsi::all());
    }

    public function listKota($provinsi)
    {
        return response()->json(Kota::whereProvinsiId($provinsi)->get());
    }

    public function show(Kota $kota)
    {
        return response()->json($kota);
    }

    public function listKecamatan($kota)
    {
        return response()->json(Kecamatan::whereKabupatenId($kota)->get());

    }

    public function listKelurahan($kecamatan)
    {
        return response()->json(Kelurahan::whereKecamatanId($kecamatan)->get());

    }
}
