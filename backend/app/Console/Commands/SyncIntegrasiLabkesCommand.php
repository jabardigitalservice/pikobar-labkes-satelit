<?php

namespace App\Console\Commands;

use App\Models\Labkes\Sampel;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PemeriksaanSampel;
use App\Models\PengambilanSampel;
use App\Models\Register;
use App\Models\Sampel as AppSampel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncIntegrasiLabkesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-integrasi-labkes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi Data Aplikasi Labkes Setiap Jam 18:00';

    private $tanggal;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->tanggal = Carbon::now()->format('Y-m-d');
    }

    const CREATOR_USER_ID = 1;
    const LABKES_LAB_ID = 47;
    const STATUS_SAMPEL = 'pcr_sample_analyzed';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $records = Sampel::sampel('sample_valid')
                        ->whereDate('waktu_sample_valid', $this->tanggal)
                        ->selectCostum()
                        ->get();
        if ($this->checkDataToday()) {
            return;
        }
        foreach ($records as $record) {
            DB::beginTransaction();
            try {
                $record = $record->toArray();
                $register = $this->insertTabelRegister($record);
                $pasien = $this->insertTabelPasien($record);
                $pengambilanSampel = $this->insertTabelPengambilanSampel($record);
                $sampel = $this->insertTabelSampel($record, $register, $pengambilanSampel);
                $this->insertTabelPasienRegister($register, $pasien);
                $this->insertTabelPemeriksaanSampel($record, $sampel);
                DB::commit();
                Log::alert("Sinkronisasi Data Manlab Ke Satelit Berhasil dilakukan pada Tanggal $this->tanggal");
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
        }
    }

    private function insertTabelRegister($record)
    {
        $record['register_uuid'] = Str::uuid();
        $record['instansi_pengirim'] = $record['fasyankes_pengirim'];
        $record['instansi_pengirim_nama'] = $record['nama_rs'];
        $record['swab_ke'] = $record['kunjungan_ke'];
        $record['tanggal_swab'] = $record['tanggal_kunjungan'];
        $record['tanggal_swab'] = $record['tanggal_kunjungan'];
        $record['creator_user_id'] = self::CREATOR_USER_ID;
        $record['lab_satelit_id'] = self::LABKES_LAB_ID;
        return Register::create($record);
    }

    private function insertTabelPasien($record)
    {
        $record['kode_provinsi'] = $record['provinsi_id'];
        $record['nama_provinsi'] = $record['provinsi_nama'];
        $record['kode_kabupaten'] = $record['kota_id'];
        $record['nama_kabupaten'] = $record['kota_nama'];
        $record['kecamatan'] = $record['kecamatan_nama'];
        $record['kode_kecamatan'] = $record['kecamatan_id'];
        $record['nama_kecamatan'] = $record['kecamatan_nama'];
        $record['kelurahan'] = $record['kelurahan_nama'];
        $record['kode_kelurahan'] = $record['kelurahan_id'];
        $record['nama_kelurahan'] = $record['kelurahan_nama'];
        $record['lab_satelit_id'] = self::LABKES_LAB_ID;
        return Pasien::create($record);
    }

    private function insertTabelPengambilanSampel($record)
    {
        return PengambilanSampel::create([
            'sampel_diambil' => false,
            'sampel_diterima' => false,
            'diterima_dari_faskes' => false,
            'sampel_rdt' => false,
            'catatan' => null,
        ]);
    }

    private function insertTabelSampel($record, $register, $pengambilanSampel)
    {
        $record['register_id'] = $register->id;
        $record['pengambilan_sampel_id'] = $pengambilanSampel->id;
        $record['waktu_pcr_sample_analyzed'] = $record['waktu_sample_valid'];
        $record['created_at'] = $record['waktu_sample_taken'];
        $record['lab_satelit_id'] = self::LABKES_LAB_ID;
        $record['sampel_status'] = self::STATUS_SAMPEL;
        $record['creator_user_id'] = self::CREATOR_USER_ID;
        return AppSampel::create($record);
    }

    private function insertTabelPemeriksaanSampel($record, $sampel)
    {
        $record['sampel_id'] = $sampel->id;
        $record['user_id'] = self::CREATOR_USER_ID;
        $record['tanggal_input_hasil'] = $record['waktu_sample_valid'];
        return PemeriksaanSampel::create($record);
    }

    private function insertTabelPasienRegister($register, $pasien)
    {
        return PasienRegister::create([
            'pasien_id' => $pasien->id,
            'register_id' => $register->id,
        ]);
    }

    private function checkDataToday()
    {
        return AppSampel::whereDate('created_at', $this->tanggal)
                    ->where('lab_satelit_id', self::LABKES_LAB_ID)
                    ->exists();
    }
}
