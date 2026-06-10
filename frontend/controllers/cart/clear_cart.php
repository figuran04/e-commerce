<?php
session_start();
require_once __DIR__ . '/../../config/init.php';
require_once '../../models/CartModel.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login/");
  exit;
}

$user_id = $_SESSION['user_id'];
$cartModel = new CartModel();

// Hapus semua cart user
$cartModel->clearCartByUserId($user_id);

// Redirect ke halaman keranjang
header("Location: ../../views/cart/");
exit;
