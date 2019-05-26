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
 * @param $date str заданная дата
 * @return int интервал времени с настоящего момента до заданной даты в формате unix time
 */
function count_time ($date) {
    return strtotime($date) - time();
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

/**
 * Получает строкy из MySQL
 *
 * @param $con str ресурс соединения
 * @param $sql str строка запроса
 * @return array массив с данными - если запрос выполнен, иначе ошибка
 */

function get_row_from_mysql ($con, $sql) {
    $result = mysqli_query($con, $sql);

    return ($result) ? mysqli_fetch_assoc($result) : die("Ошибка " . mysqli_error($con));
};

/**
 * Считает и форматирует дату и время
 * @param DATETIME дата и время из БД
 * @return str отформатированные дата и время
 */

function count_format_date ($date) {

    $date = strtotime($date);
    $time = time() + 3600;

    $date_diff = $time - $date;

    $minutes = floor(($date_diff % 3600) / 60);
    $hours = floor($date_diff / 3600);

    if ($date_diff < 120) {

        $date = 'минутy назад';

    } elseif ($date_diff < 3600 && $date_diff >= 120) {

        $date = $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад";

    } elseif ($date_diff < 7200 && $date_diff >= 3600) {

        $date = 'час назад';

    } elseif ($date_diff < 86400 && $date_diff >= 7200) {

        $date = $hours . " " . get_noun_plural_form($hours, 'час', 'часа', 'часов') . " назад";

    } elseif ($date_diff < 172800 && $date_diff >= 86400) {

        $date = "Вчера, в " . date('H:i', $date);

    } else {

        $date = date('d.m.y', $date) . " в " . date('H:i', $date);
    }

    return $date;
};

/**
 * Получает количество страниц для пагинации
 *
 * @param $con str ресурс соединения
 * @param $sql str строка запроса для получения общего количества элементов
 * @param $limit int число элементов на странице
 * @return array массив с данными
 */

function get_pages ($con, $sql_cnt, $limit) {

    $res_cnt = mysqli_query($con, $sql_cnt);
    $items_count = mysqli_fetch_assoc($res_cnt)['cnt'];

    $pages_count = ceil($items_count / $limit);

    return $pages = range(1, $pages_count);
};
