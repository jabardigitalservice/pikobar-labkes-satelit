<?php

namespace App\Imports;

use App\Models\Sampel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HasilPemeriksaanImport implements ToCollection, WithHeadingRow
{
    public $data;
    public $errors;
    public $errors_count = 0;

    public function collection(Collection $rows)
    {
        $this->data = [];
        $this->errors = [];
        $user = Auth::user();
        DB::beginTransaction();
        try {
            foreach ($rows as $key => $row) {
                if (!$row->get('no')) {
                    continue;
                }
                $sampel = Sampel::where('nomor_sampel', 'ilike', '%' . $row->get('kode_sampel') . '%')
                    ->where('lab_satelit_id', $user->lab_satelit_id)->first();
                if (!$sampel) {
                    $this->addError($key, "Nomor sampel '" . $row->get('kode_sampel') . "' tidak ditemukan");
                } elseif ($sampel->sampel_status == 'pcr_sample_analyzed') {
                    $this->addError($key, "Nomor sampel '" . $row->get('kode_sampel') . "' sudah pada status " . $sampel->status->deskripsi);
                } elseif ($sampel->sampel_status != 'sample_taken') {
                    $this->addError($key, "Nomor sampel '" . $row->get('kode_sampel') . "' masih pada status " . $sampel->status->deskripsi);
                }
                if (empty($row->get('interpretasi'))) {
                    $this->addError($key, "Interpretasi kosong");
                }
                if (!in_array(ucfirst(strtolower($row->get('interpretasi'))), ['Positif', 'Negatif', 'Inkonklusif', 'Invalid'])) {
                    $this->addError($key, "Interpretasi tidak valid harus Positif,Negatif,Inkonklusif,Invalid");
                }

                $data = $row->toArray();
                if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data['tanggal_pemeriksaan'])) {
                    $this->addError($key, "Format Tanggal Swab pemeriksaan harus yyyy-mm-dd");
                }
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

                $data['tanggal_pemeriksaan'] = date("Y-m-d", strtotime($data['tanggal_pemeriksaan']));
                $this->data[] = [
                    'no' => $data['no'],
                    'nomor_sampel' => $data['kode_sampel'],
                    'sampel_id' => $sampel ? $sampel->id : null,
                    'kesimpulan_pemeriksaan' => strtolower($data['interpretasi']),
                    'tanggal_input_hasil' => $data['tanggal_pemeriksaan'],
                    'nama_kit_pemeriksaan' => $data['kit_pemeriksaan'],
                    'catatan_pemeriksaan' => $data['keterangan'],
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
