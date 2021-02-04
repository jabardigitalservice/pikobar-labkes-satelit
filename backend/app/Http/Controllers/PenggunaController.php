<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{


    public function listPengguna(Request $request)
    {
        $models = User::with(['roles','lab_pcr','validator','lab_satelit']);
        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order  = $request->get('order', 'name');

        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                   ->orWhere('email', 'ilike', '%' . $search . '%');
            });
        }
        $count = $models->count();

        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 999999);

        if ($order) {
            $order_direction = $request->get('order_direction', 'asc');
            switch ($order) {
                default:
                    $models = $models->orderBy($order, $order_direction);
                    break;
            }
        }
        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        $result = [
            'data' => $models,
            'count' => $count
        ];

        return response()->json($result);
    }

    public function savePengguna(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'username' => 'required|max:80|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'role_id' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User();
        $user->username = $request->get('username');
        $user->password = bcrypt($request->get('password'));
        $user->email = $request->get('email');
        $user->name = $request->get('name');
        $user->role_id = $request->get('role_id');
        $user->lab_pcr_id = $request->get('lab_pcr_id');
        $user->validator_id = $request->get('validator_id');
        $user->save();

        return response()->json(['status' => 201,'message' => 'Berhasil menambahkan pengguna','result' => []]);
    }

    public function deletePengguna(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->first();
            $user->delete();
            return response()->json(['status' => 200,'message' => 'Berhasil menghapus data pengguna','result' => []]);
        } catch (\Exception $ex) {
            return response()->json(['status' => 400,'message' => 'Gagal menghapus data, terjadi kesalahan server','result' => $ex->getMessage()]);
        }
    }

    public function updatePengguna(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'username' => [
                'required',
                'max:80',
                Rule::unique('users')->ignore($id, 'id')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id, 'id')
            ],
            'role_id' => 'required',
            'password' => 'sometimes|confirmed',
        ]);

        // dd($id);
        $user = User::where('id', $id)->first();
        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->email =  $request->get('email');
        $user->role_id = $request->get('role_id');
        $user->lab_pcr_id = $request->get('lab_pcr_id');
        $user->validator_id = $request->get('validator_id');
        if (!empty($user->password)) {
            $user->password = bcrypt($request->get('password'));
        }
        $user->save();

        return response()->json(['status' => 200,'message' => 'Berhasil mengubah data pengguna','result' => []]);
    }

    public function showUpdate(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        return response()->json(['status' => 200,'message' => 'success','result' => $user]);
    }
}
