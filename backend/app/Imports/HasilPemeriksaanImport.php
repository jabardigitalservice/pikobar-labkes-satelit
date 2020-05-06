<?php

namespace App\Imports;

use App\Models\Kota;
use App\Models\Pasien;
use App\Models\Register;
use App\Models\Sampel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Log;

class HasilPemeriksaanImport implements ToCollection, WithHeadingRow
{
    public $data;
    public $errors;
    public $errors_count = 0;

    public function collection(Collection $rows)
    {
        $this->data = [];
        $this->errors = [];
        DB::beginTransaction();
        try {
            foreach ($rows as $key => $row) {
                if (!$row->get('no')) {
                    continue;
                }
                $sampel = Sampel::where('nomor_sampel',$row->get('no_sample'))->first();
                if (!$sampel) {
                    $this->addError($key, "Nomor sampel '".$row->get('no_sample')."' tidak ditemukan");
                } else if ($sampel->sampel_status == 'extraction_sample_sent') {
                    $this->addError($key, "Nomor sampel '".$row->get('no_sample')."' belum diterima di Lab PCR. Mohon diterima terlebih dahulu. ");
                } else if ($sampel->sampel_status != 'pcr_sample_received') {
                    $this->addError($key, "Nomor sampel '".$row->get('no_sample')."' masih pada status " . $sampel->status->deskripsi);
                }
                if (empty($row->get('interpretasi'))) {
                    $this->addError($key, "Interpretasi kosong");
                }
                $data = $row->toArray();
                $keys = array_keys($data);
                $data_ct = [];
                foreach ($keys as $key) {
                    if (strpos($key, 'ct_') === 0) {
                        $data_ct[] = [
                            'target_gen' => strtoupper(substr($key, 3)),
                            'ct_value' => $data[$key],
                        ];
                    }
                }
                if (is_integer($data['tanggal_periksa'])) {
                    $data['tanggal_periksa'] = gmdate("Y-m-d",($data['tanggal_periksa'] - 25569) * 86400);
                }
                $this->data[] = [
                    'no' => $data['no'],
                    'nomor_sampel' => $data['no_sample'],
                    'sampel_id' => $sampel ? $sampel->id : null,
                    'kesimpulan_pemeriksaan' => strtolower($data['interpretasi']),
                    'tanggal_input_hasil' => $data['tanggal_periksa'],
                    'target_gen' => $data_ct,
                ];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addError($key, $message)
    {
        if (!isset($this->errors[$key])) {
            $this->errors[$key] = [];
        }
        $this->errors[$key][] = $message;
        $this->errors_count++;
    }

    public function hasError($key)
    {
        return isset($this->errors[$key]) && count($this->errors[$key]) > 0;
    }
}
