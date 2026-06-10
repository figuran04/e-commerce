<?php
require_once __DIR__ . '/../../config/init.php';
require_once '../../models/UserModel.php';
require_once __DIR__ . '/../../helpers/flash.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $user_id = intval($_GET['id']);
  $userModel = new UserModel();

  $currentRole = $userModel->getRoleById($user_id);

  if ($currentRole !== null) {
    $newRole = ($currentRole === 'admin') ? 'user' : 'admin';

    if ($userModel->updateRole($user_id, $newRole)) {
      setFlash('success', "Role pengguna berhasil diperbarui menjadi {$newRole}!");
    } else {
      setFlash('error', "Terjadi kesalahan saat memperbarui role pengguna.");
    }
  } else {
    setFlash('error', "Pengguna tidak ditemukan.");
  }

  header("Location: ../../views/admin?tab=users");
  exit;
}
