<?php

namespace App\Traits;

use App\Enums\JenisSampelEnum;
use App\Models\JenisSampel;
use Illuminate\Http\Request;

/**
 * Register trait
 *
 */
trait RegisterPerujukTrait
{
    private function getJenisSampel(Request $request)
    {
        $jenis_sampel = $request->input('nama_jenis_sampel');
        if ($request->input('jenis_sampel') != JenisSampelEnum::LAINNYA()->getIndex()) {
            $jenis_sampel = JenisSampel::find($request->input('jenis_sampel'))->nama;
        }
        return $jenis_sampel;
    }
}
