SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  encrypted_password VARCHAR(255) NOT NULL,
  type ENUM('tutor','encontrador') DEFAULT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  register_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SET foreign_key_checks = 1;