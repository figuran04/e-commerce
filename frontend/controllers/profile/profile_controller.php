<?php
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../helpers/flash.php';
require_once __DIR__ . '/../../helpers/tampil_data.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit;
}

$userModel = new UserModel();

// Ambil ID profil dari URL atau default ke user yang sedang login
$profile_id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_SESSION['user_id'];

// Ambil data user dari database
$user = $userModel->getUserById($profile_id);

if (!$user) {
  setFlash('error', "User tidak ditemukan.");
  header("Location: ../home");
  exit;
}

$is_own_profile = ($profile_id == $_SESSION['user_id']);
