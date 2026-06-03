<?php
// Base URL — sesuai lokasi proyek di htdocs/5/e-commerce
$BASE     = "http://localhost/5/e-commerce";
$BASE_URL = $BASE . "/views";

// Konfigurasi database
$host    = "localhost";
$user    = "root";
$pass    = "";
$dbname  = "zerovaa_db";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  die("Koneksi database gagal: " . $e->getMessage());
}
