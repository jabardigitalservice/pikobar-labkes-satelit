<?php

namespace App\Exports;

use App\Models\Sampel;
use App\Models\Register;
use App\Models\PasienRegister;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class RegisMandiriExport implements FromQuery, 
    WithStrictNullComparison, 
    ShouldAutoSize,
    WithHeadings
{
    use Exportable;

    protected $startDate;

    protected $endDate;

    function __construct(array $payload = [])
    {
    
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

   public function query()
   {
        // $pasienRegisterSubQuery = DB::table('pasien_register')
        //     ->select(['register_id', 'pasien_id'])
        //     ->groupBy(['register_id', 'pasien_id']);
        $query = PasienRegister::leftJoin('register','register.id','pasien_register.register_id')
                    ->leftJoin('pasien','pasien.id','pasien_register.pasien_id')
                    // ->leftJoin('sampel','sampel.register_id','register.id')
                    ->leftJoin('kota','kota.id','pasien.kota_id')
                    ->orderBy('register.created_at','desc')
                    ->join(DB::raw("(select register_id,string_agg(nomor_sampel,';') nomor_sampel from sampel group by register_id) sampel"),function($join){
                        $join->on('sampel.register_id','pasien_register.register_id');
                    })
                    ->select([
                        DB::raw('ROW_NUMBER() OVER() AS Row'),
                        'register.nomor_register',
                        'pasien.nama_lengkap',
                        DB::raw("CONCAT ('''', pasien.nik)"),
                        'pasien.tempat_lahir',
                        'pasien.tanggal_lahir',
                        'pasien.jenis_kelamin',
                        'pasien.no_hp',
                        'register.sumber_pasien',
                        'kota.nama',
                        'sampel.nomor_sampel'
                    ]);

        if ($this->startDate) {
            $query->where('register.created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('register.created_at', '<=', $this->endDate);
        }
        $query->where('jenis_registrasi','mandiri');
        return $query;
   }

   public function headings(): array
    {
        return [
            'No.',
            'Nomor Registrasi',
            'Nama Pasien',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'No. HP',
            'Sumber Pasien',
            'Domisili',
            'No. Sampel'
        ];
    }
}
