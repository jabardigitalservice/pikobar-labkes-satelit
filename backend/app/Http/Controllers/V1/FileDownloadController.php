<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;

class FileDownloadController extends Controller
{
    public function download(Request $request)
    {
        $namaFile = $request->namaFile;
        $path = public_path() . "/format/$namaFile.xlsx";
        abort_if(!file_exists($path), 500, 'File not exists!');
        return response()->download($path, $namaFile . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"'
        ]);
    }
}
