<?php
date_default_timezone_set("Europe/Moscow");

$host = 'localhost'; // адрес сервера
$database = 'yeticave'; // имя базы данных
$user = 'natalie'; // имя пользователя
$password = '10000001'; // пароль

$con = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_connect_error($con));

mysqli_set_charset($con, "utf8");
