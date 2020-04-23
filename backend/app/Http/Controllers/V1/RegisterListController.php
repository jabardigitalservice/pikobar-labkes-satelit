<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Register;
use Illuminate\Http\Request;

class RegisterListController extends Controller
{
    public function index(Request $request)
    {
        $models = Register::query();
        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'name');

        if ($search != '') {
            $models = $models->where(function($query) use ($search) {
                $query->where('nomor_register','ilike','%'.$search.'%')
                   ->orWhere('nama_dokter','ilike','%'.$search.'%')
                   ->orWhereHas('fasyankes', function($query) use ($search){
                        $query->where('nama', 'ilike', '%'.$search.'%')
                            ->orWhereHas('kota', function($query) use ($search){
                                $query->where('nama', 'ilike','%'.$search.'%' );
                            });
                    });
            });
        }

        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        if ($order) {

            $order_direction = $request->get('order_direction','asc');

            if (empty($order_direction)) $order_direction = 'asc';

            switch ($order) {
                case 'tanggal_input':
                    $models = $models->orderBy('updated_at', $order_direction);
                default:
                    break;
            }
        }

        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        // format data
        foreach ($models as &$model) {
            $pasien = $model->pasiens->first();

            $model->informasi_pasien = [
                'nomor_register'=> $model->nomor_register,
                'nik_pasien'=> $pasien->no_ktp ?? $pasien->no_sim,
                'nama_pasien'=> implode(' ', $pasien->only(['nama_depan', 'nama_belakang']))
            ];

            $model->pengirim_register = [
                'fasyankes_nama'=> $model->fasyankes->nama,
                'fasyankes_kota'=> $model->fasyankes->kota->nama,
                'dokter_penanggung_jawab'=> $model->nama_dokter
            ];

            $model->tanggal_input = $model->updated_at->format('Y-m-d');
        }

        $result = [
            'data' => $models->map(function($item){
                return $item->only(['id', 'informasi_pasien', 'pengirim_register', 'tanggal_input']);
            }),
            'count' => $count
        ];

        return response()->json($result);

    }
}
