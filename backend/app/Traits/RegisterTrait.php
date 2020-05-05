<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Register trait
 * 
 */
trait RegisterTrait
{
    /**
     * Generate nomor register
     * @param string $date
     * @param string $jenisRegistrasi
     * 
     * @return string
     */
    public function generateNomorRegister($date = null, $jenisRegistrasi = null)
    {
        if(!$date) {
            $date = date('Ymd');
        }

        if (empty($jenisRegistrasi)) {
            $kodeRegistrasi = 'L';
        } 

        if ($jenisRegistrasi === 'mandiri') {
            $kodeRegistrasi = 'L';
        } 

        if ($jenisRegistrasi === 'rujukan') {
            $kodeRegistrasi = 'R';
        }

        $res = DB::select("select max(right(nomor_register, 4))::int8 val from register where nomor_register ilike '{$kodeRegistrasi}{$date}%'");

        if (count($res)) {
            $nextnum = $res[0]->val + 1;
        } 
        
        if (!count($res)) {
            $nextnum = 1;
        }
        
        return $kodeRegistrasi . $date . str_pad($nextnum,4,"0",STR_PAD_LEFT);
    }

}
