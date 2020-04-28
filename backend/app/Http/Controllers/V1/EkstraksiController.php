<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sampel;
use App\Models\Ekstraksi;
use App\Models\LabPCR;
use Validator;
use Illuminate\Support\Arr;

class EkstraksiController extends Controller
{
    public function getData(Request $request)
    {
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
                if ($val == '' || is_array($val) && count($val) == 0) continue;
                switch ($key) {
                    case 'lab_pcr_id':
                        $models->where('lab_pcr_id', $val);
                        if ($val == 999999) {
                            if (isset($params['lab_pcr_nama']) && !empty($params['lab_pcr_nama'])) {
                                $models->where('lab_pcr_nama', 'ilike', '%'.$params['lab_pcr_nama'].'%');
                            }
                        }
                        break;
                    case 'sampel_status':
                        if ($val == 'extraction_sent') {
                            $models->whereIn('sampel_status', [
                                'extraction_sample_sent',
                                'pcr_sample_received',
                                'pcr_sample_analyzed',
                                'sample_verified',
                                'sample_valid',
                            ]);
                        } else {
                            $models->where('sampel_status', $val);
                        }
                        if ($val == 'extraction_sample_reextract') {
                            $models->with(['pcr']);
                        }
                        if ($val == 'sample_invalid') {
                            $models->with(['ekstraksi','pcr']);
                        }
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
                case 'nomor_register':
                case 'nomor_sampel':
                case 'waktu_sample_taken':
                case 'waktu_extraction_sample_extracted':
                case 'waktu_extraction_sample_sent':
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

    public function detail(Request $request, $id)
    {
        $model = Sampel::with(['ekstraksi','status'])->find($id);
        $model->log_ekstraksi = $model->logs()
            ->whereIn('sampel_status', ['extraction_sample_extracted','extraction_sample_sent','extraction_sample_reextract'])
            ->orderByDesc('created_at')
            ->get();
        return response()->json(['status'=>200,'message'=>'success','data'=>$model]);
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['ekstraksi'])->find($id);
        if ($sampel->sampel_status == 'extraction_sample_extracted') {
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
                'alat_ekstraksi' => 'required_if:metode_ekstraksi,Otomatis',
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

        $ekstraksi = $sampel->ekstraksi;
        if (!$ekstraksi) {
            $ekstraksi = new Ekstraksi;
            $ekstraksi->sampel_id = $sampel->id;
            $ekstraksi->user_id = $user->id;
        }
        $ekstraksi->tanggal_penerimaan_sampel = parseDate($request->tanggal_penerimaan_sampel);
        $ekstraksi->jam_penerimaan_sampel = parseTime($request->jam_penerimaan_sampel);
        $ekstraksi->petugas_penerima_sampel = $request->petugas_penerima_sampel;
        $ekstraksi->operator_ekstraksi = $request->operator_ekstraksi;
        $ekstraksi->tanggal_mulai_ekstraksi = parseDate($request->tanggal_mulai_ekstraksi);
        $ekstraksi->jam_mulai_ekstraksi = parseTime($request->jam_mulai_ekstraksi);
        $ekstraksi->metode_ekstraksi = $request->metode_ekstraksi;
        $ekstraksi->nama_kit_ekstraksi = $request->nama_kit_ekstraksi;
        $ekstraksi->tanggal_pengiriman_rna = parseDate($request->tanggal_pengiriman_rna);
        $ekstraksi->jam_pengiriman_rna = parseTime($request->jam_pengiriman_rna);
        $ekstraksi->nama_pengirim_rna = $request->nama_pengirim_rna;
        $ekstraksi->catatan_penerimaan = $request->catatan_penerimaan;
        $ekstraksi->catatan_pengiriman = $request->catatan_pengiriman;
        $ekstraksi->save();

        $sampel->lab_pcr_id = $request->lab_pcr_id;
        $sampel->lab_pcr_nama = $lab_pcr->id == 999999 ? $request->lab_pcr_nama : $lab_pcr->nama;
        $sampel->waktu_extraction_sample_extracted = $ekstraksi->tanggal_mulai_ekstraksi ? date('Y-m-d H:i:s', strtotime($ekstraksi->tanggal_mulai_ekstraksi . ' ' .$ekstraksi->jam_mulai_ekstraksi)) : null;
        $sampel->waktu_extraction_sample_sent = $ekstraksi->tanggal_pengiriman_rna ? date('Y-m-d H:i:s', strtotime($ekstraksi->tanggal_pengiriman_rna . ' ' .$ekstraksi->jam_pengiriman_rna)) : null;
        $sampel->save();
        
        return response()->json(['status'=>201,'message'=>'Perubahan berhasil disimpan']);
    }

    public function terima(Request $request)
    {
        $user = $request->user();
        $v = Validator::make($request->all(),[
            'tanggal_penerimaan_sampel' => 'required',
            'jam_penerimaan_sampel' => 'required',
            'petugas_penerima_sampel' => 'required',
            'operator_ekstraksi' => 'required',
            'tanggal_mulai_ekstraksi' => 'required',
            'jam_mulai_ekstraksi' => 'required',
            'metode_ekstraksi' => 'required',
            'nama_kit_ekstraksi' => 'required',
            'alat_ekstraksi' => 'required_if:metode_ekstraksi,Otomatis',
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
            $ekstraksi = $sampel->ekstraksi;
            if (!$ekstraksi) {
                $ekstraksi = new Ekstraksi;
                $ekstraksi->sampel_id = $sampel->id;
                $ekstraksi->user_id = $user->id;
            }
            $ekstraksi->tanggal_penerimaan_sampel = parseDate($request->tanggal_penerimaan_sampel);
            $ekstraksi->jam_penerimaan_sampel = parseTime($request->jam_penerimaan_sampel);
            $ekstraksi->petugas_penerima_sampel = $request->petugas_penerima_sampel;
            $ekstraksi->catatan_penerimaan = $request->catatan_penerimaan;
            $ekstraksi->operator_ekstraksi = $request->operator_ekstraksi;
            $ekstraksi->tanggal_mulai_ekstraksi = parseDate($request->tanggal_mulai_ekstraksi);
            $ekstraksi->jam_mulai_ekstraksi = parseTime($request->jam_mulai_ekstraksi);
            $ekstraksi->metode_ekstraksi = $request->metode_ekstraksi;
            $ekstraksi->nama_kit_ekstraksi = $request->nama_kit_ekstraksi;
            $ekstraksi->alat_ekstraksi = $request->alat_ekstraksi;
            $ekstraksi->save();

            $sampel->waktu_extraction_sample_extracted = date('Y-m-d H:i:s', strtotime($ekstraksi->tanggal_mulai_ekstraksi . ' ' .$ekstraksi->jam_mulai_ekstraksi));
            $sampel->updateState('extraction_sample_extracted', [
                'user_id' => $user->id,
                'metadata' => $ekstraksi,
                'description' => 'Receive Extraction',
            ]);
        }
        
        return response()->json(['status'=>201,'message'=>'Penerimaan sampel berhasil dicatat']);
    }

    public function setInvalid(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['status'])->find($id);
        if (!$sampel) {
            return response()->json(['success'=>false,'code'=> 422,'message'=>'Sampel tidak ditemukan'],422);
        }
        if ($sampel->sampel_status != 'extraction_sample_reextract') {
            return response()->json(['success'=>false,'code'=> 422,'message'=>'Status sampel sudah pada tahap '. $sampel->status->deskripsi . ', sehingga tidak dapat ditandai sebagai invalid'],422);
        }
        $ekstraksi = $sampel->ekstraksi;
        if (!$ekstraksi) {
            $ekstraksi = new Ekstraksi;
            $ekstraksi->sampel_id = $sampel->id;
            $ekstraksi->user_id = $user->id;
        }
        $ekstraksi->catatan_pengiriman = $request->alasan;
        $ekstraksi->save();

        $sampel->updateState('sample_invalid', [
            'user_id' => $user->id,
            'metadata' => $ekstraksi,
            'description' => 'Sample marked as invalid',
        ]);
        return response()->json(['success'=>true,'code'=> 201,'message'=>'Sampel berhasil ditandai sebagai invalid']);
    }

    public function setProses(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['status'])->find($id);
        if (!$sampel) {
            return response()->json(['success'=>false,'code'=> 422,'message'=>'Sampel tidak ditemukan'],422);
        }
        if ($sampel->sampel_status != 'extraction_sample_reextract') {
            return response()->json(['success'=>false,'code'=> 422,'message'=>'Status sampel sudah pada tahap '. $sampel->status->deskripsi . ', sehingga tidak dapat ditandai sebagai invalid'],422);
        }
        $ekstraksi = $sampel->ekstraksi;
        if (!$ekstraksi) {
            $ekstraksi = new Ekstraksi;
            $ekstraksi->sampel_id = $sampel->id;
            $ekstraksi->user_id = $user->id;
        }
        $ekstraksi->catatan_pengiriman = $request->alasan;
        $ekstraksi->save();

        $sampel->updateState('extraction_sample_extracted', [
            'user_id' => $user->id,
            'metadata' => $ekstraksi,
            'description' => 'Sample marked as processed',
        ]);
        return response()->json(['success'=>true,'code'=> 201,'message'=>'Sampel berhasil ditandai sebagai sampel proses']);
    }

    public function kirim(Request $request)
    {
        $user = $request->user();
        $v = Validator::make($request->all(),[
            'tanggal_pengiriman_rna' => 'required',
            'jam_pengiriman_rna' => 'required',
            'nama_pengirim_rna' => 'required',
            'lab_pcr_id' => 'required',
            'lab_pcr_nama' => 'required_if:lab_pcr_id,999999',
        ]);
        $samples = Sampel::whereIn('nomor_sampel', Arr::pluck($request->samples, 'nomor_sampel'))->get()->keyBy('nomor_sampel');
        $lab_pcr = LabPCR::find($request->lab_pcr_id);

        foreach($request->samples as $key => $item) {
            if (!isset($item['nomor_sampel']) || !isset($samples[$item['nomor_sampel']])) {
                $v->after(function ($validator) {
                    $validator->errors()->add("samples", 'Ada sampel yang tidak valid');
                });
            }
        }
        if (!$lab_pcr) {
            $v->after(function ($validator) {
                $validator->errors()->add("samples", 'Lab PCR Tidak ditemukan');
            });
        }

        $v->validate();

        foreach($samples as $nomor_sampel => $sampel) {
            $ekstraksi = $sampel->ekstraksi;
            if (!$ekstraksi) {
                $ekstraksi = new Ekstraksi;
                $ekstraksi->sampel_id = $sampel->id;
                $ekstraksi->user_id = $user->id;
            }
            $ekstraksi->tanggal_pengiriman_rna = parseDate($request->tanggal_pengiriman_rna);
            $ekstraksi->jam_pengiriman_rna = parseTime($request->jam_pengiriman_rna);
            $ekstraksi->nama_pengirim_rna = $request->nama_pengirim_rna;
            $ekstraksi->catatan_pengiriman = $request->catatan_pengiriman;
            $ekstraksi->save();

            $sampel->lab_pcr_id = $request->lab_pcr_id;
            $sampel->lab_pcr_nama = $lab_pcr->id == 999999 ? $request->lab_pcr_nama : $lab_pcr->nama;
            $sampel->waktu_extraction_sample_sent = date('Y-m-d H:i:s', strtotime($ekstraksi->tanggal_pengiriman_rna . ' ' .$ekstraksi->jam_pengiriman_rna));
            $sampel->updateState('extraction_sample_sent', [
                'user_id' => $user->id,
                'metadata' => $ekstraksi,
                'description' => 'Extraction Sent',
            ]);
        }
        
        return response()->json(['status'=>201,'message'=>'Pengiriman sampel berhasil dicatat']);
    }

    public function kirimUlang(Request $request)
    {
        $user = $request->user();
        $v = Validator::make($request->all(),[
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
        $samples = Sampel::whereIn('nomor_sampel', Arr::pluck($request->samples, 'nomor_sampel'))->get()->keyBy('nomor_sampel');
        $lab_pcr = LabPCR::find($request->lab_pcr_id);

        foreach($request->samples as $key => $item) {
            if (!isset($item['nomor_sampel']) || !isset($samples[$item['nomor_sampel']])) {
                $v->after(function ($validator) {
                    $validator->errors()->add("samples", 'Ada sampel yang tidak valid');
                });
            }
        }
        if (!$lab_pcr) {
            $v->after(function ($validator) {
                $validator->errors()->add("samples", 'Lab PCR Tidak ditemukan');
            });
        }

        $v->validate();

        foreach($samples as $nomor_sampel => $sampel) {
            $ekstraksi = $sampel->ekstraksi;
            if (!$ekstraksi) {
                $ekstraksi = new Ekstraksi;
                $ekstraksi->sampel_id = $sampel->id;
                $ekstraksi->user_id = $user->id;
            }
            $ekstraksi->operator_ekstraksi = $request->operator_ekstraksi;
            $ekstraksi->tanggal_mulai_ekstraksi = parseDate($request->tanggal_mulai_ekstraksi);
            $ekstraksi->jam_mulai_ekstraksi = parseTime($request->jam_mulai_ekstraksi);
            $ekstraksi->metode_ekstraksi = $request->metode_ekstraksi;
            $ekstraksi->nama_kit_ekstraksi = $request->nama_kit_ekstraksi;
            $ekstraksi->tanggal_pengiriman_rna = parseDate($request->tanggal_pengiriman_rna);
            $ekstraksi->jam_pengiriman_rna = parseTime($request->jam_pengiriman_rna);
            $ekstraksi->nama_pengirim_rna = $request->nama_pengirim_rna;
            $ekstraksi->catatan_pengiriman = $request->catatan_pengiriman;
            $ekstraksi->save();

            $sampel->lab_pcr_id = $request->lab_pcr_id;
            $sampel->lab_pcr_nama = $lab_pcr->id == 999999 ? $request->lab_pcr_nama : $lab_pcr->nama;
            $sampel->waktu_extraction_sample_sent = date('Y-m-d H:i:s', strtotime($ekstraksi->tanggal_pengiriman_rna . ' ' .$ekstraksi->jam_pengiriman_rna));
            $sampel->updateState('extraction_sample_sent', [
                'user_id' => $user->id,
                'metadata' => $ekstraksi,
                'description' => 'Extraction Sent',
            ]);
        }
        
        return response()->json(['status'=>201,'message'=>'Pengiriman ulang sampel berhasil dicatat']);
    }

}
