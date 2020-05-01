<?php

namespace App\Exports;

use App\Models\Pasien;
use App\Models\PemeriksaanSampel;
use App\Models\Register;
use App\Models\Sampel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class SampelVerifiedExport implements FromQuery, 
    WithStrictNullComparison, 
    ShouldAutoSize,
    WithHeadings
{
    use Exportable;

    protected $startDate;

    protected $endDate;

    protected $sampelStatus;

    function __construct(array $payload = [])
    {
        $this->sampelStatus = 'sample_verified';

        if (isset($payload['startDate']) && $payload['startDate']) {
            $this->startDate = $payload['startDate'];
        }

        if (isset($payload['endDate']) && $payload['endDate']) {
            $this->endDate = $payload['endDate'];
        }

        if (isset($payload['sampelStatus']) && $payload['sampelStatus']) {
            $this->sampelStatus = $payload['sampelStatus'];
        }

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $pasienRegisterSubQuery = DB::table('pasien_register')
            ->select(['register_id', 'pasien_id'])
            ->groupBy(['register_id', 'pasien_id']);

        $query = PemeriksaanSampel::query()
            ->join('sampel', 'pemeriksaansampel.sampel_id', 'sampel.id')
            ->join('register', 'sampel.register_id', 'register.id')
            ->leftJoinSub(
                $pasienRegisterSubQuery,
                'tabel_pasien_register',
                function($join){
                    $join->on('register.id', 'tabel_pasien_register.register_id');
            })
            ->leftJoin('pasien', 'tabel_pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'pasien.kota_id', 'kota.id')
            ->orderBy('pemeriksaansampel.tanggal_input_hasil', 'desc')
            ->select([
                DB::raw('ROW_NUMBER() OVER() AS Row'),
                'register.nomor_register',
                DB::raw("CONCAT(pasien.nama_depan,' ',pasien.nama_belakang) AS nama_lengkap"),
                DB::raw("pasien.no_ktp::varchar"),
                'pasien.tanggal_lahir',
                'pasien.tempat_lahir',
                'pasien.jenis_kelamin',
                'pasien.alamat_lengkap',
                'pasien.no_telp',
                'pasien.no_hp',
                'kota.nama',
                'sampel.nomor_sampel',
                'pemeriksaansampel.kesimpulan_pemeriksaan',
                'pemeriksaansampel.tanggal_input_hasil',
                'sampel.waktu_sample_verified'
            ])
            ->where('sampel.sampel_status', $this->sampelStatus);

            if ($this->startDate) {
                $query->where('register.created_at', '>=', $this->startDate);
            }

            if ($this->endDate) {
                $query->where('register.created_at', '<=', $this->endDate);
            }
        
        return $query;

        // $pemeriksaanTerbaru = PemeriksaanSampel::query()->select([
        //         'sampel_id',
        //         DB::raw("MAX(tanggal_input_hasil) last_tanggal_input_hasil")
        //     ])
        //     ->groupBy('sampel_id');

        // $sampelQuery = Sampel::query()
        //     ->joinSub($pemeriksaanTerbaru, 'periksa', function($join){
        //         $join->on('sampel.id', '=', 'periksa.sampel_id');
        //     })
        //     ->join('register', 'sampel.register_id','register.id')
        //     ->select([
        //         'sampel.id AS sampel_id',
        //         'sampel.nomor_sampel',
        //         'sampel.register_id',
        //         'periksa.last_tanggal_input_hasil',
        //         'register.nomor_register'
        //     ]);

    }

    public function headings(): array
    {
        return [
            'No.',
            'Nomor Registrasi',
            'Nama Pasien',
            'NIK',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Jenis Kelamin',
            'Alamat',
            'No. Telp',
            'No. HP',
            // 'Sumber Pasien',
            'Domisili',
            'No. Sampel',
            'Hasil Pemeriksaan',
            'Tanggal Input Hasil',
            'Tanggal Diverifikasi'
        ];
    }


}
