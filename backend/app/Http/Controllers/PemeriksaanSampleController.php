<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PemeriksaanSampleController extends Controller
{
    public function getData(Request $request)
    {
        $dummy['data'] = array(
            (object) array(
                'sam_barcodenomor_sampel' => 111,
                'sam_jenis_sampel' => 'Usap Nasofaring & Orofaring',
                'eks_tanggal_pengiriman_rna' => '2020-04-24',
                'eks_jam_pengiriman_rna' => '14:00',
            ),
            (object) array(
                'sam_barcodenomor_sampel' => 112,
                'sam_jenis_sampel' => 'Bronchoalveolar Lavage',
                'eks_tanggal_pengiriman_rna' => '2020-04-24',
                'eks_jam_pengiriman_rna' => '14:00',
            ),
            (object) array(
                'sam_barcodenomor_sampel' => 113,
                'sam_jenis_sampel' => 'Tracheal Aspirate',
                'eks_tanggal_pengiriman_rna' => '2020-04-24',
                'eks_jam_pengiriman_rna' => '14:00',
            ),
        );
        $dummy['count'] = 3;
        return response()->json($dummy);
    }

    public function getDikirim(Request $request)
    {
        $dummy['data'] = array(
            (object) array(
                'sam_barcodenomor_sampel' => 111,
                'sam_jenis_sampel' => 'Usap Nasofaring & Orofaring',
                'eks_tanggal_pengiriman_rna' => '2020-04-24',
                'eks_jam_pengiriman_rna' => '14:00',
            )
        );
        $dummy['count'] = 1;
        return response()->json($dummy);
    }
}
