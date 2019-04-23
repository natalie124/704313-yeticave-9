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
