<?php

namespace App\Traits;

use Carbon\Carbon;

/**
 * Trait Order
 */
trait OrderTrait
{
    public function scopeOrder($query, $order, $order_direction)
    {
        return $query->orderPasien($order, $order_direction)
                        ->orderKota($order, $order_direction)
                        ->orderSampel($order, $order_direction)
                        ->orderRegister($order, $order_direction);                        ;
    }

    public function scopeOrderPasien($query, $order, $order_direction)
    {
        $query->when($order == 'nama_pasien', function ($query) use ($order_direction) {
            return $query->orderBy('pasien.nama_lengkap', $order_direction);
        });
        return $query;
    }

    public function scopeOrderKota($query, $order, $order_direction)
    {
        $query->when($order == 'nama_kota', function ($query) use ($order_direction) {
            return $query->orderBy('kota.nama', $order_direction);
        });
        return $query;
    }

    public function scopeOrderSampel($query, $order, $order_direction)
    {
        $query->when($order == 'no_sampel', function ($query) use ($order_direction) {
            return $query->orderBy('sampel.nomor_sampel', $order_direction);
        });
        $query->when($order == 'tgl_input', function ($query) use ($order_direction) {
            return $query->orderBy('sampel.waktu_sample_taken', $order_direction);
        });
        return $query;
    }

    public function scopeOrderRegister($query, $order, $order_direction)
    {
        $query->when($order == 'instansi_pengirim_nama', function ($query) use ($order_direction) {
            return $query->orderBy('register.nama_rs', $order_direction);
        });
        $query->when($order == 'sumber_pasien', function ($query) use ($order_direction) {
            return $query->orderBy('register.sumber_pasien', $order_direction);
        });
        $query->when($order == 'status', function ($query) use ($order_direction) {
            return $query->orderBy('register.status', $order_direction);
        });
        return $query;
    }
}
