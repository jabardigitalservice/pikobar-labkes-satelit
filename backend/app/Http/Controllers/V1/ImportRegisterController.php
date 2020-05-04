<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Imports\RegisterMandiriImport;
use App\Imports\RegisterRujukanImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportRegisterController extends Controller
{
    /**
     * Import Register Mandiri
     * 
     */
    public function importRegisterMandiri(Request $request)
    {        
        $request->validate([
            'register_file'=> 'required|file|mimes:xls,xlsx|max:2048'
        ],$request->only('register_file'));

        Excel::import(new RegisterMandiriImport, $request->file('register_file'));

        return response()->json([
            'status'=> 200,
            'message'=> 'Sukses import data.',
            'data'=> null
        ]);
    }

    /**
     * Import Register Rujukan
     */
    public function importRegisterRujukan(Request $request)
    {        
        $request->validate([
            'register_file'=> 'required|file|mimes:xls,xlsx|max:2048'
        ],$request->only('register_file'));

        Excel::import(new RegisterRujukanImport, $request->file('register_file'));

        return response()->json([
            'status'=> 200,
            'message'=> 'Sukses import data.',
            'data'=> null
        ]);
    }
}
