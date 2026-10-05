CREATE DATABASE IF NOT EXISTS prince_cards
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE prince_cards;

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO admins (email, password_hash)
VALUES ('ibrabra651@gmail.com', '$2y$12$OksurUunRjk.srg0GLmqPOND.WknqH71YwE4m4vYoPkLo46o8/gci')
ON DUPLICATE KEY UPDATE
    password_hash = VALUES(password_hash),
    updated_at = CURRENT_TIMESTAMP;
