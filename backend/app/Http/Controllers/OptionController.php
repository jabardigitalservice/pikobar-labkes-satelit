<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Models\LabPCR;
use App\Models\Validator;
use App\Models\JenisSampel;
use App\Models\LabSatelit;

class OptionController extends Controller
{
    public function getRoles(Request $request)
    {
        $models = Role::select('id', 'roles_name as text')->get();
        return response()->json($models);
    }
    public function getLabPCR(Request $request)
    {
        $models = LabPCR::select('id', 'nama as text')->get();
        return response()->json($models);
    }
    public function getLabSatelit(Request $request)
    {
        $models = LabSatelit::select('id', 'nama as text')->get();
        return response()->json($models);
    }
    public function getValidator(Request $request)
    {
        $models = Validator::selectRaw('id, concat(nama, \' | NIP \', nip) as text')->get();
        return response()->json($models);
    }
    public function getJenisSampel(Request $request)
    {
        $models = JenisSampel::select('id', 'nama as text')->get();
        return response()->json($models);
    }
}
