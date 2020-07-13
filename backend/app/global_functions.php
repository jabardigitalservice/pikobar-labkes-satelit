<?php

function parseDate($date)
{
    if ($date != null) {
        return date('Y-m-d', strtotime($date));
    }
    return $date;
}
function parseTime($time)
{
    $time = str_replace(':', '', $time);
    if (strlen($time) == 4) {
        return substr($time, 0, 2) . ':' . substr($time, 2, 2);
    } elseif (strlen($time) == 3) {
        return '0' . substr($time, 0, 1) . ':' . substr($time, 1, 2);
    } else {
        return '00:00';
    }
}
function parseDecimal($val)
{
    $val = str_replace(',', '.', $val);
    return floatval($val);
}
function cctor($obj)
{
    return clone $obj;
}

function generateNomorRegister()
{
    $date = date('Ymd');
    $kode_registrasi = 'S';
    $res = Illuminate\Support\Facades\DB::select("select max(right(nomor_register, 4))::int8 val from register where nomor_register ilike '{$kode_registrasi}{$date}%'");
    if (count($res)) {
        $nextnum = $res[0]->val + 1;
    } else {
        $nextnum = 1;
    }
    return $kode_registrasi . $date . str_pad($nextnum, 4, "0", STR_PAD_LEFT);
}
