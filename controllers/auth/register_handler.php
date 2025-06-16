<?php
session_start(); // Pastikan session dimulai
require '../../models/UserModel.php';
require '../../views/partials/alerts.php'; // Tambahkan untuk akses setFlash()

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $userModel = new UserModel();

  if ($userModel->isEmailExist($email)) {
    setFlash('error', "Email sudah terdaftar.");
    header("Location: ../../views/register");
    exit;
  }

  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  if ($userModel->register($name, $email, $hashedPassword)) {
    // Ambil data user yang baru saja diregistrasi untuk login
    $user = $userModel->getByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
      // Set session login
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_email'] = $user['email'];

      // Redirect ke halaman dashboard atau home
      header("Location: ../../views/home");
      exit;
    } else {
      setFlash('error', "Gagal login setelah register.");
      header("Location: ../../views/login");
      exit;
    }
  } else {
    setFlash('error', "Gagal mendaftar, coba lagi.");
    header("Location: ../../views/register");
    exit;
  }
}
