<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Pasien;
use App\Models\Register;
use App\Models\PasienRegister;
use App\Models\PengambilanSampel;
use App\Models\Sampel;
use DateTime;
use App\Exports\RegisMandiriExport;
use Illuminate\Support\Facades\Auth;

class Registrasisampel extends Controller
{ 
    public function getData(Request $request)
    {
        $models = Register::leftJoin('pasien_register','register.id','pasien_register.register_id')
                    ->leftJoin('pasien','pasien.id','pasien_register.pasien_id')
                    ->leftJoin('kota','kota.id','pasien.kota_id');
        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'name');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('pasien.nama_lengkap','ilike','%'.$search.'%')
                   ->orWhere('pasien.nik','ilike','%'.$search.'%');
            });
        }

        
        if (Auth::user()->lab_satelit_id !=null) {
            $models->where('register.lab_satelit_id',Auth::user()->lab_satelit_id);
        }
        
        if ($params) {
            foreach (json_decode($params) as $key => $val) {
                if ($val == '') continue;
                switch($key) {
                    case "nama_pasien": 
                        $models = $models->where('pasien.nama_lengkap','ilike','%'.$val.'%');
                    break;
                    case "nik":
                        $models = $models->where('pasien.nik','ilike','%'.$val.'%');
                    break;
                    case "nomor_sampel":
                        $sampel = Sampel::where('nomor_sampel',$val)->pluck('register_id');
                        $models = $models->whereIn('register.id',$sampel);
                    break;
                    case "start_date":
                        $models = $models->whereDate('register.created_at','>=',date('Y-m-d',strtotime($val)));
                    break;
                    case "end_date":
                        $models = $models->whereDate('register.created_at','<=',date('Y-m-d',strtotime($val)));
                    break;
                    case "kota":
                        $models = $models->where('pasien.kota_id',$val);
                    break;
                    case "instansi_pengirim":
                        $models = $models->where('register.instansi_pengirim',$val);
                    break;
                    default:
                        $models = $models->where($key,$val);
                        break;
                }
            }
        }

        $count = $models->count();
        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        
        if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'desc';

            switch ($order) {
                case 'nama_lengkap':
                case 'nama_pasien':
                    $models = $models->orderBy('pasien.nama_lengkap',$order_direction);
                break;
                case 'created_at':
                case 'tgl_input':
                    $models = $models->orderBy('register.created_at',$order_direction);
                break;
                case 'nomor_register':
                    $models = $models->orderBy('register.nomor_register',$order_direction);
                break;
                case 'nama_kota':
                    $models = $models->orderBy('kota.nama',$order_direction);
                break;
                case 'sumber_pasien':
                    $models = $models->orderBy('register.sumber_pasien',$order_direction);
                break;
                case 'nama_rs':
                    $models = $models->orderBy('register.nama_rs',$order_direction);
                break;
                case 'no_sampel':
                break;
                default:
                    break;
            }
        }
        $models = $models->select('pasien.*','kota.nama as nama_kota',
        'register.created_at as tgl_input','pasien_register.*','register.sumber_pasien',
        'register.jenis_registrasi','register.dinkes_pengirim','register.sumber_pasien','register.nama_rs',
        'register.other_nama_rs','register.instansi_pengirim_nama');
        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        foreach($models as &$model) {
            $model->samples = Sampel::where('register_id',$model->register_id)->get();
            $tahun = $model->usia_tahun == null ? 0 : $model->usia_tahun;
            $bulan = $model->usia_bulan == null ? 0 : $model->usia_bulan;
            $model->usia =  $tahun. ' Tahun ' . $bulan . ' Bulan ';
        }

        $result = [
            'data' => $models,
            'count' => $count
        ];

        return response()->json($result);
    }


    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date'=> 'nullable', // 'date|date_format:Y-m-d',
            'end_date'=> 'nullable', // 'date|date_format:Y-m-d',
        ]);

        $payload = []; 

        if ($request->has('start_date')) {
            $payload['startDate'] = parseDate($request->input('start_date'));
        }

        if ($request->has('end_date')) {
            // $payload['endDate'] = parseDate($request->input('end_date'));
            $payload['endDate'] = date('Y-m-d',strtotime($request->input('end_date') . "+1 days"));
        }

        return (new RegisMandiriExport($payload))->download('registrasi-mandiri-'.time().'.xlsx');
    }

}
