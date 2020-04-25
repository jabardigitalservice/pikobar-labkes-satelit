<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SampelResource;
use App\Models\Sampel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SampelController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all(), new Sampel)->validate();

        $sampelBaru = Sampel::create($request->all());

        return new SampelResource($sampelBaru);
    }

    protected function validator(array $data, Sampel $sampel)
    {
        $unique = Rule::unique('sampel', 'nomor_barcode');

        if ($sampel->getKey()) {
            $unique->ignore($sampel->getKey(), 'id');
        }

        $rules = [
            'jenis_sampel'=> ['max:100'],
            'petugas_pengambil_sampel'=> ['max:255'],
            'tanggal_sampel'=> ['nullable', 'date', 'date_format:Y-m-d'],
            'waktu_sampel'=> ['nullable', 'date_format:H:i'],
            'nomor_barcode'=> ['required', 'max:255', $unique],
            'nama_diluar_jenis'=> ['nullable'],
            'status'=> ['required', 'max:50']
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function show(Sampel $sampel)
    {
        return new SampelResource($sampel);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $barcode
     * @return \Illuminate\Http\Response
     */
    public function showByBarcode(string $barcode)
    {
        $sampel = Sampel::whereNomorBarcode($barcode)->firstOrFail();
        
        return new SampelResource($sampel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sampel $sampel)
    {
        $this->validator($request->all(), $sampel)->validate();
        
        $sampel->update($request->all());

        return new SampelResource($sampel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sampel $sampel)
    {
        $sampel->delete();

        return response()->json([
            'status'=> true,
            'message'=> __("Berhasil menghapus sampel.")
        ]);
    }
}
