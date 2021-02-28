<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportValidationRequest;
use App\Imports\HasilPemeriksaanAkhirImport;
use App\Imports\HasilPemeriksaanImport;
use App\Imports\RegisterPerujukImport;
use App\Imports\RegisterSampelImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

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
        $import = new RegisterPerujukImport();
        return $this->importExcel($import, $request->file('register_file'));
    }

    public function importExcel($import, $file)
    {
        try {
            $import->import($file);
            return response()->json(['message' => 'Sukses import data.']);
        } catch (ValidationException $e) {
            $errors = [];
            foreach ($e->failures() as $key => $failure) {
                $errors[$key]['row'] = $failure->row();
                $errors[$key]['attribute'] = $failure->attribute();
                $errors[$key]['errors'] = $failure->errors();
            }
            return response()->json(['errors' => $errors], 422);
        }
    }

    public function importInputPemeriksaan(ImportValidationRequest $request)
    {
        $importer = new HasilPemeriksaanImport();
        Excel::import($importer, $request->file('register_file'));
        return response()->json([
            'status' => 200,
            'message' => 'Sukses membaca file import excel',
            'data' => $importer->data,
            'errors' => $importer->errors,
            'errors_count' => $importer->errors_count,
        ]);
    }
}
