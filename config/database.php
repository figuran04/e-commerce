<?php
// Base URL — sesuai lokasi proyek di htdocs/5/e-commerce
$BASE     = "http://localhost/5/e-commerce";
$BASE_URL = $BASE . "/views";

// Konfigurasi database
$host    = "localhost";
$user    = "root";
$pass    = "";
$charset = "utf8mb4";

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $conn_auth     = new PDO("mysql:host=$host;dbname=db_auth;charset=$charset", $user, $pass, $options);
  $conn_products = new PDO("mysql:host=$host;dbname=db_products;charset=$charset", $user, $pass, $options);
  $conn_orders   = new PDO("mysql:host=$host;dbname=db_orders;charset=$charset", $user, $pass, $options);
  
  // Default connection for backward compatibility (defaults to auth)
  $conn = $conn_auth;
} catch (\PDOException $e) {
  die("Koneksi database gagal: " . $e->getMessage());
}

