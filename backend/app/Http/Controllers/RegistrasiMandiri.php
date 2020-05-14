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

class RegistrasiMandiri extends Controller
{ 
    public function getData(Request $request)
    {
        $models = PasienRegister::leftJoin('register','register.id','pasien_register.register_id')
                    ->leftJoin('pasien','pasien.id','pasien_register.pasien_id')
                    ->leftJoin('fasyankes','fasyankes.id','register.fasyankes_id')
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
    
        if ($params) {
            foreach (json_decode($params) as $key => $val) {
                if ($val == '') continue;
                switch($key) {
                    case "nama_pasien": 
                        $models = $models->where('pasien.nama_lengkap','ilike','%'.$val.'%');
                    break;
                    case "nomor_register":
                        $models = $models->where('register.nomor_register','ilike','%'.$val.'%');
                    break;
                    case "nomor_sampel":
                        $sampel = Sampel::where('nomor_sampel',$val)->pluck('register_id');
                        $models = $models->whereIn('register.id',$sampel);
                    break;
                    case "start_date":
                        $models = $models->where('register.created_at','>=',$val);
                    break;
                    case "end_date":
                        $models = $models->where('register.created_at','<=',$val);
                    break;
                    case "sumber_pasien":
                        $models = $models->where('register.sumber_pasien',$val);
                    break;
                    case "sumber_sampel":
                        $models = $models->where('register.nama_rs',$val);
                        // $sampel = PengambilanSampel::where('sumber_sampel',$val)
                        //         ->leftJoin('sampel','sampel.pengambilan_sampel_id','pengambilan_sampel.id')->pluck('sampel.nomor_register');
                        // // $sampel = Sampel::where('sumber_sampel',$val)->pluck('register_id');
                        // $models = $models->whereIn('register.nomor_register',$sampel);
                    break;
                    case "other_nama_rs":
                        $models = $models->where('register.other_nama_rs','ilike','%'.$val.'%');
                    break;
                    case "kota":
                        $models = $models->where('kota.id',$val);
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
                    $models = $modelss->orderBy('register.nama_rs',$order_direction);
                break;
                case 'no_sampel':
                break;
                default:
                    break;
            }
        }
        $models = $models->select('register.nomor_register','pasien.*','kota.nama as nama_kota',
        'register.created_at as tgl_input','pasien_register.*','register.sumber_pasien',
        'register.jenis_registrasi','register.dinkes_pengirim','register.sumber_pasien','register.nama_rs',
        'register.other_nama_rs');
        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        foreach($models as &$model) {
            $model->samples = Sampel::where('register_id',$model->register_id)->get();
            $bday = new DateTime($model->tanggal_lahir); 
            $today = new Datetime(date('Y-m-d'));
            $diff = $today->diff($bday);
            $model->usia = $diff->y . ' Tahun ' . $diff->m . ' Bulan '  . $diff->d . ' Hari ';
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
