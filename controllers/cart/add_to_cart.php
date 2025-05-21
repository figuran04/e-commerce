<?php
// controllers/cart/add_to_cart.php
require '../../config/init.php';
require_once '../../models/CartModel.php'; // Memanggil model

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login");
  exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Membuat instance dari CartModel
$cartModel = new CartModel($conn);

// Menambahkan produk ke keranjang
$cartModel->addToCart($user_id, $product_id, $quantity);

// Redirect ke halaman keranjang
header("Location: ../../views/cart");
exit;
