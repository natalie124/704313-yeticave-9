<?php
/**
 * форматирует цену и добавляет к ней знак рубля
 *
 * @param $price float Изначальная цена
 * @return $price str Отформатированная строка
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
