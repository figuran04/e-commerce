<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $user_id = $_GET['id'];

  // Query untuk menghapus pengguna
  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Pengguna berhasil dihapus!";
    header("Location: ../../views/admin/?status=success");
    exit;
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus pengguna!";
    header("Location: ../../views/admin/?status=error");
    exit;
  }
}
