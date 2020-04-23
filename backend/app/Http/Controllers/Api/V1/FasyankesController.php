<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fasyankes;
use Illuminate\Http\Request;

class FasyankesController extends Controller
{
    public function listByProvinsi($provinsi = 32)
    {
        // return response()->json(Fasyankes::whereProvinsiId($provinsi)->get());
    }
}
