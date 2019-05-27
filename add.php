<?php

require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    die('У Вас нет прав для просмотра этой страницы' . '<br>' . '<a href="login.php">Вход</a>' . '<br>' . '<a href="sign-up.php">Регистрация</a>' . '<br>' . '<a href="index.php">На главную</a>');
}

$sql_cat = 'SELECT id, name, symbol_code FROM categories'; // получаем все категрии

$categories = get_rows_from_mysql($con, $sql_cat); // преобразуем строки категорий в массив

$nav_content = include_template('nav.php', [
    'categories' => $categories
]); // подключаем меню

$content = include_template('add.php', [
    'nav_content' => $nav_content,
    'categories' => $categories
]); // подключаем сценарий добавления лота

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //проверяем, что форма была отправлена

    $title = $_POST['lot-name'] ?? '';
    $description = $_POST['message'] ?? '';
    $price = isset($_POST['lot-rate']) ? ceil($_POST['lot-rate']) : '';
    $dt_end = $_POST['lot-date'] ?? '';
    $bet_step = isset($_POST['lot-step']) ? ceil($_POST['lot-step']) : '';
    $cat_id = isset($_POST['category']) ? (int)$_POST['category'] : '';
    $user_id = $cur_user_id;

    $required = [
        'lot-name',
        'message',
        'lot-rate',
        'lot-date',
        'lot-step',
        'category'
    ]; // определяем список полей для валидации

    $errors = []; // определяем пустой массив, который будем заполнять ошибками валидации

    foreach ($required as $key) { // проверяем пустые поля
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить'; // если есть пустое поле, записываем ошибку
        }
    }

    if (!empty($_POST['category']) && ($cat_id > count($categories) || $cat_id < 1 || !is_numeric($_POST['category']))) {
        $errors['category'] = 'Нет такой категории';
    }

    if (!empty($dt_end) && (strtotime($dt_end) < (time() + 86400) || !is_date_valid($dt_end))) {
        $errors['lot-date'] = 'Введите дату завершения торгов';
    }

    if (!empty($_FILES['lot_img']['name'])) { // проверяем был ли загружен файл

        $filename = $_FILES['lot_img']['tmp_name']; // получаем имя загруженного файла
        $file_type = mime_content_type($filename); // получаем тип загруженного файла

        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') { // проверяем соответствие типа файла требуемому

            $errors['lot_img'] = 'Загрузите изображение в формате: jpg, jpeg, png'; // если тип файла не подходит, записываем ошибку

        } else {

            $filename = uniqid() . ($file_type === 'image/png' ? '.png' : '.jpg'); // меняем имя файла на новое уникальное
            $img_path = 'uploads/' . $filename; // определянм путь к файлу
            move_uploaded_file($_FILES['lot_img']['tmp_name'],
                $img_path); // перемещаем файл из временной папки в постоянную
        }
    } else {

        $errors['lot_img'] = 'Загрузите изображение в формате: jpg, jpeg, png'; // если файл не был загружен, записываем ошибку
    }

    if (count($errors)) { // если в массиве есть ошибки, показываем их в шаблоне с формой
        $content = include_template('add.php', [
            'nav_content' => $nav_content,
            'categories' => $categories,
            'errors' => $errors
        ]);

    } else { // если ошибок нет, записываем новый лот

        $sql = 'INSERT INTO lots (title, description, img_path, price, dt_end, bet_step, user_id, cat_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'; // формируем запрос на добавление

        $stmt = db_get_prepare_stmt($con, $sql, [
            $title,
            $description,
            $img_path,
            $price,
            $dt_end,
            $bet_step,
            $user_id,
            $cat_id
        ]); // формируем подготовленное выражение, на основе SQL-запроса и значений для него

        $res = mysqli_stmt_execute($stmt); // выполняем полученное выражение

        if ($res) { // если запрос выполнен успешно, то получаем ID нового лота и перенаправляем пользователя на страницу с его просмотром
            $lot_id = mysqli_insert_id($con);
            header('Location: lot.php?lot_id= ' . (int)$lot_id);
        }
    }
}

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - добавить лот',
    'is_flatpickr' => true,
    'is_auth' => $is_auth,
    'user_name' => $user_name
]);

print($layout_content);
