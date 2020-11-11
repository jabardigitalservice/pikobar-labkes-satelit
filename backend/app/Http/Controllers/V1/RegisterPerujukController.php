<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterPerujukRequest;
use App\Models\JenisSampel;
use App\Models\RegisterPerujuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterPerujukController extends Controller
{
    public function index(Request $request)
    {
        $models = RegisterPerujuk::query();

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
                        $models = $models->where('nama_pasien', 'ilike', '%' . $val . '%');
                        break;
                    case "kota":
                        $models = $models->whereHas('kota', function ($query) use ($val) {
                            $query->where('nama', 'ilike', '%' . $val . '%');
                        });
                    case "fasyankes":
                        $models = $models->whereHas('fasyankes', function ($query) use ($val) {
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
            $data->perujuk_id = $user->perujuk_id;
            $data->sumber_pasien = $request->get('sumber_pasien');
            $data->kriteria = $request->get('kriteria');
            $data->swab_ke = $request->get('swab_ke');
            if ($request->get('tanggal_swab')) {
                $data->tanggal_swab = date('Y-m-d', strtotime($request->get('tanggal_swab')));
            }
            $data->nomor_sampel = $request->get('nomor_sampel');
            $data->jenis_sampel = $request->jenis_sampel;
            if ($request->get('jenis_sampel') != 999999) {
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
}
