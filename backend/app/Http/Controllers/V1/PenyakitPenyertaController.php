<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\PenyakitPenyerta;
use Illuminate\Http\Request;

class PenyakitPenyertaController extends Controller
{
    public function getListMaster()
    {
        return response()->json(PenyakitPenyerta::whereIsActive(true)->get());
    }

    public function show(PenyakitPenyerta $penyakitPenyerta)
    {
        return response()->json($penyakitPenyerta);
    }
}
