<?php

namespace App\Traits;

use App\Models\PemeriksaanSampel;

/**
 * Trait pemeriksaan
 */
trait PemeriksaanTrait
{
    private function parseHasilDeteksi($hasilDeteksi)
    {
        if (!$hasilDeteksi || empty($hasilDeteksi) || in_array(null,$hasilDeteksi)) {
            return null;
        }

        return collect($hasilDeteksi)->map(function($hasilCT){
            $values = explode(',', $hasilCT['ct_value']);
            if ($hasilCT['ct_value'] && count($values) > 1) {
                $hasilCT['ct_value'] = (double) implode('.', $values);
            }
            return $hasilCT;
        });
    }

}
