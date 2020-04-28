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
            'start_date'=> 'date|date_format:Y-m-d',
            'end_date'=> 'date|date_format:Y-m-d',
            'sampel_status'=> 'nullable'
        ]);

        $payload = [];

        if ($request->has('start_date')) {
            $payload['startDate'] = $request->input('start_date');
        }

        if ($request->has('end_date')) {
            $payload['endDate'] = $request->input('end_date');
        }

        if ($request->has('sampel_status')) {
            $payload['sampelStatus'] = $request->input('sampel_status');
        }

        return (new SampelVerifiedExport($payload))->download('sampel-verifikasi-'.time().'.xlsx');
    }
}
