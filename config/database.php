<?php
$BASE = "http://localhost/e-commerce";
$BASE_URL = $BASE . "/views";

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "zerovaa_db";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Koneksi awal (tanpa DB) untuk cek dan buat DB jika belum ada
// $conn = mysqli_connect($host, $user, $pass, $dbname);
// if (!$conn) {
//   die("Koneksi gagal: " . mysqli_connect_error());
// }
// try {
//   $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
//   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//   die("Database connection failed: " . $e->getMessage());
// }
