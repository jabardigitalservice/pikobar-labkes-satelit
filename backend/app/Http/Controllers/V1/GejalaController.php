<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Gejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    public function getListMasterGejala()
    {
        return response()->json(Gejala::whereIsActive(true)->get());
    }

    public function show(Gejala $gejala)
    {
        return response()->json($gejala);
    }
}
