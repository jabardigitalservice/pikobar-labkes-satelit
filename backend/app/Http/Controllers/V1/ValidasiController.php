<?php

namespace App\Http\Controllers\V1;

use App\Events\SampelValidatedEvent;
use App\Http\Controllers\Controller;
use App\Models\PemeriksaanSampel;
use App\Models\Sampel;
use App\Models\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $models = Sampel::query()->whereHas('logs')
            ->whereHas('pemeriksaanSampel')
            ->where('sampel_status', '!=', 'sample_invalid')
            ->whereIn('sampel_status', ['sample_verified']); // 'pcr_sample_analyzed'

        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'updated_at');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('nomor_register','ilike','%'.$search.'%')
                   ->orWhere('nomor_sampel','ilike','%'.$search.'%')
                   ->orWhereHas('pemeriksaanSampel', function($query) use ($search){
                       $query->where('kesimpulan_pemeriksaan', 'ilike','%'.$search.'%');
                   })
                   ->orWhereHas('register', function($query) use ($search){
                        $query->whereHas('pasiens', function($query) use ($search) {
                            $query->where('nama_depan', 'ilike','%'.$search.'%')
                                ->orWhere('nama_belakang', 'ilike','%'.$search.'%')
                                ->orWhere('no_ktp', 'ilike','%'.$search.'%')
                                ->orWhere('no_sim', 'ilike','%'.$search.'%')
                                ->orWhere('no_kk', 'ilike','%'.$search.'%')
                                ->orWhereHas('kota', function($query) use ($search){
                                    $query->where('nama', 'ilike','%'.$search.'%');
                                });
                            
                        });
                    });
            });  
        }

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) continue;
                switch ($key) {
                    case 'kesimpulan_pemeriksaan':
                        $models->whereHas('pemeriksaanSampel', function($query) use ($val){
                            $query->where('kesimpulan_pemeriksaan', $val);
                        });
                        break;
                    default:
                        break;
                }
            }
        }

        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'asc';

            switch ($order) {
                case 'updated_at':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'nomor_register':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'pasien_nama':
                    $models = $models->leftJoin('register', 'sampel.register_id', '=', 'register.id')
                        ->leftJoin('pasien_register', 'register.id', '=', 'pasien_register.register_id')
                        ->leftJoin('pasien', 'pasien_register.pasien_id', '=', 'pasien.id')
                        ->select('sampel.*')
                        ->addSelect('pasien.nama_depan')
                        ->distinct()
                        ->orderBy('nama_depan', $order_direction);
                    break;
                case 'nomor_sampel':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'kesimpulan_pemeriksaan':
                    // $models = $models->with(['pemeriksaanSampel'=> function($query){
                    //     $query->orderBy('kesimpulan_pemeriksaan', 'asc');
                    // }]);
                    break;
                default:
                    break;
            }
        }

        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        // format data
        foreach ($models as &$model) {
            $model->register = $model->register ?? null;
            $model->pasien = optional($model->register)->pasiens()->with('kota')->first();
            $model->pemeriksaanSampel = $model->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first() ?? null;
        }

        return response()->json([
            'data'=> $models,
            'count'=> $count
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function indexValidated(Request $request)
    {
        $models = Sampel::query()->whereHas('logs')
            ->whereHas('pemeriksaanSampel')
            ->where('sampel_status', '!=', 'sample_invalid')
            ->whereIn('sampel_status', ['sample_valid']); // 'pcr_sample_analyzed'

        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'updated_at');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('nomor_register','ilike','%'.$search.'%')
                   ->orWhere('nomor_sampel','ilike','%'.$search.'%')
                   ->orWhereHas('pemeriksaanSampel', function($query) use ($search){
                       $query->where('kesimpulan_pemeriksaan', 'ilike','%'.$search.'%');
                   })
                   ->orWhereHas('register', function($query) use ($search){
                        $query->whereHas('pasiens', function($query) use ($search) {
                            $query->where('nama_depan', 'ilike','%'.$search.'%')
                                ->orWhere('nama_belakang', 'ilike','%'.$search.'%')
                                ->orWhere('no_ktp', 'ilike','%'.$search.'%')
                                ->orWhere('no_sim', 'ilike','%'.$search.'%')
                                ->orWhere('no_kk', 'ilike','%'.$search.'%')
                                ->orWhereHas('kota', function($query) use ($search){
                                    $query->where('nama', 'ilike','%'.$search.'%');
                                });
                            
                        });
                    });
            });  
        }

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) continue;
                switch ($key) {
                    case 'kesimpulan_pemeriksaan':
                        $models->whereHas('pemeriksaanSampel', function($query) use ($val){
                            $query->where('kesimpulan_pemeriksaan', $val);
                        });
                        break;
                    default:
                        break;
                }
            }
        }

        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        if ($order) {
            $order_direction = $request->get('order_direction','desc');
            if (empty($order_direction)) $order_direction = 'desc';

            switch ($order) {
                case 'updated_at':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'nomor_register':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'pasien_nama':
                    $models = $models->leftJoin('register', 'sampel.register_id', '=', 'register.id')
                        ->leftJoin('pasien_register', 'register.id', '=', 'pasien_register.register_id')
                        ->leftJoin('pasien', 'pasien_register.pasien_id', '=', 'pasien.id')
                        ->select('sampel.*')
                        ->addSelect('pasien.nama_depan')
                        ->distinct()
                        ->orderBy('nama_depan', $order_direction);
                    break;
                case 'nomor_sampel':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'kesimpulan_pemeriksaan':
                    // $models = $models->with(['pemeriksaanSampel'=> function($query){
                    //     $query->orderBy('kesimpulan_pemeriksaan', 'asc');
                    // }]);
                    break;
                default:
                    $models = $models->orderBy('updated_at', $order_direction);
                    break;
            }
        }

        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        // format data
        foreach ($models as &$model) {
            $model->register = $model->register ?? null;
            $model->pasien = optional($model->register)->pasiens()->with('kota')->first();
            $model->pemeriksaanSampel = $model->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first() ?? null;
        }

        return response()->json([
            'data'=> $models,
            'count'=> $count
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function show(Sampel $sampel)
    {
        $result = $sampel->load(['pemeriksaanSampel', 'status', 'validator'])->toArray();
        $pasien = optional($sampel->register->pasiens())->first();

        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=> $result + [
                'pasien'=> optional($pasien)->toArray(),
                'last_pemeriksaan_sampel'=> $sampel->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first()
            ]
        ]);
    }

    public function getValidator()
    {
        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> Validator::whereIsActive(true)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function updateToValidate(Request $request, Sampel $sampel)
    {
        $request->validate([
            'validator'=> 'required|exists:validator,id',
            'catatan_pemeriksaan'=> 'nullable|max:255',
            'last_pemeriksaan_id'=> 'required|exists:pemeriksaansampel,id'
        ], $request->only(['validator', 'catatan_pemeriksaan', 'last_pemeriksaan_id']));

        DB::beginTransaction();
        try {

            PemeriksaanSampel::find($request->input('last_pemeriksaan_id'))->update([
                'catatan_pemeriksaan'=> $request->input('catatan_pemeriksaan')
            ]);

            $sampel->update([
                'validator_id'=> $request->input('validator'),
                'sampel_status'=> 'sample_valid',
                'waktu_sample_valid'=> now()
            ]);

            DB::commit();

            event(new SampelValidatedEvent($sampel));
            
            return response()->json([
                'status'=>200,
                'message'=>'success',
                'data'=> Sampel::find($sampel->id)
            ]);
            
            

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkValidate(Request $request)
    {
        $request->validate([
            'sampels'=> 'required|array',
            'validator'=> 'required|exists:validator,id'
        ], $request->all());

        
    }
}
