<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as Sample;
use Illuminate\Validation\Rule;
use Validator;

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
        $v = Validator::make($request->all(),[
            'samples.*.sam_jenis_sampel' => 'required|integer|min:1|max:12',
            'samples.*.nomorsampel' => 'required',
            'pen_sampel_diambil.required' => 'Keterangan sampel diambil wajib diisi',
            'pen_nomor_ekstraksi.required' => 'Nomor Ekstraksi wajib diisi',
            'pen_nomor_ekstraksi.min' => 'Jumlah karakter minimal :min dijit.',
            'pen_nomor_ekstraksi.max' => 'Jumlah karakter maksimal :max dijit.',
        ], [
            'pen_sampel_diambil.required' => 'Keterangan sampel diambil wajib diisi',
            'pen_nomor_ekstraksi.required' => 'Nomor Ekstraksi wajib diisi',
            'pen_nomor_ekstraksi.min' => 'Jumlah karakter minimal :min dijit.',
            'pen_nomor_ekstraksi.max' => 'Jumlah karakter maksimal :max dijit.',

            'samples.*.sam_jenis_sampel.required'=> 'Jenis sampel wajib diisi.',
            'samples.*.sam_jenis_sampel.integer'=> 'Tipe data tidak valid',
            'samples.*.sam_jenis_sampel.min'=> 'Jumlah karakter minimal :min dijit.',
            'samples.*.sam_jenis_sampel.max'=> 'Jumlah karakter maksimal :max dijit.',

            'samples.*.nomorsampel.required' => 'Nomor sampel wajib diisi.',            
        ]);

        foreach($request->samples as $key => $item) {
            if (isset($item['sam_jenis_sampel']) && $item['sam_jenis_sampel'] == 12) {
                $v->after(function ($validator) use ($item, $key) {
                    if (empty($item['sam_namadiluarjenis'])) {
                        $validator->errors()->add("samples.$key.sam_namadiluarjenis", 'Jenis sampel belum diisi');
                    }
                });
            }
        }

        $v->validate();

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
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Jumlah karakter nama maksimal :max dijit.',

            'modelname.required' => 'Nama model wajib diisi',
            'modelname.unique' => 'Nama model harus unik, nama yang sama telah diambil.',
            'modelname.max' => 'Jumlah karakter nama model maksimal :max dijit.',

            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email yang sama telah diambil.',
            'email.email' => 'Email tidak valid.',

            'role_id.required' => 'ID Role wajib diisi',

            'password.required' => 'Password wajib diisi',
            'password.min' => 'Jumlah karakter password minimal :min dijit.',

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
