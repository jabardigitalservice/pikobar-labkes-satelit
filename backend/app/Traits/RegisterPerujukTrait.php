<?php

namespace App\Traits;

use App\Enums\JenisSampelEnum;
use App\Models\JenisSampel;
use Illuminate\Http\Request;

/**
 * Register trait
 *
 */
trait RegisterPerujukTrait
{
    private $params;
    private $search;
    private $order;
    private $perpage;
    private $order_direction;
    private $user;

    private function getRequestRegisterPerujuk(Request $request)
    {
        $this->params          = $request->get('params', false);
        $this->search          = $request->get('search', false);
        $this->order           = $request->get('order', 'tanggal');
        $this->perpage         = $request->get('perpage', 20);
        $this->order_direction = $request->get('order_direction', 'desc');
        $this->user            = $request->user();
    }

    private function searchRegisterPerujuk($models, $search)
    {
        $models->where(function ($q) use ($search) {
            $q->where('nomor_sampel', 'ilike', '%' . $search . '%')
                ->orWhere('nama_pasien', 'ilike', '%' . $search . '%')
                ->orWhereHas('kota', function ($query) use ($search) {
                    $query->where('nama', 'ilike', '%' . $search . '%');
                })
                ->orWhereHas('fasyankes', function ($query) use ($search) {
                    $query->where('nama', 'ilike', '%' . $search . '%');
                })
                ->orWhere('sumber_pasien', 'ilike', '%' . $search . '%')
                ->orwhere('status', $search);
        });
        return $models;
    }

    private function filterRegisterPerujuk($models, $key, $val)
    {
        switch ($key) {
            case "nomor_sampel":
                $models->where('nomor_sampel', 'ilike', '%' . $val . '%');
                break;
            case "nama_pasien":
                $models->where(function ($query) use ($val) {
                    $query->where('nama_pasien', 'ilike', '%' . $val . '%')
                        ->orWhere('nik', 'ilike', '%' . $val . '%');
                });
                break;
            case "fasyankes":
                $models->whereHas('fasyankes', function ($query) use ($val) {
                    $query->where('nama', 'ilike', '%' . $val . '%');
                });
                break;
            case "perujuk":
                $models->whereHas('perujuk', function ($query) use ($val) {
                    $query->where('nama', 'ilike', '%' . $val . '%');
                });
                break;
            case "kategori":
                $models->where('sumber_pasien', 'ilike', '%' . $val . '%');
                break;
            case "kriteria":
                $models->where('status', $val);
                break;
            case "tanggal":
                $models->whereDate('created_at', date('Y-m-d', strtotime($val)));
                break;
        }
        return $models;
    }

    private function orderRegisterPerujuk($models, $order, $order_direction)
    {
        switch ($order) {
            case "nomor_sampel":
                $models->orderBy('nomor_sampel', $order_direction);
                break;
            case "nama_pasien":
                $models->orderBy('nama_pasien', $order_direction);
                break;
            case "kota":
                $models->whereHas('kota', function ($query) use ($order_direction) {
                    $query->orderBy('nama', $order_direction);
                });
            case "fasyankes":
                $models->whereHas('fasyankes', function ($query) use ($order_direction) {
                    $query->orderBy('nama', $order_direction);
                });
                break;
            case "kategori":
                $models->orderBy('sumber_pasien', $order_direction);
                break;
            case "kriteria":
                $models->orderBy('status', $order_direction);
                break;
            case "tanggal":
                $models->orderBy('created_at', $order_direction);
                break;
            case 'kota':
                $models->orderBy('kota', $order_direction);
                break;
        }
        return $models;
    }

    private function getJenisSampel(Request $request)
    {
        $jenis_sampel = $request->input('nama_jenis_sampel');
        if ($request->input('jenis_sampel') != JenisSampelEnum::LAINNYA()->getIndex()) {
            $jenis_sampel = JenisSampel::find($request->input('jenis_sampel'))->nama;
        }
        return $jenis_sampel;
    }
}
