<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии
$categories = get_rows_from_mysql($con, $sql_cat);

$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$search = $_GET['search'] ?? ''; // получаем ключевые слова для поиска из формы поиска, если они были отправлены

if ($search) { // проверяем, был ли отправлен запрос на поиск

    $offset = ($cur_page - 1) * $limit; // определяем смещение элемента

    $sql_cnt = "SELECT count(*) AS cnt FROM lots
                WHERE MATCH(title, description) AGAINST('$search')"; // формируем запрос для получения общего количества элементов

    $pages = get_pages($con, $sql_cnt, $limit); // получаем количество страниц на основе запроса

    $sql_lots = "SELECT l.id, l.title, l.img_path, l.price, l.dt_end, c.name AS category FROM lots AS l
                 JOIN categories AS c ON l.cat_id = c.id
                 WHERE NOW() < l.dt_end AND l.win_id IS NULL AND MATCH(l.title, l.description) AGAINST(?) ORDER BY l.dt_add DESC
                 LIMIT $limit OFFSET $offset"; // получаем лоты в соответствии с поисковым запросом по полям title и description

    $stmt = db_get_prepare_stmt($con, $sql_lots, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC); // получаем массив с данными на основе поискового запроса

    $search_title = empty($lots) ? 'По запросу ' . '«' . $search . '»' . ' ничего не найдено' : 'Результаты поиска по запросу ' . '«' . $search . '»';

    $content = include_template('search.php', [ // включаем шаблон поиска с данными поискового запроса
        'nav_content' => $nav_content,
        'lots' => $lots,
        'search' => $search,
        'cur_page' => $cur_page,
        'pages' => $pages,
        'search' => $search,
        'search_title' => $search_title
    ]);
} else {
    $content = include_template('search.php', [
        'nav_content' => $nav_content,
        'search_title' => 'Введите текст запроса'
    ]);
}

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - Поиск лота',
    'is_auth' => $is_auth,
    'user_name' =>  $user_name,
]);

print($layout_content);
