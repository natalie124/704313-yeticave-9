<?php
require_once('init.php');
require_once('helpers.php');
require_once('data.php');
require_once('functions.php');

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии
$categories = get_rows_from_mysql($con, $sql_cat);

$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$content = include_template('search.php', [
    'nav_content' => $nav_content
]);

$search = $_GET['search'] ?? ''; // получаем ключевые слова для поиска из формы поиска, если они были отправлены


if ($search) { // проверяем, был ли отправлен запрос на поиск

    $cur_page = $_GET['page'] ?? 1; // получаем номер текущей страницы, по умолчанию 1
    $limit = 9; // определяем количество элементов, размещенных на одной странице
    $offset = ($cur_page - 1) * $limit; // определяем смещение элемента

    $sql_cnt = "SELECT count(*) AS cnt FROM lots
                WHERE MATCH(title, description) AGAINST('$search')"; // формируем запрос для получения общего количества элементов

    $res_cnt = mysqli_query($con, $sql_cnt); // получаем результат запроса из БД

    $items_count = mysqli_fetch_assoc($res_cnt)['cnt']; // преобразуем результат запроса в массив

    $pages_count = ceil($items_count / $limit); // получаем число страниц исходя из общего количества элементов и элементов на одной странице

    $pages = range(1, $pages_count); // получаем массив на основе числа страниц

    $sql_lots = "SELECT l.id, l.title, l.img_path, l.price, l.dt_end, c.name AS category FROM lots AS l
                 JOIN categories AS c ON l.cat_id = c.id
                 WHERE MATCH(l.title, l.description) AGAINST(?) LIMIT $limit OFFSET $offset"; // получаем лоты в соответствии с поисковым запросом по полям title и description

    $stmt = db_get_prepare_stmt($con, $sql_lots, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC); // получаем массив с данными на основе поискового запроса

    $content = include_template('search.php', [ // включаем шаблон поиска с данными поискового запроса
        'nav_content' => $nav_content,
        'lots' => $lots,
        'search' => $search,
        'cur_page' => $cur_page,
        'pages' => $pages,
        'search' => $search
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

?>
