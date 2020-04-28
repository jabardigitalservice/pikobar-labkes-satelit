<?php

function parseDate($date) {
    return date('Y-m-d',strtotime($date));
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

?>