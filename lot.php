<?php

require_once('init.php');
require_once('data.php');
require_once('helpers.php');
require_once('functions.php');


$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии из БД

$categories = get_rows_from_mysql($con, $sql_cat); // преобразуем строки категорий в массив

$lot_id = $_GET["lot_id"] ?? 0; //получаем id текущего лота

$sql_lot = "SELECT l.*, c.name FROM lots AS l
            LEFT JOIN categories AS c ON l.cat_id = c.id
            WHERE l.id = '$lot_id'"; // получаем лот из БД по id

$lot = get_row_from_mysql($con, $sql_lot); // преобразуем строку лота в массив

$sql_bets = "SELECT b.id, b.dt_add, b.bet_price, b.lot_id, u.id, u.name FROM bets AS b
            LEFT JOIN users AS u ON u.id = b.user_id
            WHERE b.lot_id = '$lot_id'
            ORDER BY b.bet_price DESC LIMIT 10"; // получаем все ставки к лоту

$bets = get_rows_from_mysql($con, $sql_bets); // преобразуем ставки в массив

$cur_price = $bets[0]['bet_price'] ?? $lot['price']; // текущая цена лота равна последней ставке либо стартовой цене
$min_bet = $cur_price + $lot['bet_step'];

$errors = [];
$errors_class = 'form__item--invalid';


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
        'nav_content' => $nav_content,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'cur_price' => $cur_price,
        'min_bet' => $min_bet,
        'bets' => $bets
    ]);
};

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bet = mysqli_real_escape_string($con, $_POST['cost']);

    if (empty($bet)) {
        $errors['cost'] = 'введите сумму ставки';
    } elseif (!is_numeric($bet)) {
        $errors['cost'] = 'ставка должна быть числом';
    } elseif ($bet < $min_bet) {
        $errors['cost'] = 'минимальная ставка: ' . $min_bet;
    } elseif ($lot['user_id'] === $cur_user_id) {
        $errors['cost'] = 'Вы не можете сделать ставку';
    } elseif ((count_time($lot['dt_end'])) < 1) {
        $errors['cost'] = 'Время вышло';
    }

    if (count($errors)) {
        $content = include_template('lot.php', [
            'lot' => $lot,
            'nav_content' => $nav_content,
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'cur_price' => $cur_price,
            'min_bet' => $min_bet,
            'errors' => $errors,
            'bets' => $bets
        ]);
    } else {
        $sql = 'INSERT INTO bets (bet_price, user_id, lot_id) VALUES (?, ?, ?)'; // формируем запрос на добавление

        $stmt = db_get_prepare_stmt($con, $sql, [$bet, $cur_user_id, $lot_id]); // формируем подготовленное выражение, на основе SQL-запроса и значений для него

        $res = mysqli_stmt_execute($stmt); // выполняем полученное выражение

        if($res) { // если запрос выполнен успешно, то получаем ID нового лота и перенаправляем пользователя на страницу с его просмотром

            $sql_bets = "SELECT b.id, b.dt_add, b.bet_price, u.name FROM bets AS b
            LEFT JOIN users AS u ON u.id = b.user_id
            WHERE b.lot_id = '$lot_id'
            ORDER BY b.bet_price DESC LIMIT 10"; // получаем все ставки к лоту

            $bets = get_rows_from_mysql($con, $sql_bets); // преобразуем ставки в массив

            $cur_price = $bets[0]['bet_price'] ?? $lot['price']; // текущая цена лота равна последней ставке либо стартовой цене
            $min_bet = $cur_price + $lot['bet_step'];

            $content = include_template('lot.php', [
                'lot' => $lot,
                'nav_content' => $nav_content,
                'is_auth' => $is_auth,
                'user_name' => $user_name,
                'cur_price' => $cur_price,
                'min_bet' => $min_bet,
                'bets' => $bets
            ]);
        }
    }
}

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - просмотр лота ' . $lot['title'],
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'bets' => $bets
]);
print($layout_content);

?>
