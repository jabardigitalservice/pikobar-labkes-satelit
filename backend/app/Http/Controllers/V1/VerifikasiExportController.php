<?php

namespace App\Http\Controllers\V1;

use App\Exports\SampelVerifiedExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifikasiExportController extends Controller
{
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
            case 'verified':
                $payload['sampelStatus'] = 'sample_verified';
                break;
            
            default:
                $payload['sampelStatus'] = 'pcr_sample_analyzed';
                break;
        }

        // \Log::debug('payload', $payload);

        return (new SampelVerifiedExport($payload))->download('sampel-verifikasi-'.time().'.xlsx');
    }
}
