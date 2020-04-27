<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sampel;
use App\Models\StatusSampel;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $models = Sampel::query()->whereHas('logs')->whereHas('pemeriksaanSampel'); // 'pcr_sample_analyzed'

        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'name');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('nomor_register','ilike','%'.$search.'%')
                   ->orWhere('nomor_sampel','ilike','%'.$search.'%');
            }); 
        }

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val == '' || is_array($val) && count($val) == 0) continue;
                switch ($key) {
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
                default:
                    break;
            }
        }

        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        // format data
        foreach ($models as &$model) {
            $model->register = $model->register ?? null;
            $model->pasien = optional($model->register)->pasiens()->first();
            $model->pemeriksaanSampel = $model->pemeriksaanSampel ?? null;
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
        $result = $sampel->load(['pemeriksaanSampel', 'status'])->toArray();
        $pasien = optional($sampel->register->pasiens())->first();

        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=> $result + ['pasien'=> optional($pasien)->toArray()]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sampelStatusList()
    {
        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=> StatusSampel::where('sampel_status', '!=', 'sample_verified')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function updateStatusSampel(Request $request, Sampel $sampel)
    {
        $request->validate([
            'sampel_status'=> 'required|exists:status_sampel,sampel_status'
        ], $request->only(['sampel_status']));

        $sampel->update([
            'sampel_status'=> $request->input('sampel_status')
        ]);

        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=> Sampel::find($sampel->id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
