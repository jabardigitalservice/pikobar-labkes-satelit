<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\PengambilanSampel;
use Illuminate\Http\Request;

class PengambilanListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function listDikirim(Request $request)
    {
        $models = PengambilanSampel::query()->with('sampel');
        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'name');

        // if ($search != '') {
        //     $models = $models->where(function($q) use ($search) {
        //         $q->where('name','ilike','%'.$search.'%')
        //            ->orWhere('email','ilike','%'.$search.'%');
        //     });
        // }

        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        // if ($order) {
        //     $order_direction = $request->get('order_direction','asc');
        //     if (empty($order_direction)) $order_direction = 'asc';

        //     switch ($order) {
        //         case 'xx':
        //             $models = $models->orderBy($order,$order_direction);
        //         default:
        //             break;
        //     }
        // }

        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();
        
        // format data
        foreach ($models as &$model) {
            $model->sampel = $model->sampel;
        }

        $result = [
            'data' => $models,
            'count' => $count
        ];

        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     *
     *  @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function listSampelRegister(Request $request)
    {
        
    }

    
}
