<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class FileDownloadController extends Controller
{
    public function download(Request $request)
    {
        $namaFile = $request->input('namaFile', '');
        $fullNameFile = $namaFile . '.xlsx';
        $storagePath = "template/satelit/$fullNameFile";
        abort_if(Storage::disk('s3')->missing($storagePath), 404, 'File not exists!');
        return Storage::disk('s3')->download($storagePath, $fullNameFile, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'inline; filename="' . $fullNameFile . '"'
        ]);
    }
}
