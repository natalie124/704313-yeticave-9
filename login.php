<?php

require_once('init.php');
require_once('helpers.php');
require_once('functions.php');

$sql_cat = 'SELECT id, name, symbol_code FROM categories'; // получаем все категрии

$categories = get_rows_from_mysql($con, $sql_cat); // преобразуем строки категорий в массив

$nav_content = include_template('nav.php', [
    'categories' => $categories
]); // подключаем меню

$content = include_template('login.php', [
        'nav_content' => $nav_content
]); // подключаем сценарий входа

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // проверяем, что форма была отправлена

    $email = $_POST['email'];
    $password = $_POST['password'];

    $errors = []; // определяем список полей для валидации

    if (empty($email)) {

        $errors['email'] = 'Введите e-mail'; // если поле e-mail пустое, записываем ошибку

    } else {

        $email = mysqli_real_escape_string($con, $_POST['email']); // получаем e-mail из формы
        $sql = "SELECT id, name, email, password FROM users WHERE email = '$email'"; // получаем данные о пользователе из БД
        $res = mysqli_query($con, $sql);

        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null; // если данные получены, преобразуем в массив
        if (!$user) {
            $errors['email'] = 'Такой пользователь не найден'; // если данных нет, записываем ошибку
        }
    }

    if (empty($_POST['password'])) {

        $errors['password'] = 'Введите пароль'; // если поле с паролем пустое, записываем ошибку

    } elseif (!password_verify($password, $user['password'])) { // сравниваем хеш пароля от пользователя с хешем пароля из БД

        $errors['password'] = 'Неверный email или пароль'; // если пароли не совпадают, записываем ошибку
    }


    if (count($errors)) { // если есть ошибки, показываем их в шаблоне

        $content = include_template('login.php', [
            'nav_content' => $nav_content,
            'errors' => $errors
        ]);

    } else { // если ошибок в форме нет, открываем для пользователя сессию

        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit();
    }

} elseif (isset($_SESSION['user'])) {
// если сессия была открыта, отправляем пользователя на главную страницу
       header('Location: index.php');
  }

$layout_content = include_template('layout.php', [
    'page_content' => $content,
    'nav_content' => $nav_content,
    'title' => 'YetiCave - вход на сайт',
    'is_auth' => $is_auth,
    'user_name' =>  $user_name
]);

print($layout_content);
