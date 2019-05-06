<?php

require_once('helpers.php');
require_once('data.php');
require_once('functions.php');
require_once('init.php');

$rows_cat = get_rows_from_mysql($con, $sql_cat);
$rows_lots = get_rows_from_mysql($con, $sql_lots);

$content = include_template('index.php', [
    'rows_lots' => $rows_lots,
    'rows_cat' => $rows_cat
]);

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'title' => 'YetiCave - Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'rows_cat' => $rows_cat
]);

print($layout_content);

?>
