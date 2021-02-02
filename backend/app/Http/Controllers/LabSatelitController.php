<?php

namespace App\Http\Controllers;

use App\Models\LabSatelit;
use Illuminate\Http\Request;

class LabSatelitController extends Controller
{
    public function listLabSatelit(Request $request)
    {
        // $models = LabSatelit::with(['register','sample']);
        $models = LabSatelit::select('*');
        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order  = $request->get('order', 'nama');

        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('nama', 'ilike', '%'.$search.'%')
                   ->orWhere('alamat', 'ilike', '%'.$search.'%');
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
        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        $result = [
            'data' => $models,
            'count' => $count
        ];

        return response()->json($result);
    }

    public function saveLabSatelit(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|unique:lab_satelit,nama',
            'alamat' => 'max:255',
            'longitude' => 'numeric',
            'latitude' => 'numeric',
        ]);

        $data = new LabSatelit;
        $data->nama = $request->get('nama');
        $data->alamat = $request->get('alamat');
        $data->longitude = $request->get('longitude');
        $data->latitude = $request->get('latitude');
        $data->save();

        return response()->json(['status'=>201,'message'=>'Berhasil menambahkan Lab Satelit','result'=>[]]);
    }

    public function deleteLabSatelit(Request $request, $id)
    {
        try {
            $data = LabSatelit::where('id', $id)->first();
            $data->delete();
            return response()->json(['status'=>200,'message'=>'Berhasil menghapus data Lab Satelit','result'=>[]]);
        } catch (\Exception $ex) {
            return response()->json(['status'=>400,'message'=>'Gagal menghapus data, terjadi kesalahan server','result'=>$ex->getMessage()]);
        }
    }

    public function updateLabSatelit(Request $request, $id)
    {

        $this->validate($request, [
            'nama' => 'required|unique:lab_satelit,nama,'.$id,
            'alamat' => 'max:255',
            'longitude' => 'numeric',
            'latitude' => 'numeric',
        ]);

        // dd($id);
        $data = LabSatelit::where('id', $id)->first();
        $data->nama = $request->get('nama');
        $data->alamat = $request->get('alamat');
        $data->longitude = $request->get('longitude');
        $data->latitude = $request->get('latitude');
        $data->save();

        return response()->json(['status'=>200,'message'=>'Berhasil mengubah data LabSatelit','result'=>[]]);
    }

    public function showUpdate(Request $request, $id)
    {
        $data = LabSatelit::where('id', $id)->first();
        return response()->json(['status'=>200,'message'=>'success','result'=>$data]);
    }
}
