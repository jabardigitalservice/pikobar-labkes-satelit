<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteSampelBulkRequest;
use App\Models\Register;
use App\Models\Sampel;

class DeleteSampelBulkController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(DeleteSampelBulkRequest $request)
    {
        Register::destroy($request->input('id'));
        return response()->json(['message' => 'Berhasil menghapus data sampel']);
    }
}
