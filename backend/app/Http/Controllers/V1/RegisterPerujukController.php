<?php

namespace App\Http\Controllers\V1;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPerujukRequest;
use App\Http\Resources\RegisterPerujukResource;
use App\Models\RegisterPerujuk;
use App\Traits\RegisterPerujukTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegisterPerujukController extends Controller
{
    use RegisterPerujukTrait;

    public function index(Request $request)
    {
        $this->getRequestRegisterPerujuk($request);
        $models = RegisterPerujuk::with(['kota', 'fasyankes']);
        if ($this->user->hasRole(RoleEnum::PERUJUK()->getIndex())) {
            $models->where('perujuk_id', $this->user->perujuk_id);
        }
        if ($this->user->hasRole(RoleEnum::LABORATORIUM()->getIndex())) {
            $models->where('lab_satelit_id', $this->user->lab_satelit_id);
            $models->where('status', 'dikirim');
        }
        if ($this->search != '') {
            $models = $this->searchRegisterPerujuk($models, $this->search);
        }
        if ($this->params) {
            foreach (json_decode($this->params) as $key => $val) {
                if ($val == '') {
                    continue;
                }
                $models = $this->filterRegisterPerujuk($models, $key, $val);
            }
        }
        if ($this->order) {
            $models = $this->orderRegisterPerujuk($models, $this->order, $this->order_direction);
        }
        return RegisterPerujukResource::collection($models->paginate($this->perpage));
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

    public function destroy(RegisterPerujuk $register_perujuk)
    {
        abort_if($register_perujuk->status != 'dikirim', 500, 'data tersebut sudah masuk ketahap berikutnya');
        $register_perujuk->delete();
        return response()->json(['message' => 'success']);
    }
}
