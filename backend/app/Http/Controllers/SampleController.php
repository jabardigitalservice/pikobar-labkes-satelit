<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as Sample;
use Illuminate\Validation\Rule;
class SampleController extends Controller
{


    public function getData(Request $request)
    {
        $models = Sample::query();
        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'name');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('name','ilike','%'.$search.'%')
                   ->orWhere('email','ilike','%'.$search.'%');
            });
        }
        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

         if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'asc';

            switch ($order) {
                case 'xx':
                    $models = $models->orderBy($order,$order_direction);
                default:
                    break;
            }
        }
        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();
        
        // format data
        foreach ($models as &$model) {
            $model->sam_barcodenomor_sampel = rand(10000,99999);
        }

        $result = [
            'data' => $models,
            'count' => $count
        ];

        return response()->json($result);
    }

    public function add(Request $request)
    {
        $this->validate($request,[
            'pen_nomor_ekstraksi' => 'required|min:2|max:255',
        ]);

        // $model = new Sample;
        // $model->save();
        
        return response()->json(['status'=>201,'message'=>'Berhasil menambahkan sampel','result'=>[]]);
    }

    public function delete(Request $request,$id)
    {
        try{
            $model = Sample::where('id',$id)->first();
            $model->delete();
            return response()->json(['status'=>200,'message'=>'Berhasil menghapus data ','result'=>[]]);
        }catch(\Exception $ex) {
            return response()->json(['status'=>400,'message'=>'Gagal menghapus data, terjadi kesalahan server','result'=>$ex->getMessage()]);
        }
        
    }

    public function update(Request $request, $id)
    {

        $this->validate($request,[
            'name' => 'required|max:255',
            'modelname' => [
                'required',
                'max:80',
                Rule::unique('models')->ignore($id,'id')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('models')->ignore($id, 'id')
            ],
            'role_id' => 'required',
            'password' => 'required|min:6',
        ]);

        // dd($id);
        $model = Sample::where('id',$id)->first();
        $model->name = $request->get('name');
        $model->modelname = $request->get('modelname');
        $model->email =  $request->get('email');
        $model->role_id = $request->get('role_id');
        $model->password = bcrypt($request->get('password'));
        $model->save();

        return response()->json(['status'=>200,'message'=>'Berhasil mengubah data ','result'=>[]]);
    }

    public function showUpdate(Request $request, $id)
    {
        $model = Sample::where('id',$id)->first();
        return response()->json(['status'=>200,'message'=>'success','result'=>$model]);
    }
}
