CREATE DATABASE IF NOT EXISTS db_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE DATABASE IF NOT EXISTS db_products CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE DATABASE IF NOT EXISTS db_orders CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE DATABASE IF NOT EXISTS zerovaa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE USER IF NOT EXISTS 'appuser'@'%' IDENTIFIED BY 'apppass';
GRANT ALL PRIVILEGES ON db_auth.* TO 'appuser'@'%';
GRANT ALL PRIVILEGES ON db_products.* TO 'appuser'@'%';
GRANT ALL PRIVILEGES ON db_orders.* TO 'appuser'@'%';
GRANT ALL PRIVILEGES ON zerovaa_db.* TO 'appuser'@'%';
FLUSH PRIVILEGES;
