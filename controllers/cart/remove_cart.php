<?php
require_once '../../config/init.php';
require_once '../../models/CartModel.php';
require_once '../../views/partials/alerts.php'; // <- Tambahkan ini untuk setFlash()

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

if (!isset($_GET['cart_id']) || empty($_GET['cart_id'])) {
  setFlash('error', "ID produk keranjang tidak ditemukan.");
  header("Location: ../../views/cart/index.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = (int)$_GET['cart_id'];

$cartModel = new CartModel($conn);

try {
  // Cek kepemilikan item
  if (!$cartModel->isCartItemOwnedByUser($cart_id, $user_id)) {
    setFlash('error', "Item tidak ditemukan atau bukan milik Anda.");
    header("Location: ../../views/cart/index.php");
    exit;
  }

  // Hapus item
  if ($cartModel->removeCartItem($cart_id, $user_id)) {
    setFlash('success', "Item berhasil dihapus dari keranjang.");
  } else {
    setFlash('error', "Gagal menghapus item keranjang.");
  }
} catch (Exception $e) {
  setFlash('error', "Terjadi kesalahan: " . $e->getMessage());
}

header("Location: ../../views/cart/index.php");
exit;
