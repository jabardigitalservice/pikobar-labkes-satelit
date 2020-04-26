<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;
use App\Models\PemeriksaanSampel;
use App\Models\LabPCR;
use Validator;
use Illuminate\Support\Arr;

class PCRController extends Controller
{
    public function getData(Request $request)
    {
        $user = $request->user();
        $models = Register::query();
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
                    case 'register_status':
                        if ($val == 'extraction_sent') {
                            $models->whereIn('register_status', [
                                'extraction_sample_sent',
                                'pcr_sample_received',
                                'pcr_sample_analyzed',
                                'sample_verified',
                                'sample_valid',
                            ]);
                        } else {
                            $models->where('register_status', $val);
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

    public function detail(Request $request, $id)
    {
        $model = Register::with(['pcr','status'])->find($id);
        $model->log_pcr = $model->logs()
            ->whereIn('register_status', ['pcr_sample_received','pcr_sample_analyzed','extraction_sample_reextract'])
            ->orderByDesc('created_at')
            ->get();
        return response()->json(['status'=>200,'message'=>'success','data'=>$model]);
    }

    public function edit(Request $request, $id)
    {
        $user = $request->user();
        $register = Register::with(['pcr'])->find($id);
        if ($register->register_status == 'pcr_sample_received') {
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

        $pcr = $register->ekstraksi;
        if (!$pcr) {
            $pcr = new Ekstraksi;
            $pcr->register_id = $register->id;
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

        $register->lab_pcr_id = $request->lab_pcr_id;
        $register->lab_pcr_nama = $lab_pcr->id == 999999 ? $request->lab_pcr_nama : $lab_pcr->nama;
        $register->waktu_pcr_sample_received = $pcr->tanggal_mulai_ekstraksi ? date('Y-m-d H:i:s', strtotime($pcr->tanggal_mulai_ekstraksi . ' ' .$pcr->jam_mulai_ekstraksi)) : null;
        $register->waktu_extraction_sample_sent = $pcr->tanggal_pengiriman_rna ? date('Y-m-d H:i:s', strtotime($pcr->tanggal_pengiriman_rna . ' ' .$pcr->jam_pengiriman_rna)) : null;
        $register->save();
        
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
        ]);
        $samples = Register::whereIn('nomor_sampel', Arr::pluck($request->samples, 'nomor_sampel'))->get()->keyBy('nomor_sampel');

        foreach($request->samples as $key => $item) {
            if (!isset($item['nomor_sampel']) || !isset($samples[$item['nomor_sampel']])) {
                $v->after(function ($validator) {
                    $validator->errors()->add("samples", 'Ada sampel yang tidak valid');
                });
            }
        }

        $v->validate();

        foreach($samples as $nomor_sampel => $register) {
            $pcr = $register->ekstraksi;
            if (!$pcr) {
                $pcr = new PemeriksaanSampel;
                $pcr->register_id = $register->id;
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

            $register->waktu_pcr_sample_received = date('Y-m-d H:i:s', strtotime($pcr->tanggal_mulai_ekstraksi . ' ' .$pcr->jam_mulai_ekstraksi));
            $register->updateState('pcr_sample_received', [
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'Receive PCR',
            ]);
        }
        
        return response()->json(['status'=>201,'message'=>'Penerimaan sampel berhasil dicatat']);
    }

}
