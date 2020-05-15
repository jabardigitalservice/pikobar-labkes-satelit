<?php

namespace App\Listeners;

use App\Events\SampelValidatedEvent;
use App\Models\Ekstraksi;
use App\Models\File;
use App\Models\Pasien;
use App\Models\PemeriksaanSampel;
use App\Models\Register;
use App\Models\Sampel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Carbon;

class CreateSuratHasilListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SampelValidatedEvent  $event
     * @return void
     */
    public function handle(SampelValidatedEvent $event)
    {
        $sampel = $event->sampel;

        $pdfFile = $this->createPDF($sampel);

        $this->putToStorage($sampel, $pdfFile);
    }

    public function putToStorage(Sampel $sampel, $file)
    {
        $timestamp = now()->timestamp;

        $storagePath = 'public/surat_hasil';
        $fileName = "SURAT_HASIL_{$sampel->nomor_sampel}_{$timestamp}.pdf";
        $filePath = $storagePath . DIRECTORY_SEPARATOR . $fileName;

        DB::beginTransaction();
        try {

            if (!Storage::exists($storagePath)) {
                Storage::makeDirectory($storagePath);
            }

            $fileStored = Storage::put($filePath, $file);
            $newFile = null;

            if ($fileStored) {
                $dataFile['mime_type'] = 'application/pdf';
                $dataFile['extension'] = 'pdf';
                $dataFile['original_name'] = $fileName;

                // $newFile = $sampel->validFile()->create($dataFile);
                if ($newFile = File::create($dataFile)) {
                    $sampel->update([
                        'valid_file_id'=> $newFile->id
                    ]);
                }
            }

            
            DB::commit();

            return;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        
    }

    public function createPDF(Sampel $sampel)
    {
        $data['sampel'] = $sampel;
        $data['pasien'] = $sampel->register ? optional($sampel->register->pasiens())->first() : null;
        $data['pemeriksaan_sampel'] = $sampel->pemeriksaanSampel;
        $data['validator'] = $sampel->validator;
        $data['last_pemeriksaan_sampel'] = $sampel->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first();
        $data['kop_surat'] = $this->getKopSurat();
        $data['tanggal_validasi'] = $this->formatTanggalValid($sampel);
        $data['tanggal_lahir_pasien'] = $data['pasien'] ? $this->getTanggalLahir($data['pasien']) : null;
        $data['umur_pasien'] = $data['pasien'] ? $this->getUmurPasien($data['pasien']->tanggal_lahir) : null;
        // $data['umur_pasien'] = $data['pasien'] ? Carbon::parse($data['pasien']->tanggal_lahir)->age : null;
        $data['last_pemeriksaan_sampel']['hasil_deteksi_terkecil'] = $this->getHasilDeteksiTerkecil($data['last_pemeriksaan_sampel']);
        $data['register'] = $sampel->register ?? null;
        $data['tanggal_periksa'] =  $sampel->ekstraksi ? $this->formatTanggalKunjungan($sampel->ekstraksi) : '-';

        $pdf = PDF::loadView('pdf_templates.print_validasi', $data);

        $pdf->setPaper([0,0,609.4488,935.433]);

        // return $pdf->stream('hasil_validasi_'.time().'.pdf');

        return $pdf->download()->getOriginalContent();
    }

    public function getKopSurat()
    {
        $pathDirectory = 'kop_surat/kop-surat-labkesda.png';

        $image = public_path($pathDirectory);

        abort_if(!file_exists($image), 500, 'File not exists!');

        $imageContent = file_get_contents($image);

        return 'data:image/png;base64, ' . base64_encode($imageContent);
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

    private function getTanggalLahir(Pasien $pasien)
    {
        return $pasien->tanggal_lahir->day . ' ' . 
                $this->getNamaBulan($pasien->tanggal_lahir->month) . ' ' . 
                $pasien->tanggal_lahir->year;
    }

    public static function getNamaBulan(int $bulanKe)
    {
        $arrayNamaBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
            'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        return $arrayNamaBulan[ $bulanKe - 1]; 
    }

    /**
     * The job failed to process.
     *
     * @param  SampelValidatedEvent  $event
     * @param  Exception  $exception
     * @return void
     */
    public function failed(SampelValidatedEvent $event, $exception)
    {
        $event->sampel->update([
            'sampel_status'=> 'sample_verified',
            'valid_file_id'=> null
        ]);
        
    }

    private function getHasilDeteksiTerkecil(PemeriksaanSampel $hasil)
    {
        return collect($hasil['hasil_deteksi_parsed'])
            ->whereNotNull('ct_value')
            // ->where('target_gen', '!=', 'IC')
            ->sortBy('ct_value')->first();
    }

    private function getUmurPasien(Carbon $tanggalLahir)
    {
        return $tanggalLahir->diff(Carbon::now())->format('%y Thn %m Bln %d Hari');
    }

    private function formatTanggalKunjungan(Ekstraksi $ekstraksi)
    {
        if (!$ekstraksi->getAttribute('tanggal_mulai_ekstraksi')) {
            $tanggal = now();
            return '-';
        }

        return $ekstraksi->tanggal_mulai_ekstraksi->day . ' ' . 
                $this->getNamaBulan($ekstraksi->tanggal_mulai_ekstraksi->month) . ' ' . 
                $ekstraksi->tanggal_mulai_ekstraksi->year;
    }
}
