<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $product_id = intval($_GET['id']);
  $productModel = new ProductModel($conn);

  if ($productModel->delete($product_id)) {
    $_SESSION['success'] = "Produk berhasil dihapus!";
  } else {
    $_SESSION['error'] = "Terjadi kesalahan saat menghapus produk!";
  }

  header("Location: ../../views/admin?tab=products");
  exit;
}
