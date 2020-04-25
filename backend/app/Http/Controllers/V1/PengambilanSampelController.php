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
     * @param  \App\Models\PengambilanSampel  $pengambilan
     * @return \Illuminate\Http\Response
     */
    public function show(PengambilanSampel $pengambilan)
    {
        return new PengambilanSampelResource($pengambilan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \EditPengambilanRequest\EditPengambilanRequest  $request
     * @param  \App\Models\PengambilanSampel  $pengambilan
     * @return \Illuminate\Http\Response
     */
    public function update(EditPengambilanRequest $request, PengambilanSampel $pengambilan)
    {
        $request->validated();
        
        DB::beginTransaction();
        try {

            $pengambilan->update($request->except(['sampel']));

            $sampelRequest = $request->only('sampel');

            // Update related sampel
            foreach ($sampelRequest['sampel'] as $key => $sampelRequest) {
                $existingSampel = $pengambilan->sampel()
                    ->where('id', $sampelRequest['id'])
                    ->first();

                if ($existingSampel) {
                    $existingSampel->update($sampelRequest);
                }
            }
            
            DB::commit();

            return new PengambilanSampelResource($pengambilan);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PengambilanSampel  $pengambilan
     * @return \Illuminate\Http\Response
     */
    public function destroy(PengambilanSampel $pengambilan)
    {
        DB::beginTransaction();
        try {

            $pengambilan->sampel()->delete();

            $pengambilan->delete();
            
            DB::commit();

            return response()->json([
                'status'=> true,
                'message'=> __("Berhasil menghapus data Pengambilan Sampel")
            ]);


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function destroySampel(Sampel $sampel)
    {
        DB::beginTransaction();
        try {

            $sampel->delete();
            
            DB::commit();

            return response()->json([
                'status'=> true,
                'message'=> __("Berhasil menghapus data sampel")
            ]);


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
