<?php

namespace App\Http\Controllers\V1;

use App\Enums\JenisSampelEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPerujukRequest;
use App\Models\JenisSampel;
use App\Models\RegisterPerujuk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $models = $models->where('status', 'dikirim');
        }
        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order = $request->get('order', 'tanggal');
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
                        $models = $models->where(function ($query) use ($val) {
                            $query->where('nama_pasien', 'ilike', '%' . $val . '%')
                                ->orWhere('nik', 'ilike', '%' . $val . '%');
                        });
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
            $order_direction = $request->get('order_direction', 'desc');
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

    public function store(RegisterPerujukRequest $request)
    {
        RegisterPerujuk::create($request->all() + [
            'nomor_register' => generateNomorRegister(),
            'register_uuid' => Str::uuid(),
            'creator_user_id' => $request->user()->id,
            'perujuk_id' => $request->user()->perujuk_id,
            'nama_jenis_sampel' => $this->getJenisSampel($request),
        ]);
        return response()->json(['message' => 'success']);
    }

    public function update(RegisterPerujukRequest $request, RegisterPerujuk $register_perujuk)
    {
        abort_if($register_perujuk->status != 'dikirim', 500, 'data tersebut sudah masuk ketahap berikutnya');
        $register_perujuk->update($request->all() + [
            'creator_user_id' => $request->user()->id,
            'perujuk_id' => $request->user()->perujuk_id,
            'nama_jenis_sampel' => $this->getJenisSampel($request),
        ]);
        return response()->json(['message' => 'success']);
    }

    public function show(RegisterPerujuk $register_perujuk)
    {
        $register_perujuk->load(['fasyankes', 'kota', 'perujuk']);
        return response()->json(['result' => $register_perujuk]);
    }

    public function delete(RegisterPerujuk $register_perujuk)
    {
        abort_if($register_perujuk->status != 'dikirim', 500, 'data tersebut sudah masuk ketahap berikutnya');
        $register_perujuk->delete();
        return response()->json(['message' => 'success']);
    }

    private function getJenisSampel(Request $request)
    {
        $jenis_sampel = $request->input('nama_jenis_sampel');
        if ($request->input('jenis_sampel') != JenisSampelEnum::LAINNYA()->getIndex()) {
            $jenis_sampel = JenisSampel::find($request->input('jenis_sampel'))->nama;
        }
        return $jenis_sampel;
    }
}
