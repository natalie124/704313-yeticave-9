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

/**
 * Получает строки из MySQL
 *
 * @param $con str ресурс соединения
 * @param $sql str строка запроса
 * @return array массив с данными - если запрос выполнен, иначе ошибка
 */

function get_rows_from_mysql ($con, $sql) {
    $result = mysqli_query($con, $sql);

    return ($result) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : die("Ошибка " . mysqli_error($con));
};
