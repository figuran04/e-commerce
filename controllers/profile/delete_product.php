<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login");
  exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['is_admin']);

if (isset($_GET['id'])) {
  $product_id = intval($_GET['id']);

  $productModel = new ProductModel($conn);
  $product = $productModel->getById($product_id);

  // Cek apakah produk ditemukan
  if (!$product) {
    $_SESSION['error'] = "Produk tidak ditemukan!";
    header("Location: ../../views/profile?status=notfound");
    exit;
  }

  // Cek kepemilikan atau admin
  if ($product['user_id'] != $user_id && !$is_admin) {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk menghapus produk ini!";
    header("Location: ../../views/profile?status=unauthorized");
    exit;
  }

  // Hapus produk
  if ($productModel->delete($product_id)) {
    $_SESSION['success'] = "Produk berhasil dihapus!";
    header("Location: ../../views/profile?status=success");
    exit;
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus produk!";
    header("Location: ../../views/profile?status=error");
    exit;
  }
}
