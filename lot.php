<?php

require_once('helpers.php');
require_once('data.php');
require_once('functions.php');
require_once('init.php');

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии из БД
$categories = get_rows_from_mysql($con, $sql_cat); // преобразуем строки категорий в массив

$lot_id = mysqli_real_escape_string($con, $_GET["lot_id"]); //получаем id текущего лота

$sql_lot = "SELECT l.*, c.name FROM lots AS l
        LEFT JOIN categories AS c ON l.cat_id = c.id
        WHERE l.id = $lot_id;"; // получаем лот из БД по id

$lot = get_row_from_mysql($con, $sql_lot); // преобразуем строку лота в массив

$nav_content = include_template('nav.php', [
    'categories' => $categories
]); //включаем меню с категориями

if (empty($lot)) {
// если лот пустой, включаем шаблон ошибки
    $content = include_template('404.php', [
        'nav_content' => $nav_content
    ]);
} else {
// включаем шаблон лота
    $content = include_template('lot.php', [
        'lot' => $lot,
        'nav_content' => $nav_content
    ]);
};

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - просмотр лота ' . $lot['title']
]);

print($layout_content);

?>
