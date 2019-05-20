<?php
require_once('init.php');
require_once('helpers.php');
require_once('data.php');
require_once('functions.php');

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии
$categories = get_rows_from_mysql($con, $sql_cat);

$cat_id = $_GET["cat_id"] ?? 0; // получаем id категории из запроса
$cat_title = $categories[$cat_id - 1]['name']; // получаем название категории на основе запроса

$cur_page = $_GET['page'] ?? 1; // получаем номер текущей страницы, по умолчанию 1
$limit = 9; // определяем количество элементов, размещенных на одной странице
$offset = ($cur_page - 1) * $limit; // определяем смещение элемента

$sql_cnt = "SELECT count(*) AS cnt FROM lots AS l
            JOIN categories AS c ON l.cat_id = c.id
            WHERE NOW() < l.dt_end AND l.win_id IS NULL AND c.id = $cat_id"; // формируем запрос для получения общего количества элементов

$res_cnt = mysqli_query($con, $sql_cnt); // получаем результат запроса из БД

$items_count = mysqli_fetch_assoc($res_cnt)['cnt']; // преобразуем результат запроса в массив

$pages_count = ceil($items_count / $limit); // получаем число страниц исходя из общего количества элементов и элементов на одной странице

$pages = range(1, $pages_count); // получаем массив на основе числа страниц


$sql_lots = "SELECT l.id, l.img_path, l.title, l.dt_end, l.price, c.name AS category, c.id AS cat_id FROM lots AS l
             JOIN categories AS c ON l.cat_id = c.id
             WHERE NOW() < l.dt_end AND l.win_id IS NULL AND c.id = $cat_id LIMIT $limit OFFSET $offset"; // получаем лоты по категории


$lots = get_rows_from_mysql($con, $sql_lots);

$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

if (empty($lots)) { // если
    $content = include_template('all-lots.php', [
        'nav_content' => $nav_content,
        'lots' => $lots,
        'cat_id' => $cat_id,
        'cur_page' => $cur_page,
        'pages' => $pages,
        'title' => 'Лоты в категории ' . '«' . $cat_title  . '»' . ' отсутствуют'
    ]);
} else {

    $content = include_template('all-lots.php', [
        'nav_content' => $nav_content,
        'lots' => $lots,
        'cat_id' => $cat_id,
        'cur_page' => $cur_page,
        'pages' => $pages,
        'title' => 'Все лоты в категории' . '«' . $cat_title  . '»'
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

?>
