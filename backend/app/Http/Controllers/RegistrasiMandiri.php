<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Register;
use App\Models\PasienRegister;
use App\Models\Sampel;
use DateTime;
use App\Exports\RegisMandiriExport;

class RegistrasiMandiri extends Controller
{ 
    public function getData(Request $request)
    {
        $models = PasienRegister::leftJoin('register','register.id','pasien_register.register_id')
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
        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

         if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'asc';

            switch ($order) {
                case 'nama_lengkap':
                    $models = $models->orderBy('pasien.nama_lengkap',$order_direction);
                default:
                    break;
            }
        }
        $models = $models->select('register.nomor_register','pasien.*','kota.nama as nama_kota','register.created_at as tgl_input','pasien_register.*','register.sumber_pasien');
        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        foreach($models as &$model) {
            $model->no_sampel = Sampel::where('register_id',$model->register_id)->pluck('nomor_sampel');
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
            $payload['endDate'] = parseDate($request->input('end_date'));
        }

        return (new RegisMandiriExport($payload))->download('registrasi-mandiri-'.time().'.xlsx');
    }

}
