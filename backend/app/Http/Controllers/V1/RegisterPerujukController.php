<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPerujukRequest;
use App\Http\Resources\RegisterPerujukResource;
use App\Models\RegisterPerujuk;
use App\Traits\PaginateTrait;
use App\Traits\RegisterPerujukTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class RegisterPerujukController extends Controller
{
    use RegisterPerujukTrait;
    use PaginateTrait;

    public function index(Request $request)
    {
        $params          = $request->get('params', false);
        $search          = $request->get('search', false);
        $order           = $request->get('order', 'tanggal');
        $perpage         = $request->get('perpage', 20);
        $order_direction = $request->get('order_direction', 'desc');
        $user            = $request->user();

        $models          = RegisterPerujuk::rolePerujuk($user);

        if ($search != '') {
            $models->search($search);
        }

        if ($params) {
            $models->filter($params);
        }
        $models->order($order, $this->getValidOrderDirection($order_direction));
        return RegisterPerujukResource::collection($models->paginate($this->getValidPerpage($perpage)));
    }

    public function store(RegisterPerujukRequest $request)
    {
        RegisterPerujuk::create($request->validated() + [
            'nomor_register' => generateNomorRegister(),
            'register_uuid' => Str::uuid(),
            'creator_user_id' => $request->user()->id,
            'perujuk_id' => $request->user()->perujuk_id,
            'nama_jenis_sampel' => $this->getJenisSampel($request),
        ]);
        return response()->json(['message' => 'success'], Response::HTTP_CREATED);
    }

    public function update(RegisterPerujukRequest $request, RegisterPerujuk $register_perujuk)
    {
        abort_if($register_perujuk->status != 'dikirim', 500, 'data tersebut sudah masuk ketahap berikutnya');
        $register_perujuk->fill($request->validated() + [
            'creator_user_id' => $request->user()->id,
            'perujuk_id' => $request->user()->perujuk_id,
            'nama_jenis_sampel' => $this->getJenisSampel($request),
        ]);
        $register_perujuk->save();
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
