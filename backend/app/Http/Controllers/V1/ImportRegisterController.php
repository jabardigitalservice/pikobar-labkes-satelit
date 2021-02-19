<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportValidationRequest;
use App\Imports\HasilPemeriksaanAkhirImport;
use App\Imports\RegisterSampelImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportRegisterController extends Controller
{
    public function importRegisterSampel(ImportValidationRequest $request)
    {
        Excel::import(new RegisterSampelImport(), $request->file('register_file'));
        return response()->json(['message' => 'Sukses import data.']);
    }

    public function importHasilPemeriksaan(ImportValidationRequest $request)
    {
        Excel::import(new HasilPemeriksaanAkhirImport(), $request->file('register_file'));
        return response()->json(['message' => 'Sukses import data.']);
    }

    public function importRegisterPerujuk(ImportValidationRequest $request)
    {
        Excel::import(new RegisterPerujukImport(), $request->file('register_file'));
        return response()->json(['message' => 'Sukses import data.']);
    }
}
