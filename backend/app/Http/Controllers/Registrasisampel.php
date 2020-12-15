<?php

namespace App\Http\Controllers;

use App\Exports\RegisMandiriExport;
use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Registrasisampel extends Controller
{
    public function getData(Request $request)
    {
        $user = Auth::user();
        $models = Register::leftJoin('pasien_register', 'register.id', 'pasien_register.register_id')
            ->leftJoin('pasien', 'pasien.id', 'pasien_register.pasien_id')
            ->leftJoin('sampel', 'register.id', 'sampel.register_id')
            ->leftJoin('kota', 'kota.id', 'pasien.kota_id')
            ->where('register.lab_satelit_id', $user->lab_satelit_id)
            ->where('sampel.lab_satelit_id', $user->lab_satelit_id)
            ->where('pasien.lab_satelit_id', $user->lab_satelit_id)
            ->whereNull('sampel.deleted_at');

        $params = $request->get('params', false);
        $search = $request->get('search', false);
        $order = $request->get('order', 'name');

        if ($search != '') {
            $models = $models->where(function ($q) use ($search) {
                $q->where('pasien.nama_lengkap', 'ilike', '%' . $search . '%')
                    ->orWhere('pasien.nik', 'ilike', '%' . $search . '%')
                    ->orWhere('kota.nama', 'ilike', '%' . $search . '%')
                    ->orWhere('register.instansi_pengirim_nama', 'ilike', '%' . $search . '%')
                    ->orWhere('pasien.usia_tahun', 'ilike', '%' . $search . '%')
                    ->orWhere('pasien.usia_bulan', 'ilike', '%' . $search . '%')
                    ->orWhere('register.sumber_pasien', 'ilike', '%' . $search . '%')
                    ->orWhere('register.status', 'ilike', '%' . $search . '%')
                    ->orWhere('sampel.nomor_sampel', 'ilike', '%' . $search . '%');
            });
        }

        if ($params) {
            foreach (json_decode($params) as $key => $val) {
                if ($val == '') {
                    continue;
                }

                switch ($key) {
                    case "nama_pasien":
                        $models = $models->where('pasien.nama_lengkap', 'ilike', '%' . $val . '%')
                            ->orWhere('pasien.nik', 'ilike', '%' . $val . '%');
                        break;
                    case "start_date":
                        $models = $models->whereDate('sampel.waktu_sample_taken', '>=', date('Y-m-d', strtotime($val)));
                        break;
                    case "end_date":
                        $models = $models->whereDate('sampel.waktu_sample_taken', '<=', date('Y-m-d', strtotime($val)));
                        break;
                    case "kota":
                        $models = $models->where('pasien.kota_id', $val);
                        break;
                    case "fasyankes_id":
                        $models = $models->where('register.fasyankes_id', $val);
                        break;
                    case "sumber_pasien":
                        $models = $models->where('register.sumber_pasien', 'ilike', '%' . $val . '%');
                        break;
                    case "status":
                        $models = $models->where('register.status', 'ilike', '%' . $val . '%');
                        break;
                    case "nomor_sampel":
                        $models = $models->where('sampel.nomor_sampel', 'ilike', '%' . $val . '%');
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
                $order_direction = 'desc';
            }

            switch ($order) {
                case 'nama_pasien':
                    $models = $models->orderBy('pasien.nama_lengkap', $order_direction);
                    break;
                case 'tgl_input':
                    $models = $models->orderBy('sampel.waktu_sample_taken', $order_direction);
                    break;
                case 'nama_kota':
                    $models = $models->orderBy('kota.nama', $order_direction);
                    break;
                case 'instansi_pengirim_nama':
                    $models = $models->orderBy('register.nama_rs', $order_direction);
                    break;
                case 'no_sampel':
                    $models = $models->orderBy('sampel.nomor_sampel', $order_direction);
                    break;
                case 'sumber_pasien':
                    $models = $models->orderBy('register.sumber_pasien', $order_direction);
                    break;
                case 'status':
                    $models = $models->orderBy('register.status', $order_direction);
                    break;
                default:
                    break;
            }
        }
        $models = $models->select('nik', 'nama_lengkap', 'tanggal_lahir', 'usia_tahun', 'nomor_sampel', 'kota.nama as nama_kota', 'pasien_register.*', 'register.sumber_pasien', 'status', 'waktu_sample_taken', 'nama_rs', 'sampel_status', 'register_perujuk_id');
        $models = $models->skip(($page - 1) * $perpage)->take($perpage)->get();

        $result = [
            'data' => $models,
            'count' => $count,
        ];

        return response()->json($result);
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable', // 'date|date_format:Y-m-d',
             'end_date' => 'nullable', // 'date|date_format:Y-m-d',
        ]);

        $payload = [];

        if ($request->has('start_date')) {
            $payload['startDate'] = parseDate($request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $payload['endDate'] = date('Y-m-d', strtotime($request->input('end_date') . "+1 days"));
        }

        return (new RegisMandiriExport($payload))->download('registrasi-mandiri-' . time() . '.xlsx');
    }

}
