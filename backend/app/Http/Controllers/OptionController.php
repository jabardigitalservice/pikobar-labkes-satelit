<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Models\LabPCR;

class OptionController extends Controller
{
    public function getRoles(Request $request)
    {
        $models = Role::select('id','roles_name as text')->get();
        return response()->json($models);
    }
    public function getLabPCR(Request $request)
    {
        $models = LabPCR::select('id','nama as text')->get();
        return response()->json($models);
    }
}
