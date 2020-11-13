<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserDinkes as UserDinkesResource;
use App\User;
use Illuminate\Http\Request;

class ListDinkesController extends Controller
{
    protected $role_id;
    protected $model;
    public function __construct()
    {
        $this->role_id =[RoleEnum::DINKES()->getIndex(), RoleEnum::SUPERADMIN()->getIndex()];
        $this->model = User::query()->whereIn('role_id', $this->role_id);
    }

    public function __invoke(Request $request)
    {

        $order = $request->get('order');
        if ($order) {
            $order_direction = $request->get('order_direction', 'asc');
            $this->sort($order, $order_direction);
        }

        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 20);
        $count = $this->model->count();
        $data = UserDinkesResource::collection($this->model->skip(($page - 1) * $perpage)->take($perpage)->get());
        return response()->json([
            'data' => $data,
            'count' => $count
        ]);
    }

    private function sort($order, $order_direction)
    {
        switch (strtolower($order)) {
            case 'lab':
            case 'alamat_lab':
                $order = $order == 'lab' ? 'nama' : 'alamat';
                $this->model = $this->model->join('lab_satelit', 'users.lab_satelit_id', 'lab_satelit.id')->orderBy('lab_satelit.' . $order, $order_direction);
                break;
            default:
                $this->model = $this->model->orderBy($order, $order_direction);
                break;
        }
    }
}