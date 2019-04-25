<?php

require_once('helpers.php');
require_once('data.php');
require_once('functions.php');

$content = include_template('index.php', [
    'lots' => $lots,
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'title' => 'YetiCave - Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories
]);

print($layout_content);

?>
