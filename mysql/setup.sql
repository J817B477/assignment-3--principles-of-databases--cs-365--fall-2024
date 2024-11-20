\W
DROP DATABASE IF EXISTS student_passwords;

CREATE DATABASE student_passwords DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

USE student_passwords;

DROP USER IF EXISTS 'passwords_user'@'localhost';

CREATE USER 'passwords_user'@'localhost';
GRANT ALL ON student_passwords.* TO 'passwords_user'@'localhost';


-- generates arguments for AES_Encrypt()
SET @block_encryption_mode = 'aes-256-cbc';
-- SET @key = 'encrypt this';
-- SET @vec = 'and this';
SET @key_str = "JS04*KR_NTO{1112";
SET @init_vector = "LKJSE29448NDJSSK";

-- creates encryption credentials table
DROP TABLE IF EXISTS credentials;
CREATE TABLE credentials (
  key_id SMALLINT AUTO_INCREMENT PRIMARY KEY,
  enc_mode VARCHAR(257) NOT NULL,
  key_st VARCHAR(257) NOT NULL,
  vec_st VARBINARY(257) NOT NULL
);

-- populates credentials table with AES arguments
INSERT INTO credentials
  (enc_mode,key_st,
  vec_st)
VALUES
  (@block_encryption_mode,@key, @init_vector);



-- creates 'student_passwords' relations
CREATE TABLE IF NOT EXISTS user (
  user_id SMALLINT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email_address VARCHAR(65) NOT NULL
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS website (
  site_id SMALLINT AUTO_INCREMENT PRIMARY KEY,
  site_name VARCHAR(100) NOT NULL,
  url VARCHAR(256) NOT NULL
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS registry(
  registry_id SMALLINT AUTO_INCREMENT PRIMARY KEY,
  user_id SMALLINT NOT NULL,
  site_id SMALLINT NOT NULL,
  encrypted_password VARBINARY(256) NOT NULL,
  user_name VARCHAR(65) NOT NULL,
  timestamp TIMESTAMP(3)
    DEFAULT CURRENT_TIMESTAMP(3)
    ON UPDATE CURRENT_TIMESTAMP(3),
  comment VARCHAR(1000),
  FOREIGN KEY (user_id)
    REFERENCES user (user_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  FOREIGN KEY (site_id)
    REFERENCES website (site_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
)ENGINE=INNODB;

-- initial population of 'student_passwords' relations
INSERT INTO user
  (first_name, last_name, email_address)
  VALUES
  ('John', 'Bennett', 'jb@gmail.com'),
  ('Ian','Malcolm', 'ian.malcolm@outlook.com'),
  ('John', 'Bennett', 'johnbennett@hartford.edu'),
  ('Grant', 'Sanderson', 'g.sanderson@3blue1brown.com'),
  ('Josh', 'Johnson', 'jj@joshjohnsoncomedy.com');

-- Inserts all unique websites for 1st 10 password entries
INSERT INTO website
  (site_name, url)
  VALUES
  ('Amazon', 'https://www.amazon.com'),
  ('GitHub', 'https://github.com'),
  ('Premium Oil', 'https://www.ctpremiumoil.com'),
  ('Premium Oil', 'https://www.nypremiumoil.com'),
  ('Spotify', 'https://accounts.spotify.com/'),
  ('LinkedIn', 'https://www.linkedin.com/'),
  ('New York Times', 'https://www.nytimes.com/');

-- Inserts 1st 10 password entries
 INSERT INTO registry
    (user_id, site_id, encrypted_password, user_name)
    VALUES
    (1, 2, AES_ENCRYPT('sticky putty', @key_str, @init_vector), 'J817B477'),
    (1, 1, AES_ENCRYPT('kerosene heater', @key_str, @init_vector), 'John Bennett'),
    (2, 4, AES_ENCRYPT('chaos theory', @key_str, @init_vector),'Malcolm.I'),
    (3, 3, AES_ENCRYPT('sad dinosaur', @key_str, @init_vector), 'John Bennett'),
    (3, 5, AES_ENCRYPT('rebel music', @key_str, @init_vector), 'J477'),
    (4, 2, AES_ENCRYPT('math in motion', @key_str, @init_vector), '3b1b'),
    (4, 5, AES_ENCRYPT('math is music', @key_str, @init_vector), '3b1b'),
    (4, 6, AES_ENCRYPT('free conferences', @key_str, @init_vector), 'Grant Sanderson'),
    (5, 7, AES_ENCRYPT('not funny material', @key_str, @init_vector), 'J.Johnson'),
    (5, 6, AES_ENCRYPT('comedy business', @key_str, @init_vector), 'Josh Johnson Comedy');

-- decryption procedures
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS sp_create_search_table()
BEGIN

  CREATE TABLE IF NOT EXISTS decrypted_registry (
  registry_id SMALLINT,
  user_id SMALLINT NOT NULL,
  site_id SMALLINT NOT NULL,
  decrypted_password VARCHAR(256),
  user_name VARCHAR(65) NOT NULL,
  timestamp TIMESTAMP(3),
  comment VARCHAR(1000)
  ) ENGINE=INNODB;


  INSERT INTO decrypted_registry (registry_id, user_id, site_id,decrypted_password, user_name, timestamp, comment)
  SELECT
    registry_id,
    user_id,
    site_id,
    CAST(AES_DECRYPT(encrypted_password, @key_str, @init_vector) AS CHAR),
    user_name,
    timestamp,
    comment
  FROM registry;
END$$

CREATE PROCEDURE IF NOT EXISTS sp_delete_search_table()
BEGIN
DROP TABLE decrypted_registry;
END$$

DELIMITER ;
