<?php

require_once('init.php');
require_once('data.php');
require_once('helpers.php');
require_once('functions.php');

if (isset($_SESSION['user'])) {
// если сессия была открыта, отправляем пользователя на главную страницу
    header("Location: index.php");
}

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии

$categories = get_rows_from_mysql($con, $sql_cat); // преобразуем строки категорий в массив

$nav_content = include_template('nav.php', [
    'categories' => $categories
]); // подключаем меню

$content = include_template('sign-up.php', [
        'nav_content' => $nav_content,
        'form_invalid' => '',
        'field_invalid' => ''
]); // подключаем сценарий регистрации аккаунта


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $contact = $_POST['message'];

    $required = ['password', 'name', 'message']; // определяем список полей для валидации
    $errors = [];
    $errors_class = 'form__item--invalid'; // определяем пустой массив, который будем заполнять ошибками валидации

    foreach ($required as $key) { // проверяем пустые поля
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить'; // если есть пустое поле, записываем ошибку
        }
    }

    if (empty($errors)) { // если ошибок нет, проверяем e-mail

        if (empty($email)) {
            $errors['email'] = 'Введите e-mail'; // если e-mail пустой, записываем ошибку
        } else { // проверяем, есть ли в БД такой e-mail
            $email = mysqli_real_escape_string($con, $email);
            $sql = "SELECT id FROM users WHERE email = '$email'";
            $res = mysqli_query($con, $sql);

            if (mysqli_num_rows($res) > 0) {
                $errors['email'] = 'Пользователь с этим email уже зарегистрирован'; // если такой e-mail в БД уже есть, записываем ошибку
            }
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false ) { // проверяем соответствие e-mail требуемому формату
            $errors['email'] = 'Введите корректный e-mail'; // если формат не подходит, записываем ошибку
        }
    }


    if (count($errors)) { // если в массиве есть ошибки, показываем их в шаблоне с формой
        $content = include_template('sign-up.php', [
            'nav_content' => $nav_content,
            'form_invalid' => 'form--invalid',
            'field_invalid' => $errors_class,
            'errors' => $errors
        ]);

    } else { // если ошибок нет, записываем данные о новом пользователе

        $sql = 'INSERT INTO users (email, password, name, contact) VALUES (?, ?, ?, ?)'; // формируем запрос на добавление

        $stmt = db_get_prepare_stmt($con, $sql, [$email, $password, $name, $contact]); // формируем подготовленное выражение, на основе SQL-запроса и значений для него

        $res = mysqli_stmt_execute($stmt); // выполняем полученное выражение

        if($res) { // если запрос выполнен успешно, то перенаправляем пользователя на страницу входа
            header("Location: login.php");
        }
    }
}

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - регистрация аккаунта'
]);

print($layout_content);
