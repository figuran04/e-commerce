<?php
// controllers/cart/remove_cart.php
require '../../config/init.php';
require_once '../../models/CartModel.php'; // Memanggil model

// Pastikan pengguna sudah login dan ID keranjang tersedia
if (!isset($_SESSION['user_id']) || !isset($_GET['cart_id'])) {
  header("Location: ../../views/cart");
  exit;
}

$cart_id = $_GET['cart_id'];

// Membuat instance dari CartModel
$cartModel = new CartModel($conn);

// Menghapus produk dari keranjang
$cartModel->removeFromCart($cart_id);

// Redirect ke halaman keranjang
header("Location: ../../views/cart");
exit;
