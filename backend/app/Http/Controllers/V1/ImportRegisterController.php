<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Imports\RegisterMandiriImport;
use App\Imports\RegisterRujukanImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportRegisterController extends Controller
{
    /**
     * Import Register Mandiri
     * 
     */
    public function importRegisterMandiri(Request $request)
    {       
        $this->importValidator($request)->validate();

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
        $this->importValidator($request)->validate();

        Excel::import(new RegisterRujukanImport, $request->file('register_file'));

        return response()->json([
            'status'=> 200,
            'message'=> 'Sukses import data.',
            'data'=> null
        ]);
    }

    private function importValidator(Request $request)
    {
        $extension = '';

        if ($request->hasFile('register_file')) {
            $extension = strtolower($request->file('register_file')->getClientOriginalExtension());
        }

        return Validator::make([
            'register_file'=> $request->file('register_file'),
            'extension'=> $extension
        ],[
            'register_file'=> 'required|file|max:2048',
            'extension'=> 'required|in:csv,xlsx,xls'
        ]);

        // OLD
        // $request->validate([
        //     'register_file'=> 'required|file|max:2048',
        //     'extension'=> 'required|in:csv,xlsx,xls'
        // ],[
        //     'register_file'=> $request->file('register_file'),
        //     'extension'=> strtolower($request->file('register_file')->getClientOriginalExtension())
        // ]);
    }
}
