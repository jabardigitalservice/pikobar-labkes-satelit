<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteSampelBulkRequest;
use App\Models\Register;

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
        $register = Register::whereIn('id', $request->input('id'))->get();
        foreach ($register as $register) {
            if ($register->sampel) {
                $register->sampel()->delete();
            }
            $register->delete();
        }
        return response()->json(['status' => 200, 'message' => 'Berhasil menghapus data sampel']);
    }
}
