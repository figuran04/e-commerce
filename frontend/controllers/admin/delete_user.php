<?php
require_once __DIR__ . '/../../config/init.php';
require_once '../../models/UserModel.php';
require_once __DIR__ . '/../../helpers/flash.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login/");
  exit;
}

if (isset($_GET['id'])) {
  $user_id = intval($_GET['id']);
  $userModel = new UserModel();

  if ($userModel->delete($user_id)) {
    setFlash('success', "Pengguna berhasil dihapus!");
  } else {
    setFlash('error', "Terjadi kesalahan saat menghapus pengguna!");
  }

  header("Location: ../../views/admin/?tab=users");
  exit;
}
