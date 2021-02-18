<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class ListUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $role)
    {
        $order           = $request->input('order', 'name');
        $order_direction = $request->input('order_direction', 'asc');
        $perpage         = $request->input('perpage', 20);

        $model = User::select('users.*')
                        ->leftJoin('lab_satelit', 'users.lab_satelit_id', 'lab_satelit.id')
                        ->leftJoin('kota', 'users.kota_id', 'kota.id')
                        ->leftJoin('fasyankes', 'users.perujuk_id', 'fasyankes.id');
        $model = $this->roleUser($role, $model);
        $model = $this->orderBy($order, $order_direction, $model);
        return UserResource::collection($model->paginate($perpage));
    }

    private function roleUser($role, $model)
    {
        switch ($role) {
            case 'dinkes':
                $model->userDinkes();
                break;
            case 'perujuk':
                $model->userPerujuk();
                break;
            default:
                $model->UserLab();
                break;
        }
        return $model;
    }

    private function orderBy($order, $order_direction, $model)
    {
        switch ($order) {
            case 'lab':
                $model->orderBy('lab_satelit.nama', $order_direction);
                break;
            case 'alamat':
                $model->orderBy('lab_satelit.alamat', $order_direction);
                break;
            case 'dinkes':
                $model->orderBy('kota.nama', $order_direction);
                break;
        }
        if (in_array($order, ['name', 'status', 'username', 'email', 'koordinator', 'last_login_at'])) {
            $model->orderBy("users.$order", $order_direction);
        }
        return $model;
    }
}
