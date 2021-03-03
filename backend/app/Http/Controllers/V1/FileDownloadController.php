<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FileDownloadController extends Controller
{
    public function download(Request $request)
    {
        $namaFile = $request->input('namaFile', '');
        $fullNameFile = $this->getFullNameFile($namaFile);
        $storagePath = $this->getStoragePathFile($namaFile, $fullNameFile);
        abort_if(Storage::disk('s3')->missing($storagePath), 422, 'File not exists!');
        return Storage::disk('s3')->download($storagePath, $fullNameFile, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'inline; filename="' . $fullNameFile . '"'
        ]);
    }

    private function getFullNameFile($namaFile)
    {
        return $namaFile . '.xlsx';
    }

    private function getStoragePathFile($namaFile, $fullNameFile)
    {
        $storagePath = "template/satelit/" . $fullNameFile;
        if (in_array($namaFile, ['fasyankes', 'labSatelit', 'wilayah'])) {
            $storagePath = "template/data_master/" . $fullNameFile;
        }
        return $storagePath;
    }
}
