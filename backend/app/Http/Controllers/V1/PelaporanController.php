<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\PelaporanService;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class PelaporanController extends Controller
{
    public function fetch_data(Request $request)
    {
        $pelaporan = new PelaporanService;
        $response = $pelaporan->pendaftar_rdt($request->get('search'), $request->get('limit', 10))->json();
        if ($response['status_code'] == 200 && count($response['data']['content']) > 0) {
            $data = $response['data']['content'];
            $response['data']['content'] = [];
            $key = 0;
            $dataUnique = [];
            foreach ($data as $item) {
                $dataBy = $item['nik'].strtoupper($item['name']).$item['phone_number'];
                if (!in_array($dataBy, $dataUnique)) {
                    $dataUnique[] = $dataBy;
                    $response['data']['content'][$key]['status_code'] = $this->setStatusCoce($item['status']);
                    $response['data']['content'][$key]['address_district_code'] = (int)str_replace('.', '', $item['address_district_code']);
                    $response['data']['content'][$key]['address_district_name'] = $this->__getWilayah('kota', (int)str_replace('.', '', $item['address_district_code']));
                    $response['data']['content'][$key]['address_subdistrict_code'] = (int)str_replace('.', '', $item['address_subdistrict_code']);
                    $response['data']['content'][$key]['address_subdistrict_name'] = $this->__getWilayah('kecamatan', (int)str_replace('.', '', $item['address_subdistrict_code']));
                    $response['data']['content'][$key]['address_village_code'] = (int)str_replace('.', '', $item['address_village_code']);
                    $response['data']['content'][$key]['address_village_name'] = $this->__getWilayah('kelurahan', (int)str_replace('.', '', $item['address_village_code']));
                    $response['data']['content'][$key]['address_province_code'] = KD_PROPINSI;
                    $response['data']['content'][$key]['address_province_name'] = $this->__getWilayah('provinsi', KD_PROPINSI);
                    $response['data']['content'][$key]['id'] = $item['id'];
                    $response['data']['content'][$key]['id_case'] = $item['id_case'];
                    $response['data']['content'][$key]['nik'] = $item['nik'];
                    $response['data']['content'][$key]['name'] = $item['name'];
                    $response['data']['content'][$key]['birth_date'] = $item['birth_date'];
                    $response['data']['content'][$key]['age'] = $item['age'];
                    $response['data']['content'][$key]['gender'] = $item['gender'];
                    $response['data']['content'][$key]['address_detail'] = $item['address_detail'];
                    $response['data']['content'][$key]['phone_number'] = $item['phone_number'];
                    $response['data']['content'][$key]['nationality'] = $item['nationality'];
                    $response['data']['content'][$key]['nationality_name'] = $item['nationality_name'];
                    $response['data']['content'][$key]['final_result'] = $item['final_result'];
                    $response['data']['content'][$key]['test_location_type'] = $item['test_location_type'];
                    $response['data']['content'][$key]['test_location'] = $item['test_location'];
                    $response['data']['content'][$key]['status'] = $item['status'];
                    $response['data']['content'][$key]['test_date'] = $item['test_date'];
                    $response['data']['content'][$key]['id_match'] = $item['id_match'];
                    $key++;
                }
            }
        } else {
            $search = $request->search;
            $pasien = Pasien::where('nama_lengkap', 'ilike', "%$search%")
                ->orWhere('nik', 'ilike', "$search%")
                ->orWhere('no_hp', 'ilike', "$search%")
                ->distinct('nama_lengkap', 'nik')
                ->orderByRaw('nama_lengkap desc, nik desc, updated_at desc')
                ->limit(10)->get();
            foreach ($pasien as $key => $item) {
                $response['data']['content'][$key]['id'] = null;
                $response['data']['content'][$key]['id_case'] = null;
                $response['data']['content'][$key]['nik'] = $item->nik;
                $response['data']['content'][$key]['name'] = $item->nama_lengkap;
                $response['data']['content'][$key]['birth_date'] = $item->tanggal_lahir;
                $response['data']['content'][$key]['age'] = $item->usia_tahun;
                $response['data']['content'][$key]['gender'] = $item->jenis_kelamin;
                $response['data']['content'][$key]['address_detail'] = $item->alamat_lengkap;
                $response['data']['content'][$key]['address_province_code'] = KD_PROPINSI;
                $response['data']['content'][$key]['address_province_name'] = $this->__getWilayah('provinsi', KD_PROPINSI);
                $response['data']['content'][$key]['address_district_code'] = $item->kota_id;
                $response['data']['content'][$key]['address_district_name'] = optional($item->kota)->nama;
                $response['data']['content'][$key]['address_subdistrict_code'] = $item->kecamatan_id;
                $response['data']['content'][$key]['address_subdistrict_name'] = optional($item->kecamatan)->nama;
                $response['data']['content'][$key]['address_village_code'] = $item->kelurahan_id;
                $response['data']['content'][$key]['address_village_name'] = optional($item->kelurahan)->nama;
                $response['data']['content'][$key]['phone_number'] = $item->no_hp;
                $response['data']['content'][$key]['nationality'] = $item->kewarganegaraan;
                $response['data']['content'][$key]['nationality_name'] = null;
                $response['data']['content'][$key]['final_result'] = null;
                $response['data']['content'][$key]['test_location_type'] = null;
                $response['data']['content'][$key]['test_location'] = null;
                $response['data']['content'][$key]['status'] = $item->status;
                $response['data']['content'][$key]['test_date'] = null;
                $response['data']['content'][$key]['id_match'] = null;
                $response['data']['content'][$key]['status_code'] = $item->status;
            }
        }

        return response()->json($response, 200);
    }

    public function setStatusCoce($status)
    {
        switch (strtoupper($status)) {
            case 'SUSPECT':
                $status = 'suspek';
                break;
            case 'CLOSEDCONTACT':
                $status = 'kontak erat';
                break;
            case 'PROBABLE':
                $status = 'probable';
                break;
            case 'CONFIRMATION':
                $status = 'konfirmasi';
                break;
            default:
                $status = 'tanpa kriteria';
                break;
        }

        return array_search($status, array_map('strtolower', STATUSES));
    }

    private function __getWilayah($tingkat, $id_wilayah)
    {
        $namaWilayah = null;
        if (!$id_wilayah) {
            return $namaWilayah;
        }
        switch ($tingkat) {
            case 'provinsi':
                $namaWilayah = optional(Provinsi::find($id_wilayah))->nama;
                break;
            case 'kota':
                $namaWilayah = optional(Kota::find($id_wilayah))->nama;
                break;
            case 'kecamatan':
                $namaWilayah = optional(Kecamatan::find($id_wilayah))->nama;
                break;
            case 'kelurahan':
                $namaWilayah = optional(Kelurahan::find($id_wilayah))->nama;
                break;
            default:
                break;
        }
        return $namaWilayah;
    }
}
