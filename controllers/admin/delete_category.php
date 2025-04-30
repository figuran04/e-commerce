<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $category_id = $_GET['id'];

  // Query untuk menghapus kategori
  $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
  $stmt->bind_param("i", $category_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Kategori berhasil dihapus!";
    header("Location: ../../views/admin/?status=success");
    exit;
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus kategori!";
    header("Location: ../../views/admin/?status=error");
    exit;
  }
}
