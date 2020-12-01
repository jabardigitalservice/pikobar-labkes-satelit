<?php

namespace App\Http\Controllers\V1;

use App\Enums\JenisSampelEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRegisterPerujukRequest;
use App\Http\Requests\StoreRegisterPerujukRequest;
use App\Http\Requests\UpdateRegisterPerujukRequest;
use App\Imports\RegisterPerujukImport;
use App\Models\Fasyankes;
use App\Models\JenisSampel;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use App\Models\Provinsi;
use App\Models\Register;
use App\Models\RegisterPerujuk;
use App\Models\Sampel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class RegisterPerujukController extends Controller
{
    public function index(Request $request)
    {
        $models = RegisterPerujuk::query()
            ->with(['kota', 'fasyankes', 'perujuk']);
        if ($request->user()->role_id == RoleEnum::PERUJUK()->getIndex()) {
            $models = $models->where('perujuk_id', $request->user()->perujuk_id);
        }
        if ($request->user()->role_id == RoleEnum::LABORATORIUM()->getIndex()) {
            $models = $models->where('lab_satelit_id', $request->user()->lab_satelit_id);
        }
        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order = $request->get('order', 'created_at');
        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 500);

        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('nomor_sampel', 'ilike', '%' . $search . '%')
                    ->orWhere('nama_pasien', 'ilike', '%' . $search . '%')
                    ->orWhereHas('kota', function ($query) use ($search) {
                        $query->where('nama', 'ilike', '%' . $search . '%');
                    })
                    ->orWhereHas('fasyankes', function ($query) use ($search) {
                        $query->where('nama', 'ilike', '%' . $search . '%');
                    })
                    ->orWhere('sumber_pasien', 'ilike', '%' . $search . '%')
                    ->orwhere('status', $search);
            });
        }

        if ($params) {
            foreach (json_decode($params) as $key => $val) {
                if ($val == '') {
                    continue;
                }
                switch ($key) {
                    case "nomor_sampel":
                        $models = $models->where('nomor_sampel', 'ilike', '%' . $val . '%');
                        break;
                    case "nama_pasien":
                        $models = $models->where('nama_pasien', 'ilike', '%' . $val . '%')
                            ->orWhere('nik', 'ilike', '%' . $val . '%');
                        break;
                    case "fasyankes":
                        $models = $models->whereHas('fasyankes', function ($query) use ($val) {
                            $query->where('nama', 'ilike', '%' . $val . '%');
                        });
                        break;
                    case "perujuk":
                        $models = $models->whereHas('perujuk', function ($query) use ($val) {
                            $query->where('nama', 'ilike', '%' . $val . '%');
                        });
                        break;
                    case "kategori":
                        $models = $models->where('sumber_pasien', 'ilike', '%' . $val . '%');
                        break;
                    case "kriteria":
                        $models = $models->where('status', $val);
                        break;
                    case "tanggal":
                        $models = $models->whereDate('created_at', date('Y-m-d', strtotime($val)));
                        break;
                }
            }
        }

        if ($order) {
            $order_direction = $request->get('order_direction', 'asc');
            if (empty($order_direction)) {
                $order_direction = 'desc';
            }
            switch ($order) {
                case "nomor_sampel":
                    $models = $models->orderBy('nomor_sampel', $order_direction);
                    break;
                case "nama_pasien":
                    $models = $models->orderBy('nama_pasien', $order_direction);
                    break;
                case "kota":
                    $models = $models->whereHas('kota', function ($query) use ($order_direction) {
                        $query->orderBy('nama', $order_direction);
                    });
                case "fasyankes":
                    $models = $models->whereHas('fasyankes', function ($query) use ($order_direction) {
                        $query->orderBy('nama', $order_direction);
                    });
                    break;
                case "kategori":
                    $models = $models->orderBy('sumber_pasien', $order_direction);
                    break;
                case "kriteria":
                    $models = $models->orderBy('status', $order_direction);
                    break;
                case "tanggal":
                    $models = $models->orderBy('created_at', $order_direction);
                    break;
                case 'kota':
                    $models = $models->orderBy('kota', $order_direction);
                    break;
            }
        }

        $count = $models->count();
        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        $result = [
            'data' => $models,
            'count' => $count,
        ];

        return response()->json($result);
    }

    public function store(StoreRegisterPerujukRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $data = new RegisterPerujuk();
            $data->nomor_register = generateNomorRegister();
            $data->register_uuid = (string)Str::uuid();
            $data->creator_user_id = $user->id;
            $data->lab_satelit_id = $request->get('lab_satelit_id');
            $data->kode_kasus = $request->get('kode_kasus');
            $data->perujuk_id = $user->perujuk_id;
            $data->sumber_pasien = $request->get('sumber_pasien');
            $data->kriteria = $request->get('kriteria');
            $data->swab_ke = $request->get('swab_ke');
            if ($request->get('tanggal_swab')) {
                $data->tanggal_swab = date('Y-m-d', strtotime($request->get('tanggal_swab')));
            }
            $data->nomor_sampel = $request->get('nomor_sampel');
            $data->jenis_sampel = $request->get('jenis_sampel');
            if ($request->get('jenis_sampel') != JenisSampelEnum::LAINNYA()->getIndex()) {
                $jenis_sampel = JenisSampel::where('id', $request->get('jenis_sampel'))->first();
                $data->nama_jenis_sampel = optional($jenis_sampel)->nama;
            } else {
                $data->nama_jenis_sampel = $request->get('nama_jenis_sampel');
            }
            $data->fasyankes_id = $request->get('fasyankes_id');
            $data->fasyankes_pengirim = $request->get('fasyankes_pengirim');
            $data->nama_pasien = $request->get('nama_pasien');
            $data->kewarganegaraan = $request->get('kewarganegaraan');
            $data->keterangan_warganegara = $request->get('keterangan_warganegara');
            $data->nik = $request->get('nik');
            $data->tempat_lahir = $request->get('tempat_lahir');
            if ($request->get('tanggal_lahir')) {
                $data->tanggal_lahir = date('Y-m-d', strtotime($request->get('tanggal_lahir')));
            }
            $data->no_hp = $request->get('no_hp');
            $data->provinsi_id = $request->get('provinsi_id');
            $data->kota_id = $request->get('kota_id');
            $data->kecamatan_id = $request->get('kecamatan_id');
            $data->kelurahan_id = $request->get('kelurahan_id');
            $data->alamat = $request->get('alamat');
            $data->no_rt = $request->get('no_rt');
            $data->no_rw = $request->get('no_rw');
            $data->jenis_kelamin = $request->get('jk');
            $data->keterangan = $request->get('keterangan');
            $data->usia_tahun = $request->get('usia_tahun');
            $data->usia_bulan = $request->get('usia_bulan');
            $data->save();
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'success', 'result' => $data]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'error']);
        }
    }

    public function update(UpdateRegisterPerujukRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $data = RegisterPerujuk::findOrFail($id);
            abort_if($data->status != 'dikirim', 500, 'data tersebut sudah masuk ketahap berikutnya');
            $data->creator_user_id = $user->id;
            $data->lab_satelit_id = $request->get('lab_satelit_id');
            $data->kode_kasus = $request->get('kode_kasus');
            $data->perujuk_id = $user->perujuk_id;
            $data->sumber_pasien = $request->get('sumber_pasien');
            $data->kriteria = $request->get('kriteria');
            $data->swab_ke = $request->get('swab_ke');
            if ($request->get('tanggal_swab')) {
                $data->tanggal_swab = date('Y-m-d', strtotime($request->get('tanggal_swab')));
            }
            $data->nomor_sampel = $request->get('nomor_sampel');
            $data->jenis_sampel = $request->get('jenis_sampel');
            if ($request->get('jenis_sampel') != JenisSampelEnum::LAINNYA()->getIndex()) {
                $jenis_sampel = JenisSampel::where('id', $request->get('jenis_sampel'))->first();
                $data->nama_jenis_sampel = optional($jenis_sampel)->nama;
            } else {
                $data->nama_jenis_sampel = $request->get('nama_jenis_sampel');
            }
            $data->fasyankes_id = $request->get('fasyankes_id');
            $data->fasyankes_pengirim = $request->get('fasyankes_pengirim');
            $data->nama_pasien = $request->get('nama_pasien');
            $data->kewarganegaraan = $request->get('kewarganegaraan');
            $data->keterangan_warganegara = $request->get('keterangan_warganegara');
            $data->nik = $request->get('nik');
            $data->tempat_lahir = $request->get('tempat_lahir');
            if ($request->get('tanggal_lahir')) {
                $data->tanggal_lahir = date('Y-m-d', strtotime($request->get('tanggal_lahir')));
            }
            $data->no_hp = $request->get('no_hp');
            $data->provinsi_id = $request->get('provinsi_id');
            $data->kota_id = $request->get('kota_id');
            $data->kecamatan_id = $request->get('kecamatan_id');
            $data->kelurahan_id = $request->get('kelurahan_id');
            $data->alamat = $request->get('alamat');
            $data->no_rt = $request->get('no_rt');
            $data->no_rw = $request->get('no_rw');
            $data->jenis_kelamin = $request->get('jenis_kelamin');
            $data->keterangan = $request->get('keterangan');
            $data->usia_tahun = $request->get('usia_tahun');
            $data->usia_bulan = $request->get('usia_bulan');
            $data->save();
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'success', 'result' => $data]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'error']);
        }
    }

    public function bulk(Request $request)
    {
        $register_perujuk = $request->get('id');
        $registerPerujuk = RegisterPerujuk::whereIn('id', $register_perujuk)->get();
        foreach ($registerPerujuk as $row) {
            $row = collect($row);
            DB::beginTransaction();
            try {
                $user = $request->user();
                $register = new Register;
                $register->nomor_register = $row->get('nomor_register');
                $register->perujuk_id = $row->get('perujuk_id');
                $register->register_uuid = $row->get('register_uuid');
                $register->creator_user_id = $user->id;
                $register->lab_satelit_id = $row->get('lab_satelit_id');
                $register->fasyankes_id = $row->get('fasyankes_id');
                $register->fasyankes_pengirim = $row->get('fasyankes_pengirim');
                $register->instansi_pengirim = $row->get('fasyankes_pengirim');
                $namaRS = $this->getNamaRS($row->get('fasyankes_id'));
                $register->nama_rs = $namaRS;
                $register->instansi_pengirim_nama = $namaRS;
                $register->sumber_pasien = $row->get('sumber_pasien');
                $register->status = $row->get('kriteria');
                $register->swab_ke = $row->get('swab_ke');
                if ($row->get('tanggal_swab') != '') {
                    $register->tanggal_swab = date('Y-m-d', strtotime($row->get('tanggal_swab')));
                }
                $register->save();

                $pasien = new Pasien;
                $pasien->nama_lengkap = $row->get('nama_pasien');
                $pasien->perujuk_id = $row->get('perujuk_id');
                $pasien->kewarganegaraan = $row->get('kewarganegaraan');
                $pasien->keterangan_warganegara = $row->get('keterangan_warganegara');
                $pasien->nik = $row->get('nik');
                $pasien->tempat_lahir = $row->get('tempat_lahir');
                if ($row->get('tanggal_lahir') != null) {
                    $pasien->tanggal_lahir = date('Y-m-d', strtotime($row->get('tanggal_lahir')));
                }
                $pasien->no_hp = $row->get('nohp');
                $pasien->kode_provinsi = $row->get('provinsi_id');
                $pasien->kota_id = $row->get('kota_id');
                $pasien->kode_kabupaten = $row->get('kota_id');
                $pasien->kode_kecamatan = $row->get('kecamatan_id');
                $pasien->kode_kelurahan = $row->get('kelurahan_id');
                $nama_provinsi = $this->getNamaWilayah('provinsi', $row->get('provinsi_id'));
                $nama_kota = $this->getNamaWilayah('kota', $row->get('kota_id'));
                $nama_kecamatan = $this->getNamaWilayah('kecamatan', $row->get('kecamatan_id'));
                $nama_kelurahan = $this->getNamaWilayah('kelurahan', $row->get('kelurahan_id'));
                $pasien->nama_provinsi = $nama_provinsi;
                $pasien->kecamatan = $nama_kecamatan;
                $pasien->kelurahan = $nama_kelurahan;
                $pasien->nama_kabupaten = $nama_kota;
                $pasien->nama_kecamatan = $nama_kecamatan;
                $pasien->nama_kelurahan = $nama_kelurahan;
                $pasien->alamat_lengkap = $row->get('alamat');
                $pasien->sumber_pasien = $row->get('sumber_pasien');
                $pasien->no_rt = $row->get('no_rt');
                $pasien->no_rw = $row->get('no_rw');
                $pasien->jenis_kelamin = $row->get('jenis_kelamin');
                $pasien->keterangan_lain = $row->get('keterangan');
                $pasien->usia_tahun = $row->get('usia_tahun');
                $pasien->usia_bulan = $row->get('usia_bulan');
                $pasien->kode_kasus = $row->get('kode_kasus');
                $pasien->lab_satelit_id = $user->lab_satelit_id;
                $pasien->save();

                PasienRegister::create([
                    'pasien_id' => $pasien->id,
                    'register_id' => $register->id,
                ]);

                $pengambilan_sampel = PengambilanSampel::create([
                    'sampel_diambil' => false,
                    'sampel_diterima' => false,
                    'diterima_dari_faskes' => false,
                    'sampel_rdt' => false,
                    'catatan' => $row->get('keterangan'),
                ]);

                $sampel = new Sampel();
                $sampel->nomor_sampel = $row->get('nomor_sampel');
                $sampel->jenis_sampel_id = $row->get('jenis_sampel');
                $sampel->perujuk_id = $row->get('perujuk_id');
                $sampel->jenis_sampel_nama = $row->get('nama_jenis_sampel');
                $sampel->register_id = $register->id;
                $sampel->lab_satelit_id = $user->lab_satelit_id;
                $sampel->pengambilan_sampel_id = $pengambilan_sampel->id;
                $sampel->creator_user_id = $user->id;
                $sampel->sampel_status = 'sample_taken';
                $sampel->waktu_sample_taken = $row->get('created_at');
                $sampel->save();

                RegisterPerujuk::find($row->get('id'))->updateState('diterima');
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        }
        return response()->json(['status' => 200, 'message' => 'success']);
    }

    public function show($id)
    {
        try {
            $models = RegisterPerujuk::with(['fasyankes', 'kota', 'perujuk'])->findOrFail($id);
            return response()->json(['status' => 200, 'message' => 'success', 'result' => $models]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'error', 'result' => []]);
        }
    }

    public function delete($id)
    {
        try {
            $models = RegisterPerujuk::findOrFail($id);
            abort_if($models->status != 'dikirim', 500, 'data tersebut sudah masuk ketahap berikutnya');
            $models->delete();
            return response()->json(['status' => 200, 'message' => 'success', 'result' => $models]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'error', 'result' => []]);
        }
    }

    private function getNamaRS($fasyankes_id)
    {
        if (!$fasyankes_id) {
            return $fasyankes_id;
        }
        return optional(Fasyankes::find($fasyankes_id))->nama;
    }

    private function getNamaWilayah($wilayah, $id)
    {
        if (!$id) {
            return $id;
        }

        switch ($wilayah) {
            case 'provinsi':
                return optional(Provinsi::find($id))->nama;
                break;
            case 'kota':
                return optional(Kota::find($id))->nama;
                break;
            case 'kecamatan':
                return optional(Kecamatan::find($id))->nama;
                break;
            case 'Kelurahan':
                return optional(Kelurahan::find($id))->nama;
                break;
        }
    }

    public function import(ImportRegisterPerujukRequest $request)
    {
        Excel::import(new RegisterPerujukImport, $request->file('register_file'));

        return response()->json([
            'status' => 200,
            'message' => 'Sukses import data.',
            'data' => null,
        ]);
    }
}
