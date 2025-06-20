<?php
require_once '../../config/init.php';
require_once '../../models/UserModel.php';
require_once '../../views/partials/alerts.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $user_id = intval($_GET['id']);
  $userModel = new UserModel($conn);
  $result = $userModel->toggleStatus($user_id);

  if ($result === 'blocked' || $result === 'active') {
    setFlash('success', "Status pengguna berhasil diperbarui menjadi {$result}!");
  } elseif ($result === false) {
    setFlash('error', "Pengguna tidak ditemukan.");
  } else {
    setFlash('error', "Terjadi kesalahan saat memperbarui status pengguna.");
  }

  header("Location: ../../views/admin?tab=users");

  exit;
}
