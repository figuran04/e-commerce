<?php
// controllers/cart/update_cart.php
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../helpers/flash.php';
require_once '../../models/CartModel.php'; // Memanggil model

// Pastikan pengguna sudah login dan data yang dibutuhkan ada
if (!isset($_SESSION['user_id']) || !isset($_POST['cart_id']) || !isset($_POST['quantity'])) {
  header("Location: ../../views/cart/");
  exit;
}

$cart_id = $_POST['cart_id'];
$quantity = (int) $_POST['quantity'];

// Membuat instance dari CartModel
$cartModel = new CartModel();

if (!$cartModel->updateQuantity($cart_id, $quantity)) {
  setFlash('error', 'Jumlah tidak bisa diperbarui karena stok tidak mencukupi.');
  header('Location: ../../views/cart/');
  exit;
}

header('Location: ../../views/cart/');
exit;
