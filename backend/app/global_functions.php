<?php

function parseDate($date) {
    if ($date != null) {
        return date('Y-m-d',strtotime($date));
    }
    return $date;
}
function parseTime($time) {
    $time = str_replace(':','', $time);
    if (strlen($time) == 4) {
        return substr($time, 0, 2) . ':' . substr($time, 2, 2);
    } else if (strlen($time) == 3) {
        return '0'.substr($time, 0, 1) . ':' . substr($time, 1, 2);
    } else {
        return '00:00';
    }
}
function parseDecimal($val) {
    $val = str_replace(',','.', $val);
    return floatval($val);
}
function cctor($obj) {
    return clone $obj;
}

?>