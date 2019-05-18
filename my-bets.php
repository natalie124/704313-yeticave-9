<?php

require_once('init.php');
require_once('data.php');
require_once('helpers.php');
require_once('functions.php');

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии
$sql_bets = "SELECT l.id AS lot_id, l.win_id, l.img_path, l.title, c.name AS category, l.dt_end, b.bet_price, b.user_id AS bet_user_id, b.dt_add, u.contact FROM bets AS b
             JOIN lots AS l ON b.lot_id = l.id
             JOIN users AS u ON l.user_id = u.id
             JOIN categories AS c ON l.cat_id = c.id
             WHERE b.user_id = '$cur_user_id' GROUP BY b.id ORDER BY b.dt_add DESC;";
$categories = get_rows_from_mysql($con, $sql_cat);
$bets = get_rows_from_mysql($con, $sql_bets);

$item_end = false;
$item_win = false;

if (isset($bet['win_id']) && $bet['win_id'] === $cur_user_id) {
    $item_win = true;
}
if (isset($bet['win_id']) && $bet['win_id'] !== $cur_user_id && (count_time($bet['dt_end']) <= 0)) {
    $item_end = true;
}

$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$content = include_template('my-bets.php', [
    'nav_content' => $nav_content,
    'bets' => $bets,
    'item_end' => $item_end,
    'item_win' => $item_win
]);

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - Moи ставки',
    'is_auth' => $is_auth,
    'user_name' =>  $user_name
]);

print($layout_content);

?>
