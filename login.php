<?php

require_once('helpers.php');
require_once('data.php');
require_once('functions.php');
require_once('init.php');

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии

$categories = get_rows_from_mysql($con, $sql_cat); // преобразуем строки категорий в массив

$nav_content = include_template('nav.php', [
    'categories' => $categories
]); // подключаем меню

$content = include_template('login.php', [
        'nav_content' => $nav_content
]); // подключаем сценарий входа

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - вход на сайт',
    'is_auth' => false,
    'user_name' => $user_name,
    'container' => '',
    'flatpickr_css' => ''
]);

print($layout_content);
