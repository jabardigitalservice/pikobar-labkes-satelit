<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInputHasil;
use App\Imports\HasilPemeriksaanImport;
use App\Models\LabPCR;
use App\Models\PemeriksaanSampel;
use App\Models\RegisterPerujuk;
use App\Models\Sampel;
use App\Traits\PemeriksaanTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use Validator;

class PCRController extends Controller
{
    use PemeriksaanTrait;

    public function getData(Request $request)
    {
        $user = Auth::user();
        $models = Sampel::leftJoin('register', 'sampel.register_id', 'register.id')
            ->leftJoin('pasien_register', 'pasien_register.register_id', 'register.id')
            ->leftJoin('pasien', 'pasien_register.pasien_id', 'pasien.id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id')
            ->where('sampel.sampel_status', 'sample_taken')
            ->whereNull('register.deleted_at')
            ->where('register.lab_satelit_id', $user->lab_satelit_id)
            ->where('sampel.lab_satelit_id', $user->lab_satelit_id)
            ->where('pasien.lab_satelit_id', $user->lab_satelit_id);
        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order = $request->get('order', 'name');
        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('nomor_sampel', 'ilike', '%' . $search . '%')
                    ->orWhere('register.nama_rs', 'ilike', '%' . $search . '%')
                    ->orWhere('nama_lengkap', 'ilike', '%' . $search . '%')
                    ->orWhere('nik', 'ilike', '%' . $search . '%');
            });
        }
        if ($params) {
            foreach (json_decode($params) as $key => $val) {
                if ($val == '') {
                    continue;
                }

                switch ($key) {
                    case "start_date":
                        $models = $models->whereDate('sampel.waktu_sample_taken', '>=', date('Y-m-d', strtotime($val)));
                        break;
                    case "end_date":
                        $models = $models->whereDate('sampel.waktu_sample_taken', '<=', date('Y-m-d', strtotime($val)));
                        break;
                    case "fasyankes_id":
                        $models = $models->where('register.fasyankes_id', $val);
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
                case 'nomor_sampel':
                    $models = $models->orderBy('nomor_sampel', $order_direction);
                    break;
                case 'nama_lengkap':
                    $models = $models->orderBy('pasien.nama_lengkap', $order_direction);
                    break;
                case 'nik':
                    $models = $models->orderBy('pasien.nik', $order_direction);
                    break;
                case 'instansi_pengirim':
                    $models = $models->orderBy('register.instansi_pengirim_nama', $order_direction);
                    break;
                case 'waktu_sample_taken':
                    $models = $models->orderBy($order, $order_direction);
                    break;
                default:
                    break;
            }
        }
        $models = $models->select('*', 'sampel.id as sampel_id');
        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        $result = [
            'data' => $models,
            'count' => $count,
        ];

        return response()->json($result);
    }

    public function uploadGrafik(Request $request)
    {
        $file = $request->file('file');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('s3')->putFileAs(
            'grafik/' . date('Y-m-d'),
            $request->file('file'),
            $filename
        );
        $url = route('grafik.url', ['path' => date('Y-m-d') . '/' . $filename]);
        return response()->json(['status' => 200, 'message' => 'success', 'url' => $url]);
    }

    public function getGrafik($path)
    {
        $exists = Storage::disk('public')->exists('grafik/' . $path);
        if ($exists) {
            return response()->file(storage_path('app/public/grafik/' . $path));
        }
        $exists = Storage::disk('s3')->exists('grafik/' . $path);
        if ($exists) {
            $contents = Storage::disk('s3')->get('grafik/' . $path);
            $ret = Storage::disk('public')->put(
                'grafik/' . $path,
                $contents
            );
            return response()->file(storage_path('app/public/grafik/' . $path));
        } else {
            abort(404);
        }
    }

    public function detail(Request $request, $id)
    {
        $model = Sampel::with(['pcr', 'status', 'ekstraksi', 'register'])
            ->find($id);
        $model->log_pcr = $model->logs()
            ->whereIn('sampel_status', ['pcr_sample_received', 'pcr_sample_analyzed', 'extraction_sample_reextract'])
            ->orderByDesc('created_at')
            ->get();
        $model->sampel = Auth::user()->lab_satelit_id;
        $model->pasien = $model->register ? optional($model->register)->pasiens()->with(['kota'])->first() : null;
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $model]);
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['pcr'])->find($id);
        if ($sampel->sampel_status == 'pcr_sample_received') {
            $v = Validator::make($request->all(), [
                'tanggal_penerimaan_sampel' => 'required',
                'jam_penerimaan_sampel' => 'required',
                'petugas_penerima_sampel' => 'required',
                'catatan_penerimaan' => 'required',
                'operator_ekstraksi' => 'required',
                'tanggal_mulai_ekstraksi' => 'required',
                'jam_mulai_ekstraksi' => 'required',
                'metode_ekstraksi' => 'required',
                'nama_kit_ekstraksi' => 'required',
            ]);
        } else {
            $v = Validator::make($request->all(), [
                'tanggal_penerimaan_sampel' => 'required',
                'jam_penerimaan_sampel' => 'required',
                'petugas_penerima_sampel' => 'required',
                'operator_ekstraksi' => 'required',
                'tanggal_mulai_ekstraksi' => 'required',
                'jam_mulai_ekstraksi' => 'required',
                'metode_ekstraksi' => 'required',
                'nama_kit_ekstraksi' => 'required',
                'tanggal_pengiriman_rna' => 'required',
                'jam_pengiriman_rna' => 'required',
                'nama_pengirim_rna' => 'required',
                'lab_pcr_id' => 'required',
                'catatan_pengiriman' => 'required',
                'lab_pcr_nama' => 'required_if:lab_pcr_id,999999',
            ]);
        }
        $lab_pcr = LabPCR::find($request->lab_pcr_id);

        if (!$lab_pcr) {
            $v->after(function ($validator) {
                $validator->errors()->add("samples", 'Lab PCR Tidak ditemukan');
            });
        }

        $v->validate();

        $pcr = $sampel->pcr;
        if (!$pcr) {
            $pcr = new PemeriksaanSampel;
            $pcr->sampel_id = $sampel->id;
            $pcr->user_id = $user->id;
        }
        $pcr->tanggal_penerimaan_sampel = parseDate($request->tanggal_penerimaan_sampel);
        $pcr->jam_penerimaan_sampel = parseTime($request->jam_penerimaan_sampel);
        $pcr->petugas_penerima_sampel = $request->petugas_penerima_sampel;
        $pcr->operator_ekstraksi = $request->operator_ekstraksi;
        $pcr->tanggal_mulai_ekstraksi = parseDate($request->tanggal_mulai_ekstraksi);
        $pcr->jam_mulai_ekstraksi = parseTime($request->jam_mulai_ekstraksi);
        $pcr->metode_ekstraksi = $request->metode_ekstraksi;
        $pcr->nama_kit_ekstraksi = $request->nama_kit_ekstraksi;
        $pcr->tanggal_pengiriman_rna = parseDate($request->tanggal_pengiriman_rna);
        $pcr->jam_pengiriman_rna = parseTime($request->jam_pengiriman_rna);
        $pcr->nama_pengirim_rna = $request->nama_pengirim_rna;
        $pcr->catatan_penerimaan = $request->catatan_penerimaan;
        $pcr->catatan_pengiriman = $request->catatan_pengiriman;
        $pcr->save();

        $sampel->lab_pcr_id = $request->lab_pcr_id;
        $sampel->lab_pcr_nama = $lab_pcr->id == 999999 ? $request->lab_pcr_nama : $lab_pcr->nama;
        $sampel->waktu_pcr_sample_received = $pcr->tanggal_mulai_ekstraksi ? date('Y-m-d H:i:s', strtotime($pcr->tanggal_mulai_ekstraksi . ' ' . $pcr->jam_mulai_ekstraksi)) : null;
        $sampel->waktu_extraction_sample_sent = $pcr->tanggal_pengiriman_rna ? date('Y-m-d H:i:s', strtotime($pcr->tanggal_pengiriman_rna . ' ' . $pcr->jam_pengiriman_rna)) : null;
        $sampel->save();

        return response()->json(['status' => 201, 'message' => 'Perubahan berhasil disimpan']);
    }

    public function invalid(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['pcr'])->find($id);
        $v = Validator::make($request->all(), [
            'catatan_pemeriksaan' => 'required',
        ]);

        $v->validate();

        $pcr = $sampel->pcr;
        if (!$pcr) {
            $pcr = new PemeriksaanSampel;
            $pcr->sampel_id = $sampel->id;
            $pcr->user_id = $user->id;
        }
        $pcr->catatan_pemeriksaan = $request->catatan_pemeriksaan;
        $pcr->save();

        $sampel->updateState('extraction_sample_reextract', [
            'user_id' => $user->id,
            'metadata' => $pcr,
            'description' => 'Invalid PCR, need re-extraction',
        ]);

        return response()->json(['status' => 201, 'message' => 'Perubahan berhasil disimpan']);
    }

    public function input(StoreInputHasil $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['pcr'])->find($id);
        $pcr = $sampel->pcr;
        if (!$pcr) {
            $pcr = new PemeriksaanSampel;
            $pcr->sampel_id = $sampel->id;
            $pcr->user_id = $user->id;
        }
        $pcr->tanggal_input_hasil = date('Y-m-d', strtotime($request->tanggal_input_hasil));
        $pcr->jam_input_hasil = date('H:s');
        $pcr->catatan_pemeriksaan = $request->catatan_pemeriksaan != '' ? $request->catatan_pemeriksaan : null;
        $pcr->grafik = $request->grafik;
        $pcr->hasil_deteksi = $this->parseHasilDeteksi($request->hasil_deteksi);
        $pcr->kesimpulan_pemeriksaan = $request->kesimpulan_pemeriksaan;
        $pcr->nama_kit_pemeriksaan = $request->nama_kit_pemeriksaan;
        $pcr->save();

        if ($sampel->sampel_status == 'sample_taken') {
            $sampel->updateState('pcr_sample_analyzed', [
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
            ], $pcr->tanggal_input_hasil);
        } else {
            $sampel->addLog([
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
            ]);
            $sampel->waktu_pcr_sample_analyzed = date('Y-m-d H:i:s');
            $sampel->save();
        }

        if ($sampel->register_perujuk_id) {
            RegisterPerujuk::find($sampel->register_perujuk_id)->updateState('pemeriksaan_selesai');
        }
        return response()->json(['status' => 201, 'message' => 'Hasil analisa berhasil disimpan']);
    }

    public function inputInvalid(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['pcr'])->find($id);

        $pcr = $sampel->pcr;
        if (!$pcr) {
            $pcr = new PemeriksaanSampel;
            $pcr->sampel_id = $sampel->id;
            $pcr->user_id = $user->id;
        }
        $pcr->kesimpulan_pemeriksaan = 'invalid';
        $pcr->save();

        if ($sampel->sampel_status == 'sample_taken') {
            $sampel->updateState('pcr_sample_analyzed', [
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
            ]);
        } else {
            $sampel->addLog([
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
            ]);
            $sampel->waktu_pcr_sample_analyzed = date('Y-m-d H:i:s');
            $sampel->save();
        }
        if ($sampel->perujuk_id) {
            RegisterPerujuk::find($sampel->perujuk_id)->updateState('pemeriksaan_selesai');
        }
        return response()->json(['status' => 201, 'message' => 'Hasil analisa berhasil disimpan']);
    }

    public function terima(Request $request)
    {
        $user = $request->user();
        $v = Validator::make($request->all(), [
            'tanggal_penerimaan_sampel' => 'required',
            'jam_penerimaan_sampel' => 'required',
            'petugas_penerima_sampel_rna' => 'required',
            'operator_real_time_pcr' => 'required',
            'tanggal_mulai_pemeriksaan' => 'required',
            'jam_mulai_pcr' => 'required',
            'jam_selesai_pcr' => 'required',
            'nama_kit_pemeriksaan' => 'required',
        ]);
        $samples = Sampel::whereIn('nomor_sampel', Arr::pluck($request->samples, 'nomor_sampel'))->get()->keyBy('nomor_sampel');

        foreach ($request->samples as $key => $item) {
            if (!isset($item['nomor_sampel']) || !isset($samples[$item['nomor_sampel']])) {
                $v->after(function ($validator) {
                    $validator->errors()->add("samples", 'Ada sampel yang tidak valid');
                });
            }
        }

        $v->validate();

        foreach ($samples as $nomor_sampel => $sampel) {
            $pcr = $sampel->pcr;
            if (!$pcr) {
                $pcr = new PemeriksaanSampel;
                $pcr->sampel_id = $sampel->id;
                $pcr->user_id = $user->id;
            }
            $pcr->tanggal_penerimaan_sampel = parseDate($request->tanggal_penerimaan_sampel);
            $pcr->jam_penerimaan_sampel = parseTime($request->jam_penerimaan_sampel);
            $pcr->petugas_penerima_sampel_rna = $request->petugas_penerima_sampel_rna;
            $pcr->catatan_penerimaan = $request->catatan_penerimaan;
            $pcr->operator_real_time_pcr = $request->operator_real_time_pcr;
            $pcr->tanggal_mulai_pemeriksaan = parseDate($request->tanggal_mulai_pemeriksaan);
            $pcr->jam_mulai_pcr = parseTime($request->jam_mulai_pcr);
            $pcr->jam_selesai_pcr = parseTime($request->jam_selesai_pcr);
            $pcr->metode_pemeriksaan = $request->metode_pemeriksaan;
            $pcr->nama_kit_pemeriksaan = $request->nama_kit_pemeriksaan;
            $pcr->tanggal_input_hasil = null;
            $pcr->jam_input_hasil = null;
            $pcr->catatan_pemeriksaan = null;
            $pcr->grafik = null;
            $pcr->hasil_deteksi = null;
            $pcr->kesimpulan_pemeriksaan = null;
            $pcr->save();

            $sampel->waktu_pcr_sample_received = date('Y-m-d H:i:s', strtotime($pcr->tanggal_mulai_ekstraksi . ' ' . $pcr->jam_mulai_ekstraksi));
            $sampel->updateState('pcr_sample_received', [
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'Receive PCR',
            ]);
        }

        return response()->json(['status' => 201, 'message' => 'Penerimaan sampel berhasil dicatat']);
    }

    public function musnahkan(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['status'])->find($id);
        if (!$sampel) {
            return response()->json(['success' => false, 'code' => 422, 'message' => 'Sampel tidak ditemukan'], 422);
        }
        $pcr = $sampel->pcr;
        if (!$pcr) {
            $pcr = new PemeriksaanSampel;
            $pcr->sampel_id = $sampel->id;
            $pcr->user_id = $user->id;
        }
        $sampel->is_musnah_pcr = true;
        $sampel->save();

        $sampel->addLog([
            'user_id' => $user->id,
            'metadata' => $pcr,
            'description' => 'Sample marked as destroyed at PCR chamber',
        ]);
        return response()->json(['success' => true, 'code' => 201, 'message' => 'Sampel berhasil ditandai telah dimusnahkan']);
    }

    public function importHasilPemeriksaan(Request $request)
    {
        $this->__importValidator($request)->validate();

        $importer = new HasilPemeriksaanImport;
        Excel::import($importer, $request->file('register_file'));

        return response()->json([
            'status' => 200,
            'message' => 'Sukses membaca file import excel',
            'data' => $importer->data,
            'errors' => $importer->errors,
            'errors_count' => $importer->errors_count,
        ]);
    }

    private function __importValidator(Request $request)
    {
        $extension = '';

        if ($request->hasFile('register_file')) {
            $extension = strtolower($request->file('register_file')->getClientOriginalExtension());
        }

        return Validator::make([
            'register_file' => $request->file('register_file'),
            'extension' => $extension,
        ], [
            'register_file' => 'required|file|max:2048',
            'extension' => 'required|in:csv,xlsx,xls',
        ]);
    }

    public function importDataHasilPemeriksaan(Request $request)
    {

        $user = $request->user();
        $data = $request->data;
        foreach ($data as $row) {
            $sampel = Sampel::with(['pcr'])->find($row['sampel_id']);
            if ($sampel) {
                $pcr = $sampel->pcr;
                if (!$pcr) {
                    $pcr = new PemeriksaanSampel;
                    $pcr->sampel_id = $sampel->id;
                    $pcr->user_id = $user->id;
                }
                $pcr->tanggal_input_hasil = date('Y-m-d', strtotime($row['tanggal_input_hasil']));
                $pcr->nama_kit_pemeriksaan = $row['nama_kit_pemeriksaan'];
                $pcr->jam_input_hasil = date('H:i');
                $pcr->catatan_pemeriksaan = $row['catatan_pemeriksaan'] != '' ? $row['catatan_pemeriksaan'] : null;
                $pcr->grafik = [];
                $pcr->hasil_deteksi = $row['target_gen'];
                $pcr->kesimpulan_pemeriksaan = $row['kesimpulan_pemeriksaan'];
                $pcr->save();

                if ($sampel->sampel_status == 'sample_taken' || $sampel->sampel_status == 'extraction_sample_sent') {
                    $sampel->updateState('pcr_sample_analyzed', [
                        'user_id' => $user->id,
                        'metadata' => $pcr,
                        'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
                    ], $row['tanggal_input_hasil']);
                } else {
                    $sampel->addLog([
                        'user_id' => $user->id,
                        'metadata' => $pcr,
                        'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
                    ]);
                    $sampel->waktu_pcr_sample_analyzed = date('Y-m-d H:i:s', strtotime($row['tanggal_input_hasil']));
                    $sampel->save();
                }
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Sukses import data.',
        ]);
    }

    public function input_hasil_pemeriksaan(request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nama_kit_pemeriksaan' => 'required',
            'nomor_sampel' => 'required',
            'kesimpulan_pemeriksaan' => 'required',
            'tanggal_mulai_pemeriksaan' => 'required',
            'ct_n' => 'required',
            'ct_e' => 'required',
            'ct_ic' => 'required',
            'ct_rdrp' => 'required',
            'catatan_pemeriksaan' => 'required',
        ]);

        if ($validator->validate()) {
            $response['status_code'] = 404;
            $response['message'] = "terjadi kesalah ketika melakukan import";
            $response['result'] = $validator->messages();
        }

        $sampel = Sampel::where('nomor_sampel', $request->nomor_sampel)->first();
        $user = \App\User::first();

        if ($sampel != '') {
            $pcr = $sampel->pcr;
            if (!$pcr) {
                $pcr = new PemeriksaanSampel;
                $pcr->sampel_id = $sampel->id;
                $pcr->user_id = $user->id;
            }

            $target_gen = [];
            $target_gen[] = ['ct_value' => (int)$request->ct_n, 'target_gen' => 'N'];
            $target_gen[] = ['ct_value' => (int)$request->ct_e, 'target_gen' => 'E'];
            $target_gen[] = ['ct_value' => (int)$request->ct_ic, 'target_gen' => 'IC'];
            $target_gen[] = ['ct_value' => (int)$request->ct_rdrp, 'target_gen' => 'RDRP'];

            $pcr->tanggal_mulai_pemeriksaan = $request->tanggal_mulai_pemeriksaan;
            $pcr->jam_input_hasil = '12:00';
            $pcr->catatan_pemeriksaan = $request->catatan_pemeriksaan;
            $pcr->grafik = [];
            $pcr->petugas_penerima_sampel_rna = "";
            $pcr->hasil_deteksi = $target_gen;
            $pcr->kesimpulan_pemeriksaan = $request->kesimpulan_pemeriksaan;
            $pcr->nama_kit_pemeriksaan = $request->nama_kit_pemeriksaan;
            $pcr->save();

            if ($sampel->sampel_status == 'pcr_sample_received' || $sampel->sampel_status == 'extraction_sample_sent') {
                $sampel->updateState('pcr_sample_analyzed', [
                    'user_id' => $user->id,
                    'metadata' => $pcr,
                    'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
                ]);
            }
            $response['message'] = "berhasil menyimpan data";
            $response['result'] = $pcr;
            $response['status_code'] = 201;
        } else {
            $sampel->addLog([
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
            ]);

            $sampel->waktu_pcr_sample_analyzed = date('Y-m-d H:i:s');
            $sampel->save();

            $response['message'] = "gagal menyimpan data";
            $response['result'] = $sampel;
            $response['status_code'] = 404;
        }

        return response()->json($response);
    }
}
