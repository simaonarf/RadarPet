SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    encrypted_password VARCHAR(255) NOT NULL
);

SET foreign_key_checks = 1;