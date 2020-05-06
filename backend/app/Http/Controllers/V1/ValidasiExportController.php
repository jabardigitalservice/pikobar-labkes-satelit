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

        $sampel->update([
            'counter_print_hasil'=> ($sampel->getAttribute('counter_print_hasil') + 1)
        ]);

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
            'tanggal_registrasi_start'=> 'nullable', // 'date|date_format:Y-m-d',
            'tanggal_registrasi_end'=> 'nullable', // 'date|date_format:Y-m-d',
            'kesimpulan_pemeriksaan'=> 'nullable',
            'fasyankes'=> 'nullable|exists:fasyankes,id',
            'kota_domisili'=> 'nullable|exists:kota,id',
        ]);

        $payload = [];

        if ($request->has('tanggal_registrasi_start') && $request->input('tanggal_registrasi_start')) {
            $payload['startDate'] = parseDate($request->input('tanggal_registrasi_start'));
        }

        if ($request->has('tanggal_registrasi_end') && $request->input('tanggal_registrasi_end')) {
            $payload['endDate'] = parseDate($request->input('tanggal_registrasi_end'));
        }

        if ($request->has('kesimpulan_pemeriksaan')) {
            $payload['kesimpulanHasil'] = $request->input('kesimpulan_pemeriksaan');
        }

        if ($request->has('fasyankes')) {
            $payload['fasyankes_id'] = $request->input('fasyankes');
        }

        if ($request->has('kota_domisili')) {
            $payload['kota_id'] = $request->input('kota_domisili');
        }

        if ($request->has('kesimpulan_pemeriksaan')) {
            $payload['kesimpulan_pemeriksaan'] = $request->input('kesimpulan_pemeriksaan');
        }

        switch ($request->input('type')) {
            case 'validated':
                $payload['sampelStatus'] = 'sample_valid';
                break;
            
            default:
                $payload['sampelStatus'] = 'sample_verified';
                break;
        }

        return (new SampelValidatedExport($payload))->download('sampel-valid-'.time().'.xlsx');
    }

    
}
