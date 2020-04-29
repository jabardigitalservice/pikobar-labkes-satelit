<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sampel;
use App\Models\PemeriksaanSampel;
use App\Models\LabPCR;
use Validator;
use Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PCRController extends Controller
{
    public function getData(Request $request)
    {
        $user = $request->user();
        $models = Sampel::query();
        $params = $request->get('params',false);
        $search = $request->get('search',false);
        $order  = $request->get('order' ,'name');

        if ($search != '') {
            $models = $models->where(function($q) use ($search) {
                $q->where('nomor_register','ilike','%'.$search.'%')
                   ->orWhere('nomor_sampel','ilike','%'.$search.'%');
            });
        }
        if ($params) {
            $params = json_decode($params, true);
            foreach ($params as $key => $val) {
                if ($val !== false && ($val == '' || is_array($val) && count($val) == 0)) continue;
                switch ($key) {
                    case 'filter_inconclusive':
                        if ($val) {
                            $models->whereHas('pcr', function($q) {
                                $q->where('kesimpulan_pemeriksaan', 'inkonklusif');
                            });
                        } else {
                            $models->where(function ($qr) {
                                $qr->whereHas('pcr', function($q) {
                                    $q->where('kesimpulan_pemeriksaan', '<>', 'inkonklusif')->orWhereNull('kesimpulan_pemeriksaan');
                                })->orWhereDoesntHave('pcr');
                            });
                        }
                        break;
                    case 'lab_pcr_id':
                        $models->where('lab_pcr_id', $val);
                        if ($val == 999999) {
                            if (isset($params['lab_pcr_nama']) && !empty($params['lab_pcr_nama'])) {
                                $models->where('lab_pcr_nama', 'ilike', '%'.$params['lab_pcr_nama'].'%');
                            }
                        }
                        break;
                    case 'kesimpulan_pemeriksaan':
                        $models->whereHas('pcr', function($q) use ($val) {
                            $q->where('kesimpulan_pemeriksaan', $val);
                        });
                        break;
                    case 'sampel_status':
                        if ($val == 'analyzed') {
                            $models->whereIn('sampel_status', [
                                'pcr_sample_analyzed',
                                'sample_verified',
                                'sample_valid',
                            ]);
                            $models->with(['pcr','status']);
                        } else {
                            $models->where('sampel_status', $val);
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        if (!empty($user->lab_pcr_id)) {
            $models->where('lab_pcr_id', $user->lab_pcr_id);
        }
        $count = $models->count();

        $page = $request->get('page',1);
        $perpage = $request->get('perpage',999999);

         if ($order) {
            $order_direction = $request->get('order_direction','asc');
            if (empty($order_direction)) $order_direction = 'asc';

            switch ($order) {
                case 'nomor_register':
                case 'nomor_sampel':
                case 'waktu_extraction_sample_sent':
                case 'waktu_pcr_sample_received':
                case 'waktu_pcr_sample_analyzed':
                case 'waktu_extraction_sample_reextract':
                    $models = $models->orderBy($order,$order_direction);
                default:
                    break;
            }
        }
        $models = $models->skip(($page-1) * $perpage)->take($perpage)->get();
        
        // format data
        foreach ($models as &$model) {
        }

        $result = [
            'data' => $models,
            'count' => $count
        ];

        return response()->json($result);
    }

    public function uploadGrafik(Request $request)
    {
        $file = $request->file('file');
        $path = Storage::disk('public')->putFileAs(
            'grafik/'.date('Y-m-d'), $request->file('file'), Str::random(20).'.'.$file->getClientOriginalExtension()
        );
        $url = asset('storage/'.$path);
        return response()->json(['status'=>200,'message'=>'success','url'=>$url]);
    }

    public function detail(Request $request, $id)
    {
        $model = Sampel::with(['pcr','status','ekstraksi'])->find($id);
        $model->log_pcr = $model->logs()
            ->whereIn('sampel_status', ['pcr_sample_received','pcr_sample_analyzed','extraction_sample_reextract'])
            ->orderByDesc('created_at')
            ->get();
        return response()->json(['status'=>200,'message'=>'success','data'=>$model]);
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['pcr'])->find($id);
        if ($sampel->sampel_status == 'pcr_sample_received') {
            $v = Validator::make($request->all(),[
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
            $v = Validator::make($request->all(),[
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
        $sampel->waktu_pcr_sample_received = $pcr->tanggal_mulai_ekstraksi ? date('Y-m-d H:i:s', strtotime($pcr->tanggal_mulai_ekstraksi . ' ' .$pcr->jam_mulai_ekstraksi)) : null;
        $sampel->waktu_extraction_sample_sent = $pcr->tanggal_pengiriman_rna ? date('Y-m-d H:i:s', strtotime($pcr->tanggal_pengiriman_rna . ' ' .$pcr->jam_pengiriman_rna)) : null;
        $sampel->save();
        
        return response()->json(['status'=>201,'message'=>'Perubahan berhasil disimpan']);
    }

    public function invalid(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['pcr'])->find($id);
        $v = Validator::make($request->all(),[
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
        
        return response()->json(['status'=>201,'message'=>'Perubahan berhasil disimpan']);
    }

    public function input(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['pcr'])->find($id);
        $v = Validator::make($request->all(),[
            'kesimpulan_pemeriksaan' => 'required',
            'hasil_deteksi.*.target_gen' => 'required',
            'hasil_deteksi.*.ct_value' => 'required',
            'grafik' => 'required',
        ]);
        // cek minimal file
        // if (count($request->grafik) < 1) {
        //     $v->after(function ($validator) {
        //         $validator->errors()->add("samples", 'Minimal 1 file untuk grafik');
        //     });
        // }
        if (count($request->hasil_deteksi) < 1) {
            $v->after(function ($validator) {
                $validator->errors()->add("samples", 'Minimal 1 hasil deteksi CT Value');
            });
        }

        $v->validate();

        $pcr = $sampel->pcr;
        if (!$pcr) {
            $pcr = new PemeriksaanSampel;
            $pcr->sampel_id = $sampel->id;
            $pcr->user_id = $user->id;
        }
        $pcr->tanggal_input_hasil = $request->tanggal_input_hasil;
        $pcr->jam_input_hasil = $request->jam_input_hasil;
        $pcr->catatan_pemeriksaan = $request->catatan_pemeriksaan;
        $pcr->grafik = $request->grafik;
        $pcr->hasil_deteksi = $request->hasil_deteksi;
        $pcr->kesimpulan_pemeriksaan = $request->kesimpulan_pemeriksaan;
        $pcr->save();

        if ($sampel->sampel_status == 'pcr_sample_received') {
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
        
        return response()->json(['status'=>201,'message'=>'Hasil analisa berhasil disimpan']);
    }

    public function terima(Request $request)
    {
        $user = $request->user();
        $v = Validator::make($request->all(),[
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

        foreach($request->samples as $key => $item) {
            if (!isset($item['nomor_sampel']) || !isset($samples[$item['nomor_sampel']])) {
                $v->after(function ($validator) {
                    $validator->errors()->add("samples", 'Ada sampel yang tidak valid');
                });
            }
        }

        $v->validate();

        foreach($samples as $nomor_sampel => $sampel) {
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
            $pcr->save();

            $sampel->waktu_pcr_sample_received = date('Y-m-d H:i:s', strtotime($pcr->tanggal_mulai_ekstraksi . ' ' .$pcr->jam_mulai_ekstraksi));
            $sampel->updateState('pcr_sample_received', [
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'Receive PCR',
            ]);
        }
        
        return response()->json(['status'=>201,'message'=>'Penerimaan sampel berhasil dicatat']);
    }

}
