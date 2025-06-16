<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../views/partials/alerts.php'; // ✅ Flash message

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login");
  exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['is_admin']);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  setFlash('error', "ID produk tidak valid.");
  header("Location: ../../views/store/");
  exit;
}

$product_id = (int) $_GET['id'];

$productModel = new ProductModel($conn);
$product = $productModel->getById($product_id);

// ✅ Produk tidak ditemukan
if (!$product) {
  setFlash('error', "Produk tidak ditemukan!");
  header("Location: ../../views/store/");
  exit;
}

// ✅ Cek izin akses
if ($product['user_id'] != $user_id && !$is_admin) {
  setFlash('error', "Anda tidak memiliki izin untuk menghapus produk ini!");
  header("Location: ../../views/store/");
  exit;
}

// ✅ Proses hapus
if ($productModel->delete($product_id)) {
  setFlash('success', "Produk berhasil dihapus!");
} else {
  setFlash('error', "Gagal menghapus produk. Mungkin produk ini terkait dengan pesanan.");
}

header("Location: ../../views/store/");
exit;
