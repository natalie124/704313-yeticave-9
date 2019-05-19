DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(128) NOT NULL UNIQUE,
  symbol_code CHAR(64) NOT NULL UNIQUE
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title CHAR(128) NOT NULL,
  description TEXT,
  img_path CHAR(64),
  price INT NOT NULL,
  dt_end TIMESTAMP,
  bet_step INT,
  user_id INT,
  win_id INT,
  cat_id INT
);

CREATE INDEX i_l_title ON lots(title);
CREATE INDEX i_l_img_path ON lots(img_path);
CREATE INDEX  i_l_price ON lots(price);
CREATE INDEX  i_l_dt_end ON lots(dt_end);
CREATE INDEX  i_l_bet_step ON lots(bet_step);
CREATE FULLTEXT INDEX i_l_search ON lots(title, description);


CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  bet_price INT NOT NULL,
  user_id INT,
  lot_id INT
);

CREATE INDEX i_b_dt_add ON bets(dt_add);
CREATE INDEX  i_b_bet_price ON bets(bet_price);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(128) NOT NULL,
  password CHAR(64) NOT NULL,
  avatar_path CHAR(64),
  contact CHAR(128) NOT NULL
);

CREATE INDEX  i_u_name ON users(name);
CREATE INDEX  i_u_password ON users(password);
CREATE INDEX  i_u_contact ON users(contact);
CREATE UNIQUE INDEX i_u_email ON users(email);
