<?php

namespace App\Http\Controllers\V1;

use App\Exports\SampelVerifiedExport;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Sampel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ValidasiExportController extends Controller
{
    public function exportPDF(Request $request, Sampel $sampel)
    {
        abort_if(!$sampel->validFile, 404, "Belum ada file");

        $filePath = 'public/surat_hasil' . DIRECTORY_SEPARATOR . $sampel->validFile->getAttribute('original_name');

        $isExists = Storage::exists($filePath);

        abort_if(!$isExists, 404, "File tidak ditemukan.");

        return Storage::download($filePath);

        // $pdfFile = $this->createPDF($sampel);

        // $fileStored = $this->putToStorage($sampel, $pdfFile);

        // return $fileStored;

        // return (new SampelVerifiedExport())
        //     ->download('sampel-validated-'.time().'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    
}
