USE db_products;

CREATE TABLE IF NOT EXISTS categories (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  parent_id INT DEFAULT NULL,
  PRIMARY KEY (id),
  KEY fk_categories_parent (parent_id),
  CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS products (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  price DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  category_id INT DEFAULT NULL,
  store_id INT NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  user_id INT UNSIGNED NOT NULL,
  sold_count INT DEFAULT 0,
  PRIMARY KEY (id),
  KEY fk_category (category_id),
  CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO categories (id, name, parent_id) VALUES
  (1, 'Umum', NULL),
  (2, 'Rumah Tangga', NULL),
  (3, 'Dekorasi', 2),
  (4, 'Kecantikan', NULL)
ON DUPLICATE KEY UPDATE name=VALUES(name), parent_id=VALUES(parent_id);

INSERT INTO products (id, name, description, price, stock, category_id, store_id, image, user_id, sold_count) VALUES
  (1, 'Botol Minum Ramah Lingkungan', 'Botol reusable untuk aktivitas harian.', 45000, 20, 1, 1, 'sample-bottle.png', 1, 12),
  (2, 'Lampu LED Hemat Energi', 'Lampu LED untuk rumah modern.', 120000, 10, 3, 1, 'sample-lamp.png', 1, 8)
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), price=VALUES(price), stock=VALUES(stock), category_id=VALUES(category_id), store_id=VALUES(store_id), image=VALUES(image), user_id=VALUES(user_id), sold_count=VALUES(sold_count);
