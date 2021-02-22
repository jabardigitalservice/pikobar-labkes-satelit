<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegistrasiSampelResource;
use App\Models\Register;
use Illuminate\Http\Request;

class Registrasisampel extends Controller
{
    public function getData(Request $request)
    {
        $params          = $request->get('params', false);
        $search          = $request->get('search', false);
        $order           = $request->get('order', 'tgl_input');
        $order_direction = $request->get('order_direction', 'desc');
        $perpage         = $request->get('perpage', 20);

        $models = Register::joinPasienRegister()
            ->joinPasien()
            ->joinSampel()
            ->joinKota()
            ->selectCustom()
            ->whereLabSatelit($request->user()->lab_satelit_id);
        if ($search) {
            $models->search($search);
        }
        if ($params) {
            $models->filter($params);
        }
        if ($order) {
            $models->order($order, $order_direction);
        }
        return RegistrasiSampelResource::collection($models->paginate($perpage));
    }
}
