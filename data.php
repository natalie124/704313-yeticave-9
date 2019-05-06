<?php
$is_auth = rand(0, 1);

$user_name = 'natalie'; // укажите здесь ваше имя

$sql_cat = "SELECT id, name, symbol_code FROM categories"; // получаем все категрии

$sql_lots = "SELECT l.title AS name, l.price AS start_price, l.img_path, c.name AS category FROM lots AS l
            LEFT JOIN categories AS c ON l.cat_id = c.id
            WHERE NOW() < l.dt_end AND l.win_id IS NULL
            ORDER BY l.dt_add DESC
            LIMIT 9"; // получаем самые новые открытые лоты (каждый лот включает название, стартовую цену, ссылку на изображение, название категории)

$categories = [
    "boards" => "Доски и лыжи",
    "attachment" => "Крепления",
    "boots" => "Ботинки",
    "clothing" => "Одежда",
    "tools" => "Инструменты",
    "other" => "Разное"
];

$lots = [
    [
        "name" => "2014 Rossignol District Snowboard",
        "category" => $categories["boards"],
        "price" => 10999,
        "path" => "img/lot-1.jpg"
    ],[
        "name" => "DC Ply Mens 2016/2017 Snowboard",
        "category" => $categories["boards"],
        "price" => 159999,
        "path" => "img/lot-2.jpg"
    ],[
        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "category" => $categories["attachment"],
        "price" => 8000,
        "path" => "img/lot-3.jpg"
    ],[
        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
        "category" => $categories["boots"],
        "price" => 10999,
        "path" => "img/lot-4.jpg"
    ],[
        "name" => "Куртка для сноуборда DC Mutiny Charocal",
        "category" => $categories["clothing"],
        "price" =>7500,
        "path" => "img/lot-5.jpg"
    ],[
        "name" => "Маска Oakley Canopy",
        "category" => $categories["other"],
        "price" => 5400,
        "path" => "img/lot-6.jpg"
    ]
];
