<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Validator;
use Illuminate\Http\Request;

class ValidatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $models = Validator::query();

        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'updated_at');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('nip','ilike','%'.$search.'%')
                   ->orWhere('nama','ilike','%'.$search.'%');
            });  
        }

        // if ($params) {
        //     $params = json_decode($params, true);
        //     foreach ($params as $key => $val) {
        //         if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) continue;
        //         switch ($key) {
        //             default:
        //                 break;
        //         }
        //     }
        // }

        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'asc';

            switch ($order) {
                default:
                $models = $models->orderBy($order,$order_direction);
                break;
            }
        }

        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        // foreach ($models as &$model) {}

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
        $request->validate([
            'nip'=> 'required|unique:validator,nip',
            'nama'=> 'required|max:255'
        ], $request->all());

        $newValidator = Validator::create($request->only(['nip', 'nama']));

        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> $newValidator
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Validator  $validator
     * @return \Illuminate\Http\Response
     */
    public function show(Validator $validator)
    {
        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> $validator
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Validator  $validator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Validator $validator)
    {
        $request->validate([
            'nama'=> 'required|max:255',
            'is_active'=> 'boolean',
        ], $request->only(['nama', 'is_active']));

        $validator->update([
            'nama'=> $request->input('nama'),
            'is_active'=> $request->input('is_active'),
        ]);

        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> Validator::find($validator->id)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Validator  $validator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Validator $validator)
    {
        if (!$validator->delete()) {
            return response()->json([
                'status'=> 500,
                'message'=> 'error',
                'data'=> $validator
            ]);
        }

        return response()->json([
            'status'=> 200,
            'message'=> 'success',
            'data'=> null
        ]);
    }
}
