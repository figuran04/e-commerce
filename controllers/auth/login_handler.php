<?php
session_start();
require '../../models/UserModel.php';
require '../../models/StoreModel.php';
require '../../views/partials/alerts.php'; // untuk akses setFlash()

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
  $password = trim($_POST['password']);

  if (empty($email) || empty($password)) {
    setFlash('error', "Email dan password wajib diisi!");
    header("Location: ../../views/login");
    exit;
  }

  $userModel = new UserModel();
  $user = $userModel->getByEmail($email);

  if (!$user) {
    setFlash('error', "Email belum terdaftar.");
    header("Location: ../../views/login");
    exit;
  }

  if (!password_verify($password, $user['password'])) {
    setFlash('error', "Password salah.");
    header("Location: ../../views/login");
    exit;
  }

  $storeModel = new StoreModel($conn);
  $store = $storeModel->getStoreByUserId($user['id']);

  if ($store) {
    $_SESSION['store_id'] = $store['id'];
  } else {
    unset($_SESSION['store_id']);
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
