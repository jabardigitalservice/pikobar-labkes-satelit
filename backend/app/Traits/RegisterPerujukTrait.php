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
        $models->when($key == 'nomor_sampel', function ($query) use ($val) {
            return $query->where('nomor_sampel', 'ilike', '%' . $val . '%');
        });
        $models->when($key == 'nama_pasien', function ($query) use ($val) {
            return $query->where(function ($query) use ($val) {
                $query->where('nama_pasien', 'ilike', '%' . $val . '%')
                        ->orWhere('nik', 'ilike', '%' . $val . '%');
            });
        });
        $models->when($key == 'fasyankes', function ($query) use ($val) {
            return $query->whereHas('fasyankes', function ($query) use ($val) {
                $query->where('nama', 'ilike', '%' . $val . '%');
            });
        });
        $models->when($key == 'perujuk', function ($query) use ($val) {
            return $query->whereHas('perujuk', function ($query) use ($val) {
                $query->where('nama', 'ilike', '%' . $val . '%');
            });
        });
        $models->when($key == 'kategori', function ($query) use ($val) {
            return $query->where('sumber_pasien', 'ilike', '%' . $val . '%');
        });
        $models->when($key == 'kriteria', function ($query) use ($val) {
            return $query->where('status', $val);
        });

        $models->when($key == 'tanggal', function ($query) use ($val) {
            return $query->whereDate('created_at', date('Y-m-d', strtotime($val)));
        });
        return $models;
    }

    private function orderRegisterPerujuk($models, $order, $order_direction)
    {
        $models->when($order == 'nomor_sampel', function ($query) use ($order_direction) {
            return $query->orderBy('nomor_sampel', $order_direction);
        });
        $models->when($order == 'nama_pasien', function ($query) use ($order_direction) {
            return $query->orderBy('nama_pasien', $order_direction);
        });
        $models->when($order == 'kota', function ($query) use ($order_direction) {
            return $query->whereHas('kota', function ($query) use ($order_direction) {
                $query->orderBy('nama', $order_direction);
            });
        });
        $models->when($order == 'fasyankes', function ($query) use ($order_direction) {
            return $query->whereHas('fasyankes', function ($query) use ($order_direction) {
                $query->orderBy('nama', $order_direction);
            });
        });
        $models->when($order == 'kategori', function ($query) use ($order_direction) {
            return $query->orderBy('sumber_pasien', $order_direction);
        });
        $models->when($order == 'kriteria', function ($query) use ($order_direction) {
            return $query->orderBy('status', $order_direction);
        });
        $models->when($order == 'tanggal', function ($query) use ($order_direction) {
            return $query->orderBy('created_at', $order_direction);
        });
        $models->when($order == 'kota', function ($query) use ($order_direction) {
            return $query->orderBy('kota', $order_direction);
        });
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
