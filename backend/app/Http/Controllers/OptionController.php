<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class OptionController extends Controller
{
    public function getRoles(Request $request)
    {
        $models = Role::select('id','roles_name as text')->get();
        return response()->json($models);
    }
}
