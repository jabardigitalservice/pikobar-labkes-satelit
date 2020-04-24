<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // table: register
            'nomor_register'=> ['exists:register,nomor_register'],
            'fasyankes_id'=> ['required', 'exists:fasyankes,id'],
            'nomor_rekam_medis'=> ['min:3', 'max:255'],
            'nama_dokter'=> ['required', 'max:255'],
            'no_telp'=> ['max:30'],

            // table: pasien
            // "force_update_pasien"=> ['required', 'boolean'],
            "pasien_id"=> ['required', 'exists:pasien,id'],
            "nama_depan"=> ['required', 'min:3', 'max:255'],
            "nama_belakang"=> ['min:3', 'max:255'],
            "kewarganegaraan"=> ['required', 'in:wni,wna'],
            "no_ktp"=> [
                'nullable', 
                'digits:16', 
                // 'unique:pasien,no_ktp',
            ],
            "no_sim"=> [
                Rule::requiredIf(function(){
                    return !$this->input('no_ktp');
                }),
                'max:255' 
            ],
            "no_kk"=> ['required', 'max:255'],
            "tanggal_lahir"=> ['required', 'date', 'date_format:Y-m-d'],
            "tempat_lahir"=> ['required', 'max:100'],
            "no_hp_pasien"=> ['max:25'],
            "no_telp_pasien"=> ['max:20'],
            "pekerjaan"=> ['max:150'],
            "jenis_kelamin"=> ['required', 'in:L,P'],
            "kota_id"=> ['required', 'exists:kota,id'],
            "kecamatan"=> ['required', 'min:3', 'max:100'],
            "kelurahan"=> ['required', 'min:3', 'max:100'],
            "no_rw"=> ['required', 'max:3'],
            "no_rt"=> ['required', 'max:3'],
            "alamat_lengkap"=> ['required'],
            "keterangan_lain"=> ['nullable'],

            // Riwayat Kunjungan
            "riwayat_kunjungan.*.tanggal_kunjungan"=> ['date', 'date_format:Y-m-d'],
            "riwayat_kunjungan.*.fasyankes_nama"=> [
                'max:150', 
                Rule::requiredIf(function(){
                    return $this->input('riwayat_kunjungan.*.tanggal_kunjungan');
                }),
            ],

            // Tanda dan Gejala Pasien
            "tanda_gejala.pasien_rdt"=> ['required', 'boolean'],
            "tanda_gejala.hasil_rdt_positif"=> [
                'boolean',
                Rule::requiredIf(function(){
                    return $this->input('tanda_gejala.pasien_rdt');
                }),
            ],
            "tanda_gejala.tanggal_rdt"=> [
                'date',
                'date_format:Y-m-d',
                Rule::requiredIf(function(){
                    return $this->input('tanda_gejala.pasien_rdt');
                }),
            ],
            "tanda_gejala.keterangan_rdt"=> ['nullable'],
            "tanda_gejala.tanggal_onset_gejala"=> ['required', 'date', 'date_format:Y-m-d'],
            "tanda_gejala.daftar_gejala.*.gejala_id"=> ['exists:gejala,id'],
            "tanda_gejala.daftar_gejala.*.status"=> ['boolean'],
            "tanda_gejala.gejala_lain"=> ['nullable'], 
            
            // Pemeriksaan Penunjang
            "pemeriksaan_penunjang.xray_paru"=> ['required', 'boolean'],
            "pemeriksaan_penunjang.penjelasan_xray"=> ['max:255'],
            "pemeriksaan_penunjang.leukosit"=> ['numeric'],
            "pemeriksaan_penunjang.limfosit"=> ['numeric'],
            "pemeriksaan_penunjang.trombosit"=> ['numeric'],
            "pemeriksaan_penunjang.ventilator"=> ['required', 'boolean'],
            "pemeriksaan_penunjang.status_kesehatan"=> ['required', 'in:pulang,dirawat,meninggal'],
            "pemeriksaan_penunjang.keterangan_lab"=> ['max:255'],

            // Riwayat Kontak
            "riwayat_kontak.*.nama_lengkap"=> ['max:255'],
            "riwayat_kontak.*.hubungan"=> [
                'max:255',
                Rule::requiredIf(function(){
                    return $this->input('riwayat_kontak.*.nama_lengkap');
                }),
            ],
            "riwayat_kontak.*.alamat"=> [
                'max:255',
                Rule::requiredIf(function(){
                    return $this->input('riwayat_kontak.*.nama_lengkap');
                }),
            ],
            "riwayat_kontak.*.tanggal_awal"=> [
                'date',
                'date_format:Y-m-d',
                Rule::requiredIf(function(){
                    return $this->input('riwayat_kontak.*.nama_lengkap');
                }),
            ],
            "riwayat_kontak.*.tanggal_akhir"=> [
                'date',
                'date_format:Y-m-d',
                Rule::requiredIf(function(){
                    return $this->input('riwayat_kontak.*.nama_lengkap');
                }),
            ],
            "riwayat_kontak.*.positif_covid19" => [
                'boolean',
                Rule::requiredIf(function(){
                    return $this->input('riwayat_kontak.*.nama_lengkap');
                }),
            ],
            "riwayat_kontak.*.keluarga_sakit_sejenis"=> [
                'boolean',
                Rule::requiredIf(function(){
                    return $this->input('riwayat_kontak.*.nama_lengkap');
                }),
            ],

            // Riwayat Lawatan
            "riwayat_lawatan.*.tanggal_lawatan"=> [
                'date',
                'date_format:Y-m-d'
            ],
            "riwayat_lawatan.*.nama_kota"=> [
                'max:255',
                Rule::requiredIf(function(){
                    return $this->input('riwayat_lawatan.*.tanggal_lawatan');
                }),
            ],
            "riwayat_lawatan.*.nama_negara"=> [
                'max:255',
                Rule::requiredIf(function(){
                    return $this->input('riwayat_lawatan.*.tanggal_lawatan');
                }),
            ],

            // Penyakit Penyerta
            "penyakit_penyerta.keterangan_lain"=> ['max:255'],
            "penyakit_penyerta.riwayat.*.penyakit_penyerta_id"=> [
                'exists:penyakit_penyerta,id',
            ],
            "penyakit_penyerta.riwayat.*.status"=> [
                'boolean',
                Rule::requiredIf(function(){
                    return $this->input('penyakit_penyerta.riwayat.*.penyakit_penyerta_id');
                }),
            ]

        ];
    }
}
