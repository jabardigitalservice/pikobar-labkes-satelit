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
        // if ($this->checkDataToday()) {
        //     return;
        // }
        $start_time = Carbon::now();
        $records = Sampel::sampel('sample_valid')
                        // ->whereDate('waktu_sample_valid', $this->tanggal)
                        ->limit(1)
                        ->skip(2)
                        ->selectCostum()
                        ->get();
        $totalData = $records->count();
        foreach ($records->chunk(1000) as $chunk) {
            foreach ($chunk->toArray() as $record) {
                DB::beginTransaction();
                try {
                    $register = $this->insertTabelRegister($record);
                    $pasien = $this->insertTabelPasien($record);
                    $pengambilanSampel = $this->insertTabelPengambilanSampel($record);
                    $sampel = $this->insertTabelSampel($record, $register, $pengambilanSampel);
                    $this->insertTabelPasienRegister($register, $pasien);
                    $this->insertTabelPemeriksaanSampel($record, $sampel);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                    Log::alert($th);
                    throw $th;
                }
            }
        }
        $end_time = Carbon::now();
        $totalDuration = $start_time->diff($end_time)->format('%H:%I:%S') . " Menit";
        Log::alert("Sinkronisasi Data Manlab Ke Satelit pada tanggal $this->tanggal Berhasil dilakukan \n
        Detail: \n jumlah data: $totalData \n Durasi : $totalDuration");
    }

    private function insertTabelRegister($record)
    {
        $record['register_uuid'] = Str::uuid();
        $record['creator_user_id'] = self::CREATOR_USER_ID;
        $record['lab_satelit_id'] = self::LABKES_LAB_ID;
        return Register::create($record);
    }

    private function insertTabelPasien($record)
    {
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
