<?php
// controllers/cart/cart_handler.php
require_once '../../config/init.php';
require_once '../../models/CartModel.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit;
}

$user_id = $_SESSION['user_id'];

// Membuat instance dari CartModel
$cartModel = new CartModel($conn);

// Mengambil item keranjang berdasarkan user_id
$result = $cartModel->getCartItems($user_id);

// Memasukkan data ke dalam array untuk dipassing ke view
$cart_data = [
  'cartItems' => $result
];
