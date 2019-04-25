<?php

function format_price ($price) {
// форматирует сумму лота и добавляет к ней знак рубля
    $price = ceil($price);
    $price = number_format($price, 0, "", " ");

    return $price .= "<b class='rub'>р</b>";
};

function esc_str($str) {
    return htmlspecialchars($str);
};

date_default_timezone_set("Europe/Moscow");

function count_time () {
//считает время до начала следующего дня и возвращает его в формате “ЧЧ:ММ”
    $ts_midnight = strtotime('tomorrow');
    $secs_to_midnight = $ts_midnight - time();
    $hours = floor($secs_to_midnight / 3600);
    $minutes = floor(($secs_to_midnight % 3600) / 60);
    if ($minutes < 10) {
        $minutes = '0'.$minutes;
    };
    if ($hours < 10) {
        $hours = '0'.$hours;
    };

    return $lot_time = $hours.":".$minutes;
};

function timer_finishing ($time) {
//добавляет класс "timer--finishing", если времени осталось меньше часа
    $timer_end;
    if ($time < 1) {
        $timer_end = "timer--finishing";
    }

    return $timer_end;
}
