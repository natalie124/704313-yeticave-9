<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$sql_cat = 'SELECT id, name, symbol_code FROM categories'; // получаем все категрии
$categories = get_rows_from_mysql($con, $sql_cat);

$cat_id = $_GET['cat_id'] ?? 0; // получаем id категории из запроса
$cat_id = (int)$cat_id;

$sql_cnt = "SELECT count(*) AS cnt FROM lots AS l
            JOIN categories AS c ON l.cat_id = c.id
            WHERE NOW() < l.dt_end AND l.win_id IS NULL AND c.id = $cat_id"; // формируем запрос для получения общего количества элементов

$pages = get_pages($con, $sql_cnt, $limit); // получаем количество страниц


$cat_title = $categories[$cat_id - 1]['name'] ?? 0; // получаем название категории на основе запроса

$nav_content = include_template('nav.php', [
    'categories' => $categories
]);


if ($cat_id > count($categories) || $cat_id < 1 || !in_array($cur_page, $pages)) {

    $content = include_template ('404.php', [
        'nav_content' => $nav_content
    ]);
} else {

    $offset = (($cur_page - 1) * $limit); // определяем смещение элемента
    $sql_lots = "SELECT l.id, l.img_path, l.title, l.dt_end, l.dt_add, l.price, c.name AS category, c.id AS cat_id FROM lots AS l
                 JOIN categories AS c ON l.cat_id = c.id
                 WHERE NOW() < l.dt_end AND l.win_id IS NULL AND c.id = $cat_id ORDER BY l.dt_add DESC LIMIT $limit OFFSET $offset"; // получаем лоты по категории


    $lots = get_rows_from_mysql($con, $sql_lots);

    $title = empty($lots) ? 'Лоты в категории ' . '«' . $cat_title  . '»' . ' отсутствуют': 'Все лоты в категории ' . '«' . $cat_title  . '»';

    $content = include_template('all-lots.php', [
        'nav_content' => $nav_content,
        'lots' => $lots,
        'cat_id' => $cat_id,
        'cur_page' => $cur_page,
        'pages' => $pages,
        'title' => $title
    ]);
}

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - Все лоты в категории ' . '«' .  $cat_title . '»',
    'is_auth' => $is_auth,
    'user_name' =>  $user_name
]);

print($layout_content);
