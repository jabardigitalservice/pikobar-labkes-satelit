<?php

namespace App\Http\Controllers\V1;

use App\Exports\AjaxTableExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHasilPemeriksaan;
use App\Models\PemeriksaanSampel;
use App\Models\Register;
use App\Models\Sampel;
use App\Models\SampelLog;
use App\Models\StatusSampel;
use App\Traits\PemeriksaanTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VerifikasiController extends Controller
{
    use PemeriksaanTrait;
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $isData = false)
    {
        $models = Sampel::leftJoin('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->leftJoin('register', 'sampel.register_id', 'register.id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'register.id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id')
            ->where('sampel.sampel_status', 'pcr_sample_analyzed');

        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order = $request->get('order', 'waktu_pcr_sample_analyzed');

        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('nomor_sampel', 'ilike', '%' . $search . '%')
                    ->orWhere('catatan_pemeriksaan', 'ilike', '%' . $search . '%')
                    ->orWhere('nama_lengkap', 'ilike', '%' . $search . '%')
                    ->orWhere('nik', 'ilike', '%' . $search . '%')
                    ->orWhere('kota.nama', 'ilike', '%' . $search . '%')
                    ->orWhere('instansi_pengirim_nama', 'ilike', '%' . $search . '%')
                    ->orWhere('status', 'ilike', '%' . $search . '%')
                    ->orWhere('sumber_pasien', 'ilike', '%' . $search . '%')
                    ->orWhere('catatan_pemeriksaan', 'ilike', '%' . $search . '%');
            });
        }

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) {
                    continue;
                }

                switch ($key) {
                    case 'kesimpulan_pemeriksaan':
                        $models->where('kesimpulan_pemeriksaan', 'ilike', '%' . $val . '%');
                        break;
                    case 'kota':
                        $models->where('pasien.kota_id', $val);
                        break;
                    case 'nama_pasien':
                        $models->where('pasien.nama_lengkap', 'ilike', '%' . $val . '%');
                        break;
                    case 'instansi_pengirim':
                        $models->where('register.instansi_pengirim_nama', 'ilike', '%' . $val . '%');
                        break;
                    case 'start_date':
                        $models->whereDate('waktu_pcr_sample_analyzed', '>=', date('Y-m-d', strtotime($val)));
                        break;
                    case 'end_date':
                        $models->whereDate('waktu_pcr_sample_analyzed', '<=', date('Y-m-d', strtotime($val)));
                        break;
                    case 'sumber_pasien':
                        $models->where('register.sumber_pasien', 'ilike', '%' . $val . '%');
                        break;
                    case 'status':
                        $models->where('status', 'ilike', '%' . $val . '%');
                        break;
                    default:
                        break;
                }
            }
        }

        if (Auth::user()->lab_satelit_id != null) {
            $models->where('sampel.lab_satelit_id', Auth::user()->lab_satelit_id);
        }

        $count = $models->count();

        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 500);

        if ($order) {
            $order_direction = $request->get('order_direction', 'asc');
            if (empty($order_direction)) {
                $order_direction = 'desc';
            }

            switch ($order) {
                case 'waktu_pcr_sample_analyzed':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                case 'nomor_sampel':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                case 'nama_pasien':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                case 'kota_domilisi':
                    $models = $models->orderBy('kota_id', $order_direction);
                    break;
                case 'instansi_pengirim':
                    $models = $models->orderBy('instansi_pengirim_nama', $order_direction);
                    break;
                case 'status':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                case 'sumber_pasien':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                case 'kesimpulan_pemeriksaan':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                case 'catatat':
                    $models = $models->orderBy('catatan_pemeriksaan', $order_direction);
                    break;
                default:
                    break;
            }
        }

        $models = $models->select('*', 'sampel.id as sampel_id', 'kota.nama as nama_kota', 'register.created_at as created_at', 'register.sumber_pasien as sumber_pasien');

        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        return !$isData ? response()->json([
            'data' => $models,
            'count' => $count,
        ]) : $models;
    }

    public function export(Request $request)
    {
        $models = $this->index($request, true);
        foreach ($models as $idx => &$model) {
            $model->no = $idx + 1;
        }
        $header = [
            'No',
            'Tanggal Masuk Sampel',
            'Kode Sampel',
            'Kewarganegaraan',
            'Kategori',
            'Nama',
            'NIK',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Usia',
            'Alamat',
            'Desa/Kelurahan',
            'Kecamatan',
            'Kota/Kab',
            'Status',
            'Instansi Pengirim',
            'Nama Instansi',
            'Jenis Sampel',
            'Swab Ke',
            'Tanggal Swab',
            'Interpretasi',
            'Tanggal Pemeriksaan',
            'Keterangan',
        ];
        $mapping = function ($model) {
            return [
                $model->no,
                parseDate($model->created_at),
                $model->nomor_sampel,
                $model->kewarganegaraan,
                $model->sumber_pasien,
                $model->nama_lengkap,
                $model->nik ? "'" . $model->nik : null,
                $model->jenis_kelamin,
                parseDate($model->tanggal_lahir),
                $model->usia_tahun,
                $model->alamat_lengkap,
                $model->kelurahan,
                $model->kecamatan,
                $model->kota_id,
                $model->status,
                $model->instansi_pengirim,
                $model->instansi_pengirim_nama,
                $model->jenis_sampel_nama,
                $model->swab_ke,
                parseDate($model->tanggal_swab),
                $model->kesimpulan_pemeriksaan,
                parseDate($model->waktu_pcr_sample_analyzed),
                $this->getKeterangan($model),
            ];
        };
        $column_format = [
        ];
        return Excel::download(new AjaxTableExport($models, $header, $mapping, $column_format, [], $models->count()), 'hasil_pemeriksaan.xlsx');

    }

    private function __getKeterangan($model)
    {
        if ($model->kesimpulan_pemeriksaan == 'positif' && $model->status == 'positif') {
            return 'lama';
        } elseif ($model->kesimpulan_pemeriksaan == 'positif' && $model->status != null && $model->status != 'positif') {
            return 'baru';
        }
        return '';
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

        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order = $request->get('order', 'created_at');

        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('nomor_register', 'ilike', '%' . $search . '%')
                    ->orWhere('nomor_sampel', 'ilike', '%' . $search . '%')
                    ->orWhereHas('pemeriksaanSampel', function ($query) use ($search) {
                        $query->where('kesimpulan_pemeriksaan', 'ilike', '%' . $search . '%');
                    })
                    ->orWhereHas('register', function ($query) use ($search) {
                        $query->whereHas('pasiens', function ($query) use ($search) {
                            $query->where('nama_lengkap', 'ilike', '%' . $search . '%')
                                ->orWhere('nik', 'ilike', '%' . $search . '%');
                        });
                    });
            });
        }

        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) {
                    continue;
                }

                switch ($key) {
                    case 'kesimpulan_pemeriksaan':
                        $models->whereHas('pemeriksaanSampel', function ($query) use ($val) {
                            $query->where('kesimpulan_pemeriksaan', $val);
                        });
                        break;
                    case 'kota_domisili':
                        $models->whereHas('register', function ($query) use ($val) {
                            $query->join('pasien_register', 'register.id', 'pasien_register.register_id')
                                ->join('pasien', 'pasien_register.pasien_id', 'pasien.id')
                                ->where('pasien.kota_id', $val);
                        });
                        break;
                    case 'fasyankes':
                        $models->whereHas('register', function ($query) use ($val) {
                            $query->where('fasyankes_id', $val);
                        });
                        break;
                    case 'kategori':
                        $models->whereHas('register', function ($query) use ($val) {
                            $query->where('sumber_pasien', 'ilike', '%' . $val . '%');
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

        $page = $request->get('page', 1);
        $perpage = $request->get('perpage', 500);

        if ($order) {
            $order_direction = $request->get('order_direction', 'asc');
            if (empty($order_direction)) {
                $order_direction = 'asc';
            }

            switch ($order) {
                case 'created_at':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                case 'nomor_register':
                    $models = $models->orderBy($order, $order_direction);
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
                    $models = $models->orderBy($order, $order_direction);
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

        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        // format data
        foreach ($models as &$model) {
            $model->register = $model->register ?? null;
            $model->pasien = $model->register ? optional($model->register)->pasiens()->with(['kota'])->first() : null;
            $model->pemeriksaanSampel = $model->pemeriksaanSampel()->orderBy('tanggal_input_hasil', 'desc')->first() ?? null;
        }

        return response()->json([
            'data' => $models,
            'count' => $count,
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
    public function show($id)
    {
        $sampel = Sampel::leftJoin('pemeriksaansampel', 'sampel.id', 'pemeriksaansampel.sampel_id')
            ->leftJoin('register', 'sampel.register_id', 'register.id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'register.id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id');
        $sampel->where('sampel.id', $id);
        if (Auth::user()->lab_satelit_id != null) {
            $sampel->where('sampel.lab_satelit_id', Auth::user()->lab_satelit_id);
        }
        $sampel->select('*', 'sampel.id as id', 'kota.nama as nama_kota', 'register.created_at as created_at', 'pemeriksaansampel.id as pemeriksaan_id', 'register.sumber_pasien as sumber_pasien');
        $result = $sampel->first();
        $log = SampelLog::where('sampel_id', $result->id)->orderBy('created_at', 'desc')->get();
        $result->logs = $log;
        $result->sampel = Auth::user()->lab_satelit_id;
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $result,
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
            'status' => 200,
            'message' => 'success',
            'data' => StatusSampel::where('sampel_status', '!=', 'sample_verified')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sampel  $sampel
     * @return \Illuminate\Http\Response
     */
    public function updateToVerified(StoreHasilPemeriksaan $request, Sampel $sampel)
    {
        $request->validate([
            'kesimpulan_pemeriksaan' => 'required',
            'catatan_pemeriksaan' => 'nullable|max:255',
            'last_pemeriksaan_id' => 'required|exists:pemeriksaansampel,id',
        ], $request->only(['kesimpulan_pemeriksaan', 'catatan_pemeriksaan', 'last_pemeriksaan_id']));

        DB::beginTransaction();
        try {

            PemeriksaanSampel::find($request->input('last_pemeriksaan_id'))->update([
                'kesimpulan_pemeriksaan' => $request->input('kesimpulan_pemeriksaan'),
                'catatan_pemeriksaan' => $request->input('catatan_pemeriksaan'),
                'hasil_deteksi' => $this->parseHasilDeteksi($request->hasil_deteksi),
            ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => Sampel::find($sampel->id),
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
            'sampel_status' => 'sample_verified',
            'waktu_sample_verified' => now(),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => Sampel::find($sampel->id),
        ]);
    }

    public function listKategori()
    {
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => Register::select('sumber_pasien')
                ->whereNotNull('sumber_pasien')
                ->groupBy('sumber_pasien')->get(),
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
