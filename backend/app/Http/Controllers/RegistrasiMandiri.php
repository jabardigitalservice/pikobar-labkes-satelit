<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrasiMandiri extends Controller
{
    public function getData(Request $request)
    {
        $dummy['data'] = array(
            (object) array(
                'noreg' => 0271234123,
                'nik' => 123456789,
                'nama_pasien' => 'ABCD EFG (20 Tahun 0 Bulan)',
                'dinkes_pengirim' => 'Kota Cirebon',
                'fasyankes' => 'Rumah Sakit (RSUD ABCD)',
                'dokter_pj' => 'Dr. ABCD',
                'created_at' => '2020-04-21 09:00:29'
            ),
            (object) array(
                'noreg' => 0271234124,
                'nik' => 123456788,
                'nama_pasien' => 'Ahmad Husen (20 Tahun 0 Bulan)',
                'dinkes_pengirim' => 'Kota Cirebon',
                'fasyankes' => 'Rumah Sakit (RSUD ABCD)',
                'dokter_pj' => 'Dr. ABCD',
                'created_at' => '2020-04-21 09:00:29'
            ),
            (object) array(
                'noreg' => 0271234125,
                'nik' => 123456787,
                'nama_pasien' => 'Husni Nuryani (20 Tahun 0 Bulan)',
                'dinkes_pengirim' => 'Kota Bandung',
                'fasyankes' => 'Rumah Sakit (RSUD ABCD)',
                'dokter_pj' => 'Dr. ABCD',
                'created_at' => '2020-04-21 09:00:29'
            ),
        );
        $dummy['count'] = 2;
        return response()->json($dummy);
    }
}
