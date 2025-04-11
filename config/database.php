<?php
$BASE = "http://localhost/e-commerce";
$BASE_URL = $BASE . "/views";
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "zerovaa_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
