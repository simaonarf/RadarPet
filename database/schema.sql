CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name,email,password_hash,role) VALUES
('Admin Demo','admin@demo.com','$2y$12$T6GN8tPghB0kKUDeZ9ugwuymn/qRLrbQ5Z7JPcSKFBe5k7knzSvV2','admin'),
('Usuário Demo','user@demo.com','$2y$12$20lAQ7dtQ8KHCE2v13vjr.IYotH5Ax6Odh06imUT8cZA/RBgI2L8K','user');
