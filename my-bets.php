<?php

require_once('init.php');
require_once('data.php');
require_once('helpers.php');
require_once('functions.php');

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии
$categories = get_rows_from_mysql($con, $sql_cat);



$nav_content = include_template('nav.php', [
    'categories' => $categories
]);

$content = include_template('my-bets.php', [
    'nav_content' => $nav_content
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
