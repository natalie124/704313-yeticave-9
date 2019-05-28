<?php

session_start();

date_default_timezone_set('Europe/Moscow');

$host = 'localhost'; // адрес сервера
$database = 'yeticave'; // имя базы данных
$user = 'natalie'; // имя пользователя
$password = '10000001'; // пароль

$con = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_connect_error($con));

mysqli_set_charset($con, 'utf8');

$is_auth = isset($_SESSION['user']) ? true : false;
$user_name = $is_auth ? strip_tags($_SESSION['user']['name']) : false;
$cur_user_id = $is_auth ? (int)$_SESSION['user']['id'] : false;

$limit = 9; //количество элементов, размещенных на одной странице
$cur_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // получаем номер текущей страницы, по умолчанию 1
