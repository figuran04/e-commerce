<?php
require '../../models/UserModel.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']);

  if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Email dan password wajib diisi!";
    header("Location: ../../views/login");
    exit;
  }

  $userModel = new UserModel();
  $user = $userModel->getByEmail($email);

  if (!$user) {
    $_SESSION['error'] = "Email belum terdaftar.";
    header("Location: ../../views/login");
    exit;
  }

  if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = "Password salah.";
    header("Location: ../../views/login");
    exit;
  }

  // Login sukses
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['user_name'] = htmlspecialchars($user['name']);
  $_SESSION['user_email'] = htmlspecialchars($user['email']);

  if ($user['role'] === 'admin') {
    $_SESSION['is_admin'] = true;
    header("Location: ../../views/admin");
  } else {
    header("Location: ../../views/home");
  }
  exit;
}
