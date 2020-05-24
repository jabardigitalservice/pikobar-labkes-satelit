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
use App\Imports\HasilPemeriksaanImport;
use App\Models\PasienRegister;
use App\Traits\PemeriksaanTrait;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PCRController extends Controller
{
    use PemeriksaanTrait;

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
            foreach (json_decode($params) as $key => $val) {
                if ($val == '') continue;
                switch($key) {
                    case "start_date":
                        $models = $models->whereDate('sampel.waktu_sample_taken','>=',date('Y-m-d',strtotime($val)));
                    break;
                    case "end_date":
                        $models = $models->whereDate('sampel.waktu_sample_taken','<=',date('Y-m-d',strtotime($val)));
                    break;
                    case 'instansi_pengirim': 
                        $models->whereHas('register', function ($query) use ($val){
                            $query->where('register.instansi_pengirim', 'ilike', '%'. $val .'%');
                        });
                        break;
                    default:
                        break;
                }
            }
        }
       if (Auth::user()->lab_satelit_id !=null) {
            $models->where('sampel.lab_satelit_id',Auth::user()->lab_satelit_id);
        }
        $models->where('sampel.sampel_status','sample_taken');
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
            $model->register = $model->register ?? null;
            $model->pasien = $model->register ? optional($model->register)->pasiens()->with(['kota'])->first() : null;
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
        $filename = Str::random(20).'.'.$file->getClientOriginalExtension();
        $path = Storage::disk('s3')->putFileAs(
            'grafik/'.date('Y-m-d'), $request->file('file'), $filename
        );
        $url = route('grafik.url', ['path' => date('Y-m-d') . '/' . $filename]);
        return response()->json(['status'=>200,'message'=>'success','url'=>$url]);
    }

    public function getGrafik($path)
    {
        $exists = Storage::disk('public')->exists('grafik/'.$path);
        if ($exists) {
            return response()->file(storage_path('app/public/grafik/'.$path));
        }
        $exists = Storage::disk('s3')->exists('grafik/'.$path);
        if ($exists) {
            $contents = Storage::disk('s3')->get('grafik/'.$path);
            $ret = Storage::disk('public')->put(
                'grafik/'.$path, $contents
            );
            return response()->file(storage_path('app/public/grafik/'.$path));
        } else {
            abort(404);
        }
    }

    public function detail(Request $request, $id)
    {
        $model = Sampel::with(['pcr','status','ekstraksi','register'])
                ->find($id);
        $model->log_pcr = $model->logs()
            ->whereIn('sampel_status', ['pcr_sample_received','pcr_sample_analyzed','extraction_sample_reextract'])
            ->orderByDesc('created_at')
            ->get();
        $model->pasien = $model->register ? optional($model->register)->pasiens()->with(['kota'])->first() : null;
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
            'nama_kit_pemeriksaan' => 'required',
            'hasil_deteksi.*.target_gen' => 'required',
            'hasil_deteksi.*.ct_value' => 'required',
            // 'grafik' => 'required',
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
        $pcr->hasil_deteksi = $this->parseHasilDeteksi($request->hasil_deteksi);
        $pcr->kesimpulan_pemeriksaan = $request->kesimpulan_pemeriksaan;
        $pcr->nama_kit_pemeriksaan = $request->nama_kit_pemeriksaan;
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
            $pcr->tanggal_input_hasil = null;
            $pcr->jam_input_hasil = null;
            $pcr->catatan_pemeriksaan = null;
            $pcr->grafik = null;
            $pcr->hasil_deteksi = null;
            $pcr->kesimpulan_pemeriksaan = null;
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

    public function musnahkan(Request $request, $id)
    {
        $user = $request->user();
        $sampel = Sampel::with(['status'])->find($id);
        if (!$sampel) {
            return response()->json(['success'=>false,'code'=> 422,'message'=>'Sampel tidak ditemukan'],422);
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
        return response()->json(['success'=>true,'code'=> 201,'message'=>'Sampel berhasil ditandai telah dimusnahkan']);
    }

    public function importHasilPemeriksaan(Request $request)
    {        
        $this->importValidator($request)->validate();

        $importer = new HasilPemeriksaanImport;
        Excel::import($importer, $request->file('register_file'));

        return response()->json([
            'status'=> 200,
            'message'=> 'Sukses membaca file import excel',
            'data'=> $importer->data,
            'errors'=> $importer->errors,
            'errors_count'=> $importer->errors_count,
        ]);
    }

    private function importValidator(Request $request)
    {
        $extension = '';

        if ($request->hasFile('register_file')) {
            $extension = strtolower($request->file('register_file')->getClientOriginalExtension());
        }

        return Validator::make([
            'register_file'=> $request->file('register_file'),
            'extension'=> $extension
        ],[
            'register_file'=> 'required|file|max:2048',
            'extension'=> 'required|in:csv,xlsx,xls'
        ]);
    } 

    public function importDataHasilPemeriksaan(Request $request)
    {
        
        $user = $request->user();
        $data = $request->data;
        foreach ($data as $row) {
            $sampel = Sampel::find($row['sampel_id']);
            if ($sampel) {
                $pcr = $sampel->pcr;
                if (!$pcr) {
                    $pcr = new PemeriksaanSampel;
                    $pcr->sampel_id = $sampel->id;
                    $pcr->user_id = $user->id;
                }
                $pcr->tanggal_input_hasil = $row['tanggal_input_hasil'];
                $pcr->nama_kit_pemeriksaan = $row['nama_kit_pemeriksaan'];
                $pcr->jam_input_hasil = '12:00';
                $pcr->catatan_pemeriksaan = '';
                $pcr->grafik = [];
                $pcr->hasil_deteksi = $row['target_gen'];
                $pcr->kesimpulan_pemeriksaan = $row['kesimpulan_pemeriksaan'];
                $pcr->save();

                if ($sampel->sampel_status == 'sample_taken' || $sampel->sampel_status == 'extraction_sample_sent') {
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
                    $sampel->waktu_sample_taken = date('Y-m-d H:i:s');
                    $sampel->save();
                }
            }
        }

        return response()->json([
            'status'=> 200,
            'message'=> 'Sukses import data.',
        ]);
    }

    public function input_hasil_pemeriksaan(request $request)
    {
        $validator = \Validator::make($request->all(),[
            'nama_kit_pemeriksaan'      => 'required',
            'nomor_sampel'              => 'required',
            'kesimpulan_pemeriksaan'    => 'required',
            'tanggal_mulai_pemeriksaan' => 'required',
            'ct_n'                      =>  'required',
            'ct_e'                      =>  'required',
            'ct_ic'                     =>  'required',
            'ct_rdrp'                   =>  'required',
            'catatan_pemeriksaan'       =>  'required',
        ]);

        if ($validator->validate()) {
            $response['status_code']    = 404;
            $response['message']        = "terjadi kesalah ketika melakukan import";
            $response['result']         = $validator->messages();
        }

        $sampel = Sampel::where('nomor_sampel',$request->nomor_sampel)->first();
        $user   = \App\User::first();

        if($sampel!='')
        {
            $pcr = $sampel->pcr;
            if (!$pcr) {
                $pcr            = new PemeriksaanSampel;
                $pcr->sampel_id = $sampel->id;
                $pcr->user_id   = $user->id;
            }

            $target_gen = [];
            $target_gen[] = ['ct_value'=>(int)$request->ct_n,'target_gen'=>'N'];
            $target_gen[] = ['ct_value'=>(int)$request->ct_e,'target_gen'=>'E'];
            $target_gen[] = ['ct_value'=>(int)$request->ct_ic,'target_gen'=>'IC'];
            $target_gen[] = ['ct_value'=>(int)$request->ct_rdrp,'target_gen'=>'RDRP'];
            
            $pcr->tanggal_mulai_pemeriksaan = $request->tanggal_mulai_pemeriksaan;
            $pcr->jam_input_hasil           = '12:00';
            $pcr->catatan_pemeriksaan       = $request->catatan_pemeriksaan;
            $pcr->grafik                    = [];
            $pcr->petugas_penerima_sampel_rna="";
            $pcr->hasil_deteksi             = $target_gen;
            $pcr->kesimpulan_pemeriksaan    = $request->kesimpulan_pemeriksaan;
            $pcr->nama_kit_pemeriksaan      = $request->nama_kit_pemeriksaan;
            $pcr->save();

            if ($sampel->sampel_status == 'pcr_sample_received' || $sampel->sampel_status == 'extraction_sample_sent') {
                $sampel->updateState('pcr_sample_analyzed', [
                        'user_id' => $user->id,
                        'metadata' => $pcr,
                        'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
                    ]);
                }
            $response['message']        =  "berhasil menyimpan data";
            $response['result']         =  $pcr;
            $response['status_code']    =   201;
        }else
        {
            $sampel->addLog([
                'user_id' => $user->id,
                'metadata' => $pcr,
                'description' => 'PCR Sample analyzed as [' . strtoupper($pcr->kesimpulan_pemeriksaan) . ']',
            ]);

            $sampel->waktu_pcr_sample_analyzed = date('Y-m-d H:i:s');
            $sampel->save();

            $response['message']        =  "gagal menyimpan data";
            $response['result']         =  $sampel;
            $response['status_code']    =  404;
        }

        return response()->json($response);
        
    }

}
