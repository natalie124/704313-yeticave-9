<?php
require_once('init.php');
require_once('helpers.php');
require_once('data.php');
require_once('functions.php');
require_once ('getwinner.php');

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии

$sql_lots = "SELECT l.id, l.title AS name, l.price AS start_price, l.img_path, l.dt_end, c.name AS category FROM lots AS l
            LEFT JOIN categories AS c ON l.cat_id = c.id
            WHERE NOW() < l.dt_end AND l.win_id IS NULL
            ORDER BY l.dt_add DESC
            LIMIT 9"; // получаем самые новые открытые лоты (каждый лот включает название, стартовую цену, ссылку на изображение, название категории)

$categories = get_rows_from_mysql($con, $sql_cat);
$lots = get_rows_from_mysql($con, $sql_lots);

$content = include_template('index.php', [
    'lots' => $lots,
    'categories' => $categories
]);

$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - Главная',
    'container' => 'container',
    'is_auth' => $is_auth,
    'user_name' =>  $user_name
]);

print($layout_content);

?>
