<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../views/partials/alerts.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

if (isset($_GET['id'])) {
  $product_id = intval($_GET['id']);
  $productModel = new ProductModel($conn);

  if ($productModel->delete($product_id)) {
    setFlash('success', "Produk berhasil dihapus!");
  } else {
    setFlash('error', "Terjadi kesalahan saat menghapus produk!");
  }

  header("Location: ../../views/admin?tab=products");
  exit;
}
