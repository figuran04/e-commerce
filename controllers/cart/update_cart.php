<?php
// controllers/cart/update_cart.php
require '../../config/init.php';
require_once '../../models/CartModel.php'; // Memanggil model

// Pastikan pengguna sudah login dan data yang dibutuhkan ada
if (!isset($_SESSION['user_id']) || !isset($_POST['cart_id']) || !isset($_POST['quantity'])) {
  header("Location: ../../views/cart");
  exit;
}

$cart_id = $_POST['cart_id'];
$quantity = (int) $_POST['quantity'];

// Membuat instance dari CartModel
$cartModel = new CartModel($conn);

// Memperbarui jumlah produk di keranjang
$cartModel->updateQuantity($cart_id, $quantity);

// Redirect ke halaman keranjang
header("Location: ../../views/cart");
exit;
