<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePengambilanRequest;
use App\Http\Requests\EditPengambilanRequest;
use App\Http\Resources\PengambilanSampelResource;
use App\Models\PengambilanSampel;
use App\Models\Sampel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengambilanSampelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreatePengambilanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePengambilanRequest $request)
    {
        $request->validated();
        
        DB::beginTransaction();
        try {

            $pengambilanSampel = PengambilanSampel::create($request->except('sampel'));

            $pengambilanSampel->sampel()->saveMany($this->getSampelToCreate($request));
            
            DB::commit();

            return new PengambilanSampelResource($pengambilanSampel);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function getSampelToCreate(Request $request) : array
    {
        $arrayInstance = [];
        $arrayRequestSampel = $request->only('sampel');

        foreach ($arrayRequestSampel['sampel'] as $key => $sampel) {
            array_push($arrayInstance, new Sampel($sampel));
        }

        return $arrayInstance;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PengambilanSampel  $pengambilanSampel
     * @return \Illuminate\Http\Response
     */
    public function show(PengambilanSampel $pengambilanSampel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \EditPengambilanRequest\EditPengambilanRequest  $request
     * @param  \App\Models\PengambilanSampel  $pengambilanSampel
     * @return \Illuminate\Http\Response
     */
    public function update(EditPengambilanRequest $request, PengambilanSampel $pengambilanSampel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PengambilanSampel  $pengambilanSampel
     * @return \Illuminate\Http\Response
     */
    public function destroy(PengambilanSampel $pengambilanSampel)
    {
        //
    }
}
