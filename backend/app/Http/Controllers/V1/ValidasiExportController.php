<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sampel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ValidasiExportController extends Controller
{
    public function exportPDF(Request $request, Sampel $sampel)
    {
        abort_if(!$sampel->validFile, 404, "Belum ada file");

        $filePath = 'public/surat_hasil' . DIRECTORY_SEPARATOR . $sampel->validFile->getAttribute('original_name');

        $isExists = Storage::exists($filePath);

        abort_if(!$isExists, 404, "File tidak ditemukan.");

        return Storage::download($filePath, $sampel->validFile->original_name.'.pdf', [
            // "X-Suggested-Filename"=> $sampel->validFile->original_name.'.pdf',
            "Content-Type"=> "application/pdf",
            "Content-Description" => 'File Transfer',
            "Content-Disposition" => 'attachment;filename='.$sampel->validFile->original_name.'.pdf',
        ]);

    }

    
}
