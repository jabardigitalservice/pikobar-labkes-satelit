<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Kota;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    public function listKota($provinsi = 32)
    {
        return response()->json(Kota::whereProvinsiId($provinsi)->get());
    }
}
