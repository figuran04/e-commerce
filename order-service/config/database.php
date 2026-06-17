<?php
// Base URL — dapat di-overwrite lewat APP_URL saat deploy di Docker
$BASE     = getenv('APP_URL') ?: 'http://localhost/5/e-commerce';
$BASE_URL = rtrim($BASE, '/') . '/views';

// Konfigurasi database — dapat di-overwrite lewat variabel lingkungan
$host    = getenv('DB_HOST') ?: 'localhost';
$port    = getenv('DB_PORT') ?: '3306';
$user    = getenv('DB_USER') ?: 'root';
$pass    = getenv('DB_PASSWORD') ?: '';
$charset = 'utf8mb4';

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $conn_orders = new PDO("mysql:host=$host;port=$port;dbname=db_orders;charset=$charset", $user, $pass, $options);
  
  // Default connection for backward compatibility (defaults to orders)
  $conn = $conn_orders;
} catch (\PDOException $e) {
  die("Koneksi database gagal: " . $e->getMessage());
}

