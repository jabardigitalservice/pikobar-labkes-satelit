<?php

namespace App\Http\Controllers\Integrasi;

use App\Http\Controllers\Controller;
use App\Models\Sampel;
use Illuminate\Http\Request;

class IntegrasiController extends Controller
{
    public function index(Request $request)
    {
        $models = Sampel::leftJoin('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->leftJoin('register', 'sampel.register_id', 'register.id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'register.id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id')
            ->where('sampel.sampel_status', 'pcr_sample_analyzed')
            ->select('*', 'sampel.id as id')
            ->get();
        $mapping = [];
        foreach ($models as $key => $row) {
            $mapping[$key]['id_sampel'] = $row->id;
            $mapping[$key]['kd_pasien'] = $row->nomor_register;
            $mapping[$key]['nama'] = $row->nama_lengkap;
            $mapping[$key]['jns_identitas'] = $row->jenis_identitas;
            $mapping[$key]['no_identitas'] = $row->nik;
            $mapping[$key]['tgl_lahir'] = $row->tanggal_lahir;
            $mapping[$key]['usia_thn'] = $row->usia_tahun;
            $mapping[$key]['usia_bln'] = $row->usia_bulan;
            $mapping[$key]['jns_kelamin'] = $row->jenis_kelamin;
            $mapping[$key]['no_hp'] = $row->no_hp ? $row->no_hp : $row->no_telp;
            $mapping[$key]['alamat_ktp'] = $row->alamat_lengkap;
            $mapping[$key]['alamat_domisili'] = $row->alamat_domisili;
            $mapping[$key]['alamat_kdpropinsi'] = KD_PROPINSI;
            $mapping[$key]['alamat_nmpropinsi'] = NM_PROPINSI;
            $mapping[$key]['alamat_kdkabupaten'] = $row->kode_kabupaten;
            $mapping[$key]['alamat_nmkabupaten'] = $row->nama_kabupaten;
            $mapping[$key]['alamat_kdkecamatan'] = $row->kode_kecamatan;
            $mapping[$key]['alamat_nmkecamatan'] = $row->nama_kecamatan;
            $mapping[$key]['alamat_kdkelurahan'] = $row->kode_kelurahan;
            $mapping[$key]['alamat_nmkelurahan'] = $row->nama_kelurahan;
            $mapping[$key]['alamat_rw'] = $row->no_rw;
            $mapping[$key]['alamat_rt'] = $row->no_rt;
            $mapping[$key]['warganegara'] = $row->kewarganegaraan;
            $mapping[$key]['warganegara_ket'] = $row->keterangan_kewarganegaraan;
            $mapping[$key]['tgl_gejala'] = TGL_GEJALA;
            $mapping[$key]['faskes_jns'] = $row->jenis_fasyankes;
            $mapping[$key]['faskes_kd'] = $row->kode_fasyankes;
            $mapping[$key]['faskes_nm'] = $row->nama_fasyankes;
            $mapping[$key]['faskes_kdpropinsi'] = KD_PROPINSI;
            $mapping[$key]['faskes_nmpropinsi'] = NM_PROPINSI;
            $mapping[$key]['faskes_kdkabupaten'] = $row->kode_kabupaten_fasyankes;
            $mapping[$key]['faskes_nmkabupaten'] = $row->nama_kabupaten_fasyankes;
            $mapping[$key]['faskes_keterangan'] = $row->instansi_pengirim;
            $mapping[$key]['lab_kd'] = $row->kode_lab;
            $mapping[$key]['lab_nm'] = $row->nama_lab;
            $mapping[$key]['lab_nosampel'] = $row->nomor_sampel;
            $mapping[$key]['lab_nopemeriksaan'] = $row->swab_ke;
            $mapping[$key]['jns_sampel'] = $row->jenis_sampel_id;
            $mapping[$key]['jns_sampel2'] = $row->jenis_sampel_id == 999999 ? $row->jenis_sampel_nama : null;
            $mapping[$key]['tgl_pengambilan'] = date('Y-m-d', strtotime($row->tanggal_swab));
            $mapping[$key]['tgl_kirim'] = date('Y-m-d', strtotime($row->waktu_sample_taken));
            $mapping[$key]['tgl_terima'] = date('Y-m-d', strtotime($row->waktu_sample_taken));
            $mapping[$key]['tgl_periksa'] = date('Y-m-d', strtotime($row->waktu_pcr_sample_analyzed));
            $mapping[$key]['lab_hasil'] = $row->kesimpulan_pemeriksaan;
            $mapping[$key]['lab_keterangan'] = $row->catatan_pemeriksaan;
            $mapping[$key]['lab_status_sampel'] = STATUS_SAMPEL;
            $mapping[$key]['created_id'] = $row->creator_user_id;
            $mapping[$key]['created_by'] = $row->nama_fasyankes;
            $mapping[$key]['created_date'] = date('Y-m-d', strtotime($row->waktu_sample_taken));
            $mapping[$key]['modified_id'] = null;
            $mapping[$key]['modified_by'] = null;
            $mapping[$key]['modified_date'] = null;
            $mapping[$key]['verif_id'] = VERIF_ID;
            $mapping[$key]['verif_by'] = null;
            $mapping[$key]['verif_date'] = date('Y-m-d', strtotime($row->waktu_pcr_sample_analyzed));
        }
        $data['result'] = $mapping;
        $data['count'] = $models->count();
        return response()->json($data, 200);
    }
}
