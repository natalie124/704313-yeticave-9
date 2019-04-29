<?php
/**
 * форматирует сумму лота и добавляет к ней знак рубля
 *
 * @param $price int Сумма лота
 * @return $price str Сумма лота со знаком рубля
 */
function format_price ($price) {
    $price = ceil($price);
    $price = number_format($price, 0, "", " ");

    return $price .= "<b class='rub'>р</b>";
};

/**
 * Считает время до заданной даты в формате unix time
 *
 * @param $specified_date str заданная дата
 * @return int интервал времени с настоящего момента до заданной даты в формате unix time
 */
function count_time ($specified_date) {
    return strtotime($specified_date) - time();
};
