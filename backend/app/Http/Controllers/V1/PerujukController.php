<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerujukRequest;
use App\Http\Requests\UpdatePerujukRequest;
use App\Models\Perujuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerujukController extends Controller
{
    public function index(Request $request)
    {
        $models = Perujuk::query();

        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order = $request->get('order', 'nama');
        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 500);

        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('nama', 'ilike', '%' . $search . '%')
                    ->orWhere('alamat', 'ilike', '%' . $search . '%')
                    ->orWhere('kota', 'ilike', '%' . $search . '%');
            });
        }

        if ($params) {
            foreach (json_decode($params) as $key => $val) {
                if ($val == '') {
                    continue;
                }

                switch ($key) {
                    case "nama":
                        $models = $models->where('nama', 'ilike', '%' . $val . '%');
                        break;
                    case "alamat":
                        $models = $models->where('alamat', 'ilike', '%' . $val . '%');
                        break;
                    case "kota":
                        $models = $models->where('kota', 'ilike', '%' . $val . '%');
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
                case 'nama':
                    $models = $models->orderBy('nama', $order_direction);
                    break;
                case 'alamat':
                    $models = $models->orderBy('alamat', $order_direction);
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

    public function store(StorePerujukRequest $request)
    {
        DB::beginTransaction();
        try {
            $perujuk = new Perujuk();
            $perujuk->nama = $request->get('nama');
            $perujuk->alamat = $request->get('alamat');
            $perujuk->kota = $request->get('kota');
            $perujuk->save();
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'success', 'result' => $perujuk]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'error']);
        }
    }

    public function show($id)
    {
        try {
            $models = Perujuk::find($id);
            return response()->json(['status' => 200, 'message' => 'success', 'result' => $models]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'error', 'result' => []]);
        }
    }

    public function update(UpdatePerujukRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $perujuk = Perujuk::find($id);
            $perujuk->nama = $request->get('nama');
            $perujuk->alamat = $request->get('alamat');
            $perujuk->kota = $request->get('kota');
            $perujuk->save();
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'success', 'result' => $perujuk]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'error']);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            Perujuk::find($id)->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'success', 'result' => []]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'error']);
        }
    }
}
