<?php
// scratch/setup_databases_phase3.php

$host = "localhost";
$user = "root";
$pass = "";
$charset = "utf8mb4";

try {
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 1. Create Databases
    $dbs = ['db_auth', 'db_products', 'db_orders'];
    foreach ($dbs as $db) {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        echo "Database `$db` created or already exists.\n";
    }

    // 2. Create tables in db_auth
    $pdo->exec("USE `db_auth`");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `email` varchar(100) NOT NULL,
        `phone` varchar(20) DEFAULT NULL,
        `address` text DEFAULT NULL,
        `bio` text DEFAULT NULL,
        `password` varchar(255) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `role` enum('admin','user') DEFAULT 'user',
        `status` enum('active','blocked') DEFAULT 'active',
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `stores` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(10) UNSIGNED NOT NULL,
        `name` varchar(255) NOT NULL,
        `address` text DEFAULT NULL,
        `description` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        CONSTRAINT `stores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    echo "Tables in `db_auth` created successfully.\n";

    // 3. Create tables in db_products
    $pdo->exec("USE `db_products`");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `categories` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `parent_id` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `fk_categories_parent` (`parent_id`),
        CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text DEFAULT NULL,
        `price` decimal(10,2) NOT NULL,
        `stock` int(11) NOT NULL DEFAULT 0,
        `category_id` int(11) DEFAULT NULL,
        `store_id` int(11) NOT NULL,
        `image` varchar(255) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `user_id` int(10) UNSIGNED NOT NULL,
        `sold_count` int(11) DEFAULT 0,
        PRIMARY KEY (`id`),
        KEY `fk_category` (`category_id`),
        CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    echo "Tables in `db_products` created successfully.\n";

    // 4. Create tables in db_orders
    $pdo->exec("USE `db_orders`");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `carts` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(10) UNSIGNED NOT NULL,
        `product_id` int(11) NOT NULL,
        `quantity` int(11) NOT NULL DEFAULT 1,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `product_id` (`product_id`),
        KEY `carts_ibfk_1` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `orders` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(10) UNSIGNED NOT NULL,
        `store_id` int(11) NOT NULL,
        `total_price` decimal(10,2) NOT NULL,
        `status` enum('Dipesan','Dikirim','Selesai','Dibatalkan','Ditolak') DEFAULT 'Dipesan',
        `order_date` datetime DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `fk_orders_user` (`user_id`),
        KEY `store_id` (`store_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `order_items` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `order_id` int(11) NOT NULL,
        `product_id` int(11) NOT NULL,
        `product_name` varchar(255) DEFAULT NULL,
        `product_image` varchar(255) DEFAULT NULL,
        `quantity` int(11) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        PRIMARY KEY (`id`),
        KEY `order_id` (`order_id`),
        KEY `product_id` (`product_id`),
        CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    echo "Tables in `db_orders` created successfully.\n";

    // 5. Migrate data from zerovaa_db
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (in_array('zerovaa_db', $databases)) {
        echo "Found `zerovaa_db`. Starting data migration...\n";

        // Migrate users
        $pdo->exec("INSERT IGNORE INTO db_auth.users SELECT * FROM zerovaa_db.users");
        $users_count = $pdo->query("SELECT COUNT(*) FROM db_auth.users")->fetchColumn();
        echo "- Migrated users: $users_count rows\n";

        // Migrate stores
        $pdo->exec("INSERT IGNORE INTO db_auth.stores SELECT * FROM zerovaa_db.stores");
        $stores_count = $pdo->query("SELECT COUNT(*) FROM db_auth.stores")->fetchColumn();
        echo "- Migrated stores: $stores_count rows\n";

        // Migrate categories
        $pdo->exec("INSERT IGNORE INTO db_products.categories SELECT * FROM zerovaa_db.categories");
        $categories_count = $pdo->query("SELECT COUNT(*) FROM db_products.categories")->fetchColumn();
        echo "- Migrated categories: $categories_count rows\n";

        // Migrate products
        $pdo->exec("INSERT IGNORE INTO db_products.products SELECT * FROM zerovaa_db.products");
        $products_count = $pdo->query("SELECT COUNT(*) FROM db_products.products")->fetchColumn();
        echo "- Migrated products: $products_count rows\n";

        // Migrate carts
        $pdo->exec("INSERT IGNORE INTO db_orders.carts SELECT * FROM zerovaa_db.carts");
        $carts_count = $pdo->query("SELECT COUNT(*) FROM db_orders.carts")->fetchColumn();
        echo "- Migrated carts: $carts_count rows\n";

        // Migrate orders
        $pdo->exec("INSERT IGNORE INTO db_orders.orders SELECT * FROM zerovaa_db.orders");
        $orders_count = $pdo->query("SELECT COUNT(*) FROM db_orders.orders")->fetchColumn();
        echo "- Migrated orders: $orders_count rows\n";

        // Migrate order_items
        $pdo->exec("INSERT IGNORE INTO db_orders.order_items SELECT * FROM zerovaa_db.order_items");
        $order_items_count = $pdo->query("SELECT COUNT(*) FROM db_orders.order_items")->fetchColumn();
        echo "- Migrated order items: $order_items_count rows\n";

        echo "Migration completed successfully!\n";
    } else {
        echo "No `zerovaa_db` found. Created blank databases.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
