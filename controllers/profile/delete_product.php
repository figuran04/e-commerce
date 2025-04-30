<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $product_id = $_GET['id'];

  // Query untuk menghapus produk
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Produk berhasil dihapus!";
    header("Location: ../../views/profile?status=success");
    exit;
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus produk!";
    header("Location: ../../views/profile?status=error");
    exit;
  }
}
