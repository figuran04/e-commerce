<?php
$BASE_URL = "http://localhost/zerovaa-native/views";
$BASE = "http://localhost/zerovaa-native";
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "zerovaa_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}


// <?php
// $BASE_URL = "https://zerovaa.wuaze.com/views";
// $BASE = "https://zerovaa.wuaze.com";
// $host = "sql309.infinityfree.com";
// $user = "if0_38636903";
// $pass = "TN9vayHML06LKO2";
// $dbname = "if0_38636903_zerovaa_db";

// $conn = mysqli_connect($host, $user, $pass, $dbname);

// if (!$conn) {
//   die("Koneksi gagal: " . mysqli_connect_error());
// }
