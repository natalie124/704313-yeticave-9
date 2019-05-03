-- добавляем записи в таблицу categories

INSERT INTO categories
(name, symbol_code) VALUES
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки', 'boots'),
('Одежда', 'clothing'),
('Инструменты', 'tools'),
('Разное', 'other');

-- добавляем записи в таблицу users

INSERT INTO users
(dt_add, email, name, password, avatar_path, contact) VALUES
(CURRENT_TIMESTAMP(), 'user1@mail.ru', 'user1', MD5('password1'), '/img/avatar-1.jpg', 'тел.: 11-11-11'),
(CURRENT_TIMESTAMP(), 'user2@mail.ru', 'user2', MD5('password2'), '/img/avatar-2.jpg', 'тел.: 22-22-22'),
(CURRENT_TIMESTAMP(), 'user3@mail.ru', 'user3', MD5('password3'), '/img/avatar-3.jpg', 'тел.: 33-33-33');

-- добавляем записи в таблицу lots

INSERT INTO lots
(dt_add, title, description, img_path, price, dt_end, bet_step, user_id, cat_id) VALUES
(CURRENT_TIMESTAMP(), '2014 Rossignol District Snowboard', '2014 Rossignol District Snowboard', 'img/lot-1.jpg', 10999, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY), 1099, 1, 1),
(CURRENT_TIMESTAMP(), 'DC Ply Mens 2016/2017 Snowboard', 'DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 159999, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY), 15999, 2, 1),
(CURRENT_TIMESTAMP(), 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления Union Contact Pro 2015 года размер L/XL','img/lot-3.jpg', 8000, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY), 800, 2, 2),
(CURRENT_TIMESTAMP(), 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 10999, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY), 1099, 2, 3),
(CURRENT_TIMESTAMP(), 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 7500, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY), 750, 1, 4),
(CURRENT_TIMESTAMP(), 'Маска Oakley Canopy', 'Маска Oakley Canopy', 'img/lot-6.jpg', 5400, DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 DAY), 540, 1, 6);

-- добавляем записи в таблицу bets

INSERT INTO bets
(dt_add, bet_price, user_id, lot_id) VALUES
(CURRENT_TIMESTAMP(), 12098, 2, 1),
(CURRENT_TIMESTAMP(), 13197, 3, 1),
(CURRENT_TIMESTAMP(), 14296, 2, 1),
(CURRENT_TIMESTAMP(), 15395, 3, 1);

-- получаем все категории

SELECT id, name, symbol_code FROM categories;

-- получаем самые новые открытые лоты (каждый лот включает название, стартовую цену, ссылку на изображение, цену, название категории)

SELECT l.title AS name, l.price AS start_price, l.img_path, MAX(b.bet_price) AS price, c.name AS category FROM lots AS l
LEFT JOIN bets AS b ON b.lot_id = l.id
LEFT JOIN categories AS c ON l.cat_id = c.id
WHERE NOW() < l.dt_end AND l.win_id IS NULL
GROUP BY l.id
ORDER BY l.dt_add DESC
LIMIT 6;

-- показываем лот по его id. Получаем также название категории, к которой принадлежит лот

SELECT l.*, c.name FROM lots AS l
LEFT JOIN categories AS c ON l.cat_id = c.id
WHERE l.id = 1;

-- обновляем название лота по его идентификатору

UPDATE lots SET title = '2016 Rossignol District Snowboard'
WHERE id = 1;

-- получаем список самых свежих ставок для лота по его идентификатору

SELECT l.*, b.bet_price FROM lots AS l
LEFT JOIN bets AS b ON b.lot_id = l.id
WHERE l.id = 1
ORDER BY b.bet_price DESC
LIMIT 3;
