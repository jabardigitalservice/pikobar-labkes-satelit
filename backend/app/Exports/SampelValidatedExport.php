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
    WithCustomValueBinder
    // WithColumnFormatting,
    // WithMapping
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
            ->orderBy('sampel.id')
            // ->orderBy('pemeriksaansampel.tanggal_input_hasil', 'desc')
            ->select([
                DB::raw('ROW_NUMBER() OVER(ORDER BY sampel.id) AS Row'),
                'register.nomor_register',
                // DB::raw("CONCAT(pasien.nama_depan,' ',pasien.nama_belakang) AS nama_lengkap"),
                // DB::raw("pasien.no_ktp::varchar"),
                'pasien.nama_lengkap',
                DB::raw("pasien.nik::varchar"), // DB::raw('CONCAT("\'",pasien.nik) AS nik'),
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

            ])
            ->where('sampel.sampel_status', $this->sampelStatus);

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
            'Domisili',
            'No. Sampel',
            'Hasil Pemeriksaan',
            'Tanggal Input Hasil',
            'Tanggal Diverifikasi',
            'Sumber Pasien'
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

    // public function columnFormats(): array
    // {
    //     return [
    //         'D' => NumberFormat::FORMAT_TEXT,
    //     ];
    // }

    // public function map($register): array
    // {
    //     // Date::dateTimeToExcel($register->tanggal_lahir)
    //     return [
    //         $register->nomor_urut,
    //         $register->nomor_register,
    //         $register->nama_lengkap,
    //         $register->nik ? "'" . $register->nik : "",
    //         $register->tanggal_lahir,
    //         $register->tempat_lahir,
    //         $register->jenis_kelamin,
    //         $register->alamat_lengkap,
    //         $register->no_telp,
    //         $register->no_hp,
    //         $register->nama_kota,
    //         $register->nomor_sampel ? "'" . $register->nomor_sampel : "",
    //         $register->kesimpulan_pemeriksaan,
    //         $register->tanggal_input_hasil,
    //         $register->waktu_sample_verified,
    //         $register->sumber_pasien,
    //     ];
    // }

}
