CREATE DATABASE IF NOT EXISTS db_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE DATABASE IF NOT EXISTS db_products CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE DATABASE IF NOT EXISTS db_orders CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE DATABASE IF NOT EXISTS zerovaa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE USER IF NOT EXISTS 'appuser'@'%' IDENTIFIED BY 'apppass';
GRANT ALL PRIVILEGES ON db_auth.* TO 'appuser'@'%';
GRANT ALL PRIVILEGES ON db_products.* TO 'appuser'@'%';
GRANT ALL PRIVILEGES ON db_orders.* TO 'appuser'@'%';
GRANT ALL PRIVILEGES ON zerovaa_db.* TO 'appuser'@'%';

-- Isolated user for Auth service
CREATE USER IF NOT EXISTS 'authuser'@'%' IDENTIFIED BY 'authpass';
GRANT ALL PRIVILEGES ON db_auth.* TO 'authuser'@'%';

-- Isolated user for Product service
CREATE USER IF NOT EXISTS 'productuser'@'%' IDENTIFIED BY 'productpass';
GRANT ALL PRIVILEGES ON db_products.* TO 'productuser'@'%';

-- Isolated user for Order service
CREATE USER IF NOT EXISTS 'orderuser'@'%' IDENTIFIED BY 'orderpass';
GRANT ALL PRIVILEGES ON db_orders.* TO 'orderuser'@'%';

FLUSH PRIVILEGES;

