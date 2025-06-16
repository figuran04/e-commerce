<?php
require_once '../../config/init.php';
require_once '../../models/UserModel.php';
require_once '../../views/partials/alerts.php'; // ✅ untuk setFlash

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit;
}

$userModel = new UserModel($conn);

// Ambil ID profil dari URL atau default ke user yang sedang login
$profile_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

// Ambil data user dari database
$user = $userModel->getUserById($profile_id);

if (!$user) {
  setFlash('error', "User tidak ditemukan."); // ✅ gunakan flash
  header("Location: ../home"); // Atau arahkan ke halaman aman lainnya
  exit;
}

$is_own_profile = ($profile_id == $_SESSION['user_id']);
