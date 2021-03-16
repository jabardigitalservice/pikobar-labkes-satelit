<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegistrasiSampelResource;
use App\Models\Register;
use App\Traits\PaginateTrait;
use Illuminate\Http\Request;

class Registrasisampel extends Controller
{
    use PaginateTrait;

    public function index(Request $request)
    {
        $params          = $request->get('params', false);
        $search          = $request->get('search', false);
        $order           = $request->get('order', 'tgl_input');
        $order_direction = $request->get('order_direction', 'desc');
        $perpage         = $request->get('perpage', 20);
        $user            = $request->user();

        $models = Register::joinPasienRegister()
            ->joinPasien()
            ->joinSampel()
            ->joinKota()
            ->selectCustom()
            ->LabSatelit($user->lab_satelit_id);

        if ($search) {
            $models->search($search);
        }
        if ($params) {
            $models->filter($params);
        }
        $models->order($order, $this->getValidOrderDirection($order_direction));
        return RegistrasiSampelResource::collection($models->paginate($this->getValidPerpage($perpage)));
    }
}
