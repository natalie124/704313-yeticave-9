<?php

require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$lot_id = (int)$_GET["lot_id"] ?? 0; //получаем id текущего лота если он есть

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии из БД
$sql_lot = "SELECT l.*, c.name, b.bet_price, b.user_id AS bet_id FROM lots AS l
            LEFT JOIN categories AS c ON l.cat_id = c.id
            LEFT JOIN bets AS b ON b.lot_id = '$lot_id'
            WHERE l.id = '$lot_id'
            ORDER BY b.bet_price DESC"; // получаем лот из БД по id
$sql_bets = "SELECT b.id, b.dt_add, b.bet_price, b.lot_id, u.id AS user_id, u.name FROM bets AS b
            LEFT JOIN users AS u ON u.id = b.user_id
            WHERE b.lot_id = '$lot_id'
            ORDER BY b.bet_price
            DESC LIMIT 10"; // получаем все ставки к лоту

$categories = get_rows_from_mysql($con, $sql_cat); // преобразуем строки категорий в массив
$lot = get_row_from_mysql($con, $sql_lot); // преобразуем строку лота в массив
$bets = get_rows_from_mysql($con, $sql_bets); // преобразуем ставки в массив

$cur_price = $bets[0]['bet_price'] ?? $lot['price']; // текущая цена лота равна последней ставке либо стартовой цене
$min_bet = $cur_price + $lot['bet_step']; // минимальная ставка равна текущей цене лота плюс шаг ставки
$cur_user_bet = $lot['bet_id'] !== $cur_user_id ? false : true; // если последнюю ставку сделал текущий пользователь, переменная вернет true (нужна для скрытия формы в шаблоне)

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
        'cur_price' => $cur_price,
        'min_bet' => $min_bet,
        'bets' => $bets,
        'cur_user_id' => $cur_user_id,
        'cur_user_bet' => $cur_user_bet
    ]);
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // проверяем, была ли форма

    $errors = []; // создаем пустой массив, в который будем записывать ошибки валидации формы
    $errors_class = 'form__item--invalid'; // добавляем этот класс к форме, если есть ошибки
    $bet = mysqli_real_escape_string($con, $_POST['cost']); // получаем значание из формы

    if (empty($bet)) {

        $errors['cost'] = 'введите сумму ставки'; // если поле пустое - записываем ошибку

    } elseif (!is_numeric($bet)) {

        $errors['cost'] = 'ставка должна быть числом'; // если введено не число - записываем ошибку

    } elseif ($bet < $min_bet) {

        $errors['cost'] = 'минимальная ставка: ' . $min_bet; // если ставка меньше минимальной - записываем ошибку
    }

    if (count($errors)) { // если есть ошибки, показываем шаблон с ошибками

        $content = include_template('lot.php', [
            'lot' => $lot,
            'nav_content' => $nav_content,
            'is_auth' => $is_auth,
            'cur_price' => $cur_price,
            'min_bet' => $min_bet,
            'errors' => $errors,
            'bets' => $bets,
            'cur_user_id' => $cur_user_id,
            'cur_user_bet' => $cur_user_bet
        ]);
    } else {

        $sql = 'INSERT INTO bets (bet_price, user_id, lot_id) VALUES (?, ?, ?)'; // формируем запрос на добавление

        $stmt = db_get_prepare_stmt($con, $sql, [
            $bet,
            $cur_user_id,
            $lot_id
        ]); // формируем подготовленное выражение, на основе SQL-запроса и значений для него

        $res = mysqli_stmt_execute($stmt); // выполняем полученное выражение

        if ($res) { // если запрос выполнен успешно, то добавляем новую ставку в БД

            $sql_bets = "SELECT b.id, b.dt_add, b.bet_price, u.name, u.id  AS user_id FROM bets AS b
            LEFT JOIN users AS u ON u.id = b.user_id
            WHERE b.lot_id = '$lot_id'
            ORDER BY b.bet_price DESC LIMIT 10"; // обновляем данные о ставках

            $bets = get_rows_from_mysql($con, $sql_bets); // преобразуем новые данные в массив
            $cur_price = $bets[0]['bet_price'] ?? $lot['price']; // обновляем данные о текущей цене лота
            $min_bet = $cur_price + $lot['bet_step']; // обновляем данные о минимальной ставке для лота
            $cur_user_bet = $bets[0]['user_id'] !== $cur_user_id ? false : true; // обновляем данные о том была ли последняя ставка сделана текущим пользователем

            $content = include_template('lot.php', [ // показываем шаблон с новыми данными
                'lot' => $lot,
                'nav_content' => $nav_content,
                'is_auth' => $is_auth,
                'cur_price' => $cur_price,
                'min_bet' => $min_bet,
                'bets' => $bets,
                'cur_user_id' => $cur_user_id,
                'cur_user_bet' => $cur_user_bet
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
