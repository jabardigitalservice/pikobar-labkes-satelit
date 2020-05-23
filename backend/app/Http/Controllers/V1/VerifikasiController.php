<?php

namespace App\Http\Controllers\V1;

use App\Exports\AjaxTableExport;
use App\Http\Controllers\Controller;
use App\Models\PemeriksaanSampel;
use App\Models\PengambilanSampel;
use App\Models\Register;
use App\Models\Sampel;
use App\Models\StatusSampel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VerifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $isData = false)
    {
        $models = Sampel::query()->whereHas('logs')
            ->whereHas('pemeriksaanSampel')
            ->whereIn('sampel_status', ['pcr_sample_analyzed']);
            // ->where('sampel_status', '!=', 'sample_verified')
            // ->where('sampel_status', '!=', 'sample_valid')
            // ->where('sampel_status', '!=', 'sample_invalid'); // 'pcr_sample_analyzed'

        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'created_at');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('nomor_register','ilike','%'.$search.'%')
                   ->orWhere('nomor_sampel','ilike','%'.$search.'%')
                   ->orWhereHas('pemeriksaanSampel', function($query) use ($search){
                       $query->where('kesimpulan_pemeriksaan', 'ilike','%'.$search.'%');
                   })
                   ->orWhereHas('register', function($query) use ($search){
                        $query->whereHas('pasiens', function($query) use ($search) {
                            $query->where('nama_lengkap', 'ilike','%'.$search.'%')
                                ->orWhere('nik', 'ilike','%'.$search.'%');
                        });
                    });
            }); 
        }

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) continue;
                switch ($key) {
                    case 'kesimpulan_pemeriksaan':
                        $models->whereHas('pemeriksaanSampel', function($query) use ($val){
                            $query->where('kesimpulan_pemeriksaan', $val);
                        });
                        break;
                    case 'kota':
                        $models->whereHas('register', function($query) use ($val){
                            $query->join('pasien_register', 'register.id', 'pasien_register.register_id')
                                ->join('pasien', 'pasien_register.pasien_id', 'pasien.id')
                                ->where('pasien.kota_id', $val);
                        });
                        break;
                    case 'nama_pasien': 
                        $models->whereHas('register', function($query) use ($val){
                            $query->join('pasien_register', 'register.id', 'pasien_register.register_id')
                                ->join('pasien', 'pasien_register.pasien_id', 'pasien.id')
                                ->where('pasien.nama_lengkap','ilike' ,'%'.$val.'%');
                        });
                        break;
                    case 'instansi_pengirim': 
                        $models->whereHas('register', function ($query) use ($val){
                            $query->where('register.instansi_pengirim', 'ilike', '%'. $val .'%');
                        });
                        break;
                    case 'start_date':
                        $models->whereDate('waktu_pcr_sample_analyzed', '>=', $val);
                        break;
                    case 'end_date':
                        $models->whereDate('waktu_pcr_sample_analyzed', '<=', $val);
                        break;
                    default:
                        break;
                }
            }

            // 
                        // kategori --> sumber_pasien
                        // tanggal ver-val
        }

        if (Auth::user()->lab_satelit_id !=null) {
            $models->where('sampel.lab_satelit_id',Auth::user()->lab_satelit_id);
        }

        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'desc';

            switch ($order) {
                case 'created_at':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'nomor_register':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'pasien_nama':
                    $models = $models->leftJoin('register', 'sampel.register_id', '=', 'register.id')
                        ->leftJoin('pasien_register', 'register.id', '=', 'pasien_register.register_id')
                        ->leftJoin('pasien', 'pasien_register.pasien_id', '=', 'pasien.id')
                        ->select('sampel.*')
                        ->addSelect('pasien.nama_depan')
                        ->distinct()
                        ->orderBy('nama_depan', $order_direction);
                    break;
                default:
                    break;
            }
        }

        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        // format data
        foreach ($models as &$model) {
            $model->register = $model->register ?? null;
            $model->pasien = $model->register ? optional($model->register)->pasiens()->with(['kota'])->first() : null;
            $model->pemeriksaanSampel = $model->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first() ?? null;
        }

        return !$isData ? response()->json([
            'data'=> $models,
            'count'=> $count
        ]) : $models;
    }

    public function export(Request $request)
    {
        $models = $this->index($request, true);
        foreach ($models as $idx => &$model) {
            $model->no = $idx+1;
        }
        $header = [
            'No',
            'Tanggal Pemeriksaan',
            'Nomor Sampel',
            'Nama Pasien',
            'NIK',
            'Usia',
            'Kota Domisili',
            'Instansi Pengiriman',
            'Parameter Lab',
            'Kesimpulan Pemeriksaan',
            'Keterangan',
        ];
        $mapping = function($model) {
            return [
                $model->no,
                $model->waktu_pcr_sample_analyzed,
                $model->nomor_sampel,
                $model->pasien->nama_lengkap,
                $model->pasien->nik,
                $model->pasien->usia_tahun,
                isset($model->pasien->kota->nama) ? $model->pasien->kota->nama : null,
                $model->register->instansi_pengirim,
                $model->pemeriksaanSampel->hasil_deteksi_parsed != null ? 
                $model->pemeriksaanSampel->hasil_deteksi_parsed->transform(function($r){
                return $r['target_gen'] . ": " . $r['ct_value'];})->implode("\n") : null,
                $model->pemeriksaanSampel->kesimpulan_pemeriksaan,
                $model->pemeriksaanSampel->catatan_pemeriksaan,
                // $model->jabatan_jenis == 2 ? $model->jabatan_nama : (
                //     $$model->jabatan_jenis == 3 ? ($$model->jft ? $$model->jft->jf_nama : 'ERR JFT') : (
                //         $$model->jabatan_jenis == 4 ? ($$model->jfu ? $$model->jfu->jfu_nama : 'ERR JFU') : 'ERR JAB'
                //     )
                // ),
            ];
        };
        $column_format = [
            'E' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER,
        ];
        return Excel::download(new AjaxTableExport($models, $header, $mapping, $column_format,[],$models->count()), 'hasil_pemeriksaan.xlsx');
    
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function indexVerified(Request $request)
    {
        $models = Sampel::query()->whereHas('logs')
            ->whereHas('pemeriksaanSampel')
            ->where('sampel_status', '!=', 'sample_invalid')
            ->whereIn('sampel_status', ['sample_verified', 'sample_valid']); // 'pcr_sample_analyzed'

        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'created_at');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('nomor_register','ilike','%'.$search.'%')
                   ->orWhere('nomor_sampel','ilike','%'.$search.'%')
                   ->orWhereHas('pemeriksaanSampel', function($query) use ($search){
                       $query->where('kesimpulan_pemeriksaan', 'ilike','%'.$search.'%');
                   })
                   ->orWhereHas('register', function($query) use ($search){
                        $query->whereHas('pasiens', function($query) use ($search) {
                            $query->where('nama_lengkap', 'ilike','%'.$search.'%')
                            ->orWhere('nik', 'ilike','%'.$search.'%');
                        });
                    });
            });  
        }

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) continue;
                switch ($key) {
                    case 'kesimpulan_pemeriksaan':
                        $models->whereHas('pemeriksaanSampel', function($query) use ($val){
                            $query->where('kesimpulan_pemeriksaan', $val);
                        });
                        break;
                    case 'kota_domisili':
                        $models->whereHas('register', function($query) use ($val){
                            $query->join('pasien_register', 'register.id', 'pasien_register.register_id')
                                ->join('pasien', 'pasien_register.pasien_id', 'pasien.id')
                                ->where('pasien.kota_id', $val);
                        });
                        break;
                    case 'fasyankes': 
                        $models->whereHas('register', function ($query) use ($val){
                            $query->where('fasyankes_id', $val);
                        });
                        break;
                    case 'kategori': 
                        $models->whereHas('register', function ($query) use ($val){
                            $query->where('sumber_pasien', 'ilike', '%'. $val .'%');
                        });
                        break;
                    case 'tanggal_verifikasi_start':
                        $models->where('waktu_sample_verified', '>=', $val);
                        break;
                    case 'tanggal_verifikasi_end':
                        $models->where('waktu_sample_verified', '<=', $val);
                        break;
                    default:
                        break;
                        
                }
            }
        }

        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

        if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'asc';

            switch ($order) {
                case 'created_at':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'nomor_register':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'pasien_nama':
                    $models = $models->leftJoin('register', 'sampel.register_id', '=', 'register.id')
                        ->leftJoin('pasien_register', 'register.id', '=', 'pasien_register.register_id')
                        ->leftJoin('pasien', 'pasien_register.pasien_id', '=', 'pasien.id')
                        ->select('sampel.*')
                        ->addSelect('pasien.nama_depan')
                        ->distinct()
                        ->orderBy('nama_depan', $order_direction);
                    break;
                case 'nomor_sampel':
                    $models = $models->orderBy($order,$order_direction);
                    break;
                case 'kesimpulan_pemeriksaan':
                    // $models = $models->with(['pemeriksaanSampel'=> function($query){
                    //     $query->orderBy('kesimpulan_pemeriksaan', 'asc');
                    // }]);
                    break;
                default:
                    break;
            }
        }

        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();

        // format data
        foreach ($models as &$model) {
            $model->register = $model->register ?? null;
            $model->pasien = $model->register ? optional($model->register)->pasiens()->with(['kota'])->first() : null;
            $model->pemeriksaanSampel = $model->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first() ?? null;
        }

        return response()->json([
            'data'=> $models,
            'count'=> $count
        ]);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function show(Sampel $sampel)
    {
        $result = $sampel->load(['pemeriksaanSampel', 'status', 'register', 'ekstraksi', 'logs'])->toArray();
        $pasien = $sampel->register ? optional($sampel->register->pasiens()->with(['kota']))->first() : null;
        $fasyankes = $sampel->register ? $sampel->register->fasyankes : null;
        $pengambilanSampel = PengambilanSampel::find($sampel->getAttribute('pengambilan_sampel_id'));

        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=> $result + [
                'pasien'=> optional($pasien)->toArray(),
                'last_pemeriksaan_sampel'=> $sampel->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first(),
                'fasyankes'=> $fasyankes,
                'pengambilanSampel'=> $pengambilanSampel
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sampelStatusList()
    {
        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=> StatusSampel::where('sampel_status', '!=', 'sample_verified')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function updateToVerified(Request $request, Sampel $sampel)
    {
        $request->validate([
            'kesimpulan_pemeriksaan'=> 'required|in:positif,negatif,inkonklusif,sampel kurang',
            'catatan_pemeriksaan'=> 'nullable|max:255',
            'last_pemeriksaan_id'=> 'required|exists:pemeriksaansampel,id'
        ], $request->only(['kesimpulan_pemeriksaan', 'catatan_pemeriksaan', 'last_pemeriksaan_id']));

        DB::beginTransaction();
        try {

            PemeriksaanSampel::find($request->input('last_pemeriksaan_id'))->update([
                'kesimpulan_pemeriksaan'=> $request->input('kesimpulan_pemeriksaan'),
                'catatan_pemeriksaan'=> $request->input('catatan_pemeriksaan')
            ]);

            // $sampel->update([
            //     'sampel_status'=> 'sample_verified',
            //     'waktu_sample_verified'=> now()
            // ]);

            DB::commit();

    
            return response()->json([
                'status'=>200,
                'message'=>'success',
                'data'=> Sampel::find($sampel->id)
            ]);
            
            

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function verifiedSingleSampel(Request $request, Sampel $sampel)
    {
        $sampel->update([
            'sampel_status'=> 'sample_verified',
            'waktu_sample_verified'=> now()
        ]);

        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=> Sampel::find($sampel->id)
        ]);
    }

    public function listKategori()
    {
        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=> Register::select('sumber_pasien')
                ->whereNotNull('sumber_pasien')
                ->groupBy('sumber_pasien')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
