<?php

namespace App\Http\Controllers\V1;

use App\Exports\SampelValidatedExport;
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

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date'=> 'nullable', // 'date|date_format:Y-m-d',
            'end_date'=> 'nullable', // 'date|date_format:Y-m-d',
            'sampel_status'=> 'nullable'
        ]);

        $payload = [];

        if ($request->has('start_date')) {
            $payload['startDate'] = parseDate($request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $payload['endDate'] = parseDate($request->input('end_date'));
        }

        if ($request->has('sampel_status')) {
            $payload['sampelStatus'] = $request->input('sampel_status');
        }

        return (new SampelValidatedExport($payload))->download('sampel-valid-'.time().'.xlsx');
    }

    
}
