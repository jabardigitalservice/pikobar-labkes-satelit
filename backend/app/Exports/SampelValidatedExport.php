<?php

namespace App\Exports;

use App\Models\PemeriksaanSampel;
use App\Models\Sampel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SampelValidatedExport extends DefaultValueBinder implements FromQuery,
    WithStrictNullComparison, 
    ShouldAutoSize,
    WithHeadings,
    WithCustomValueBinder,
    WithMapping
{
    use Exportable;

    protected $startDate;

    protected $endDate;

    protected $kotaId;

    protected $fasyankesId;

    protected $sampelStatus;

    protected $kesimpulanPemeriksaan;

    function __construct(array $payload = [])
    {
        $this->sampelStatus = 'sample_valid';

        if (isset($payload['startDate']) && $payload['startDate']) {
            $this->startDate = $payload['startDate'];
        }

        if (isset($payload['endDate']) && $payload['endDate']) {
            $this->endDate = $payload['endDate'];
        }

        if (isset($payload['sampelStatus']) && $payload['sampelStatus']) {
            $this->sampelStatus = $payload['sampelStatus'];
        }

        if (isset($payload['kota_id']) && $payload['kota_id']) {
            $this->kotaId = $payload['kota_id'];
        }

        if (isset($payload['fasyankes_id']) && $payload['fasyankes_id']) {
            $this->kotaId = $payload['fasyankes_id'];
        }

        if (isset($payload['kesimpulan_pemeriksaan']) && $payload['kesimpulan_pemeriksaan']) {
            $this->kesimpulanPemeriksaan = $payload['kesimpulan_pemeriksaan'];
        }

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        
        $query = $this->queryFromPemeriksaan()->where('sampel.sampel_status', $this->sampelStatus);

            if ($this->startDate) {
                $query->where('register.created_at', '>=', $this->startDate);
            }

            if ($this->endDate) {
                $query->where('register.created_at', '<=', $this->endDate);
            }

            if ($this->kotaId) {
                $query->where('kota.id', $this->kotaId);
            }
    
            if ($this->fasyankesId) {
                $query->where('register.fasyankes_id', $this->fasyankesId);
            }
    
            if ($this->kesimpulanPemeriksaan) {
                $query->where('pemeriksaansampel.kesimpulan_pemeriksaan', $this->kesimpulanPemeriksaan);
            }
        
        return $query;

    }

    public function queryFromPemeriksaan()
    {
        $pasienRegisterSubQuery = DB::table('pasien_register')
            ->select(['register_id', 'pasien_id'])
            ->groupBy(['register_id', 'pasien_id']);

        return PemeriksaanSampel::query()
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
            ->leftJoin('fasyankes', 'register.fasyankes_id', 'fasyankes.id')
            ->orderBy('sampel.id')
            // ->orderBy('pemeriksaansampel.tanggal_input_hasil', 'desc')
            ->select([
                DB::raw('ROW_NUMBER() OVER(ORDER BY sampel.id) AS nomor_urut'),
                'register.nomor_register',
                'pasien.nama_lengkap',
                DB::raw("pasien.nik::varchar"),
                'pasien.tanggal_lahir',
                'pasien.tempat_lahir',
                'pasien.jenis_kelamin',
                'pasien.alamat_lengkap',
                'pasien.no_telp',
                'pasien.no_hp',
                DB::raw('kota.nama AS nama_kota'),
                'sampel.nomor_sampel',
                'pemeriksaansampel.kesimpulan_pemeriksaan',
                'pemeriksaansampel.tanggal_input_hasil',
                'sampel.waktu_sample_verified',
                'register.sumber_pasien',
                DB::raw('fasyankes.nama AS nama_fasyankes'),
                DB::raw("DATE_PART('year', age(pasien.tanggal_lahir)) as umur_pasien"),
                'sampel.jenis_sampel_nama',
                DB::raw('register.created_at as tanggal_registrasi'),
                'sampel.lab_pcr_nama',
                'register.tanggal_kunjungan',
                'register.nama_rs',
            ]);
    }

    public function headings(): array
    {
        return [
            'No.',
            // 'Hari, Tanggal', ????
            'Nomor Registrasi',
            'No. Sampel',
            'Sumber Pasien',
            'Fasyankes/Dinkes Pengirim',
            'Nama Pasien',
            'NIK',
            'Umur',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Jenis Kelamin',
            'Asal',
            'No. Telp / HP',
            'Alamat',
            'Tipe Sampel',
            'Hasil Pemeriksaan',
            'Lab PCR',
            'Tanggal Registrasi',
            'Tanggal Periksa/Lapor',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value) && (strlen($value) > 7)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function map($register): array
    {
        // Date::dateTimeToExcel($register->tanggal_lahir)
        return [
            $register->nomor_urut,
            $register->nomor_register,
            $register->nomor_sampel, // ? "'" . $register->nomor_sampel : "",
            $register->sumber_pasien,
            $register->nama_fasyankes ?? $register->nama_rs,
            $register->nama_lengkap,
            $register->nik, // ? "'" . $register->nik : "",
            $register->umur_pasien,
            $register->tanggal_lahir,
            $register->tempat_lahir,
            $register->jenis_kelamin,
            $register->nama_kota,
            ($register->no_telp || $register->no_hp) ? $register->no_telp .' HP. '.$register->no_hp : '',
            $register->alamat_lengkap,
            $register->jenis_sampel_nama,
            $register->kesimpulan_pemeriksaan,
            $register->lab_pcr_nama,
            $register->tanggal_registrasi,
            $register->tanggal_kunjungan,
            // $register->waktu_sample_verified,
        ];
    }

    

}
