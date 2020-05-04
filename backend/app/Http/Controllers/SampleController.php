<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\User as Sample;
use Illuminate\Validation\Rule;
use App\Models\Sampel;
use App\Models\PemeriksaanSampel;
use App\Models\Register;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use DB;
use Validator;

class SampleController extends Controller
{
    public function getData(Request $request)
    {
        $models = Sampel::query();
        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'name');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('nomor_sampel','ilike','%'.$search.'%');
                //    ->orWhere('email','ilike','%'.$search.'%');
            });
        }

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        if ($params) {
            foreach (json_decode($params) as $key => $val) {
                if ($val == '') continue;
                switch($key) {
                    case 'sampel_status':
                        if ($val == 'sample_sent') {
                            $models->whereIn('sampel_status', [
                                'sample_taken',
                                'extraction_sample_extracted',
                                'extraction_sample_reextract',
                                'extraction_sample_sent',
                                'pcr_sample_received',
                                'pcr_sample_analyzed',
                                'sample_verified',
                                'sample_valid',
                                'sample_invalid',
                            ]);
                        } else {
                            $models->where('sampel_status', $val);
                        }
                        break;
                    case 'waktu_sample_taken':
                        $tgl = date('Y-m-d', strtotime($val));
                        $models->whereBetween('waktu_sample_taken', [$tgl.' 00:00:00',$tgl.' 23:59:59']);
                        break;
                    case 'petugas_pengambil':
                        $models->where('petugas_pengambilan_sampel', $val);
                        break;
                    //case 'is_mandiri':
                    //    if($val=="Ya") {
                    //        $models = $models->whereNull('pengambilan_sampel_id');
                    //    }else {
                    //        $models = $models->whereNotNull('pengambilan_sampel_id');
                    //    }
                    //    break;
                    default:
                        // $models = $models->where($key,$val);
                        break;
                }
            }
        }
        $count = $models->count();

        if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'asc';

            switch ($order) {
                case 'xx':
                    // $models = $models->orderBy($order,$order_direction);
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
            'pen_sampel_sumber.required' => 'Sumber sampel wajib diisi',
            'samples.*.petugas_pengambil' => 'required'
        ], [
            'pen_sampel_sumber.required' => 'Sumber sampel wajib diisi',
            'samples.*.sam_jenis_sampel.required'=> 'Jenis sampel wajib diisi.',
            'samples.*.sam_jenis_sampel.integer'=> 'Tipe data tidak valid',
            'samples.*.sam_jenis_sampel.min'=> 'Jumlah karakter minimal :min dijit.',
            'samples.*.sam_jenis_sampel.max'=> 'Jumlah karakter maksimal :max dijit.',
            'samples.*.nomorsampel.required' => 'Nomor sampel wajib diisi.',
            'samples.*.petugas_pengambil.required' => 'Petugas Pengambil tidak boleh kosong'            
        ]);

        foreach($request->samples as $key => $item) {
            if (isset($item['sam_jenis_sampel']) && $item['sam_jenis_sampel'] == 999999) {
                $v->after(function ($validator) use ($item, $key) {
                    if (empty($item['sam_namadiluarjenis'])) {
                        $validator->errors()->add("samples.$key.sam_namadiluarjenis", 'Jenis sampel belum diisi');
                    }
                });
            }
        }

        $v->validate();
        $model = new PengambilanSampel;
        $model->sumber_sampel = $request->get('pen_sampel_sumber');
        $model->penerima_sampel = $request->get('pen_penerima_sampel');
        $model->catatan = $request->get('pen_catatan');
        $model->sampel_diterima = true;
        $model->save();

        foreach($request->samples as $key=>$item){
            $sm = Sampel::where('nomor_sampel',$item['nomorsampel'])->first();
            if(!$sm) {
                $sm = new Sampel;
            }
            $jenis = DB::table('jenis_sampel')->where('id',$item['sam_jenis_sampel'])->first();
            $sm->nomor_sampel = $item['nomorsampel'];
            $sm->jenis_sampel_id = $item['sam_jenis_sampel'];
            $sm->jenis_sampel_nama = $jenis->nama;
            $sm->tanggal_pengambilan_sampel = $item['tanggalsampel'];
            $sm->jam_pengambilan_sampel = $item['pukulsampel'];
            $sm->petugas_pengambilan_sampel = $item['petugas_pengambil'];
            $sm->pengambilan_sampel_id = $model->id;
            $sm->waktu_waiting_sample = date('Y-m-d H:i:s');
            $sm->updateState('sample_taken');
            $sm->save();
        }
        // $model = new Sample;
        // $model->save();
        
        return response()->json(['status'=>201,'message'=>'Berhasil menambahkan sampel','result'=>[]]);
    }


    public function getUpdate(Request $request, $id)
    {
        $model = Sampel::where('nomor_sampel',$id)->first();
        // dd($id);
        $models = PengambilanSampel::where('id',$model->pengambilan_sampel_id)->first();
        if (!$models) {
            $models = new PengambilanSampel;
            $models->diterima_dari_faskes = false;
            $models->sampel_diterima = false;
            $models->sampel_rdt = false;
            $models->save();
            $model->pengambilan_sampel_id = $models->id;
            $models->sampels = Sampel::where('nomor_sampel',$id)
                                    ->select('id as id_sampel','nomor_sampel as nomorsampel',
                                    'petugas_pengambilan_sampel as petugas_pengambil',
                                    'tanggal_pengambilan_sampel as tanggalsampel',
                                    'jam_pengambilan_sampel as pukulsampel',
                                    'jenis_sampel_id as sam_jenis_sampel')->get();
        } else {
            $models->sampels = Sampel::where('pengambilan_sampel_id',$models->id)
                                    ->select('id as id_sampel','nomor_sampel as nomorsampel',
                                    'petugas_pengambilan_sampel as petugas_pengambil',
                                    'tanggal_pengambilan_sampel as tanggalsampel',
                                    'jam_pengambilan_sampel as pukulsampel',
                                    'jenis_sampel_id as sam_jenis_sampel')->get();
        }
        return response()->json(['status'=>200,'message'=>'success','result'=>$models]);
    }

    public function getById(Request $request, $id)
    {
        $model = Sampel::where('nomor_sampel',$id)->first();
        return response()->json(['status'=>200,'message'=>'success','result'=>$model]);
    }

    public function delete(Request $request, $id)
    {
        try{
            $model = Sampel::where('id',$id)->first();
            $model->delete();
            return response()->json(['status'=>200,'message'=>'Berhasil menghapus data ','result'=>[]]);
        }catch(\Exception $ex) {
            return response()->json(['status'=>400,'message'=>'Gagal menghapus data, terjadi kesalahan server','result'=>$ex->getMessage()]);
        }
    }

    public function storeUpdate(Request $request, $id)
    {
        $v = Validator::make($request->all(),[
            'samples.*.sam_jenis_sampel' => 'required|integer|min:1|max:12',
            'samples.*.nomorsampel' => 'required',
            'pen_sampel_sumber.required' => 'Sumber sampel wajib diisi',
            'samples.*.petugas_pengambil' => 'required'
        ], [
            'pen_sampel_sumber.required' => 'Sumber sampel wajib diisi',
            'samples.*.sam_jenis_sampel.required'=> 'Jenis sampel wajib diisi.',
            'samples.*.sam_jenis_sampel.integer'=> 'Tipe data tidak valid',
            'samples.*.sam_jenis_sampel.min'=> 'Jumlah karakter minimal :min dijit.',
            'samples.*.sam_jenis_sampel.max'=> 'Jumlah karakter maksimal :max dijit.',
            'samples.*.nomorsampel.required' => 'Nomor sampel wajib diisi.',
            'samples.*.petugas_pengambil.required' => 'Petugas Pengambil tidak boleh kosong'            
        ]);

        foreach($request->samples as $key => $item) {
            if (isset($item['sam_jenis_sampel']) && $item['sam_jenis_sampel'] == 999999) {
                $v->after(function ($validator) use ($item, $key) {
                    if (empty($item['sam_namadiluarjenis'])) {
                        $validator->errors()->add("samples.$key.sam_namadiluarjenis", 'Jenis sampel belum diisi');
                    }
                });
            }
        }

        $v->validate();
        $model = PengambilanSampel::where('id',$id)->first();
        $model->sumber_sampel = $request->get('pen_sampel_sumber');
        $model->penerima_sampel = $request->get('pen_penerima_sampel');
        $model->catatan = $request->get('pen_catatan');
        $model->save();

        foreach($request->samples as $key=>$item){
            $sm = Sampel::where('id',$item['id_sampel'])->first();
            if(!$sm) {
                $sm = new Sampel;
            }
            $jenis = DB::table('jenis_sampel')->where('id',$item['sam_jenis_sampel'])->first();
            $sm->nomor_sampel = $item['nomorsampel'];
            $sm->jenis_sampel_id = $item['sam_jenis_sampel'];
            $sm->jenis_sampel_nama = $jenis->nama;
            $sm->tanggal_pengambilan_sampel = $item['tanggalsampel'];
            $sm->jam_pengambilan_sampel = $item['pukulsampel'];
            $sm->petugas_pengambilan_sampel = $item['petugas_pengambil'];
            $sm->pengambilan_sampel_id = $model->id;
            if ($sm->sampel_status == 'waiting_sample') {
                $sm->updateState('sample_taken');
            }
            $sm->save();
        }
    }
}
