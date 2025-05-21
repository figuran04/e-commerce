<?php
require '../../models/UserModel.php';  // Mengimpor model User

// Mengecek apakah request menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Mengambil data dari form
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  // Validasi data (misal, cek email yang sudah terdaftar)
  $userModel = new UserModel();
  if ($userModel->isEmailExist($email)) {
    $_SESSION['error'] = "Email sudah terdaftar.";
    header("Location: ../../views/register");
    exit;
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  // Menyimpan pengguna baru ke database
  if ($userModel->register($name, $email, $hashedPassword)) {
    $_SESSION['success'] = "Akun berhasil dibuat! Silakan masuk.";
    header("Location: ../../views/login");
    exit;
  } else {
    $_SESSION['error'] = "Gagal mendaftar, coba lagi.";
    header("Location: ../../views/register");
    exit;
  }
}
