<?php

namespace App\Http\Controllers\V1;

use App\Exports\SampelVerifiedExport;
use App\Http\Controllers\Controller;
use App\Models\Sampel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class ValidasiExportController extends Controller
{
    public function exportPDF(Request $request, Sampel $sampel)
    {
        $data['sampel'] = $sampel;
        $data['pasien'] = optional($sampel->register->pasiens())->first();
        $data['pemeriksaan_sampel'] = $sampel->pemeriksaanSampel;
        $data['validator'] = $sampel->validator;
        $data['last_pemeriksaan_sampel'] = $sampel->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first();
        $data['kop_surat'] = $this->getKopSurat();
        $data['tanggal_validasi'] = $this->formatTanggalValid($sampel);

        $pdf = PDF::loadView('pdf_templates.print_validasi', $data);

        $pdf->setPaper([0,0,609.4488,935.433]);

        return $pdf->stream('hasil_validasi_'.time().'.pdf');

        // return $pdf->download()->getOriginalContent();

        // return (new SampelVerifiedExport())
        //     ->download('sampel-validated-'.time().'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }

    public function getKopSurat()
    {
        $pathDirectory = 'public/kop_surat/kop-surat-lab-kes.png';

        $image = Storage::get($pathDirectory);

        return 'data:image/png;base64, ' . base64_encode($image);
    }

    private function formatTanggalValid(Sampel $sampel)
    {
        if (!$sampel->getAttribute('waktu_sample_valid')) {
            $tanggal = now();
            return $tanggal->day . ' ' . 
                $this->getNamaBulan($tanggal->month) . ' ' . 
                $tanggal->year;
        }

        return $sampel->waktu_sample_valid->day . ' ' . 
                $this->getNamaBulan($sampel->waktu_sample_valid->month) . ' ' . 
                $sampel->waktu_sample_valid->year;
    }

    public static function getNamaBulan(int $bulanKe)
    {
        $arrayNamaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
            'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        return $arrayNamaBulan[ $bulanKe - 1]; 
    }
}
