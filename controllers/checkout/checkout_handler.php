<?php
require_once '../../config/init.php';
require_once '../../models/CartModel.php';
require_once '../../views/partials/alerts.php'; // Tambahkan ini untuk setFlash()

// Hanya tangani jika POST dari cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
  $selected_product_ids = $_POST['selected_items'];
  $_SESSION['selected_product_ids'] = $selected_product_ids;
  header("Location: index.php"); // redirect untuk hindari resubmit POST
  exit;
}

// Ambil data dari session
$selected_product_ids = $_SESSION['selected_product_ids'] ?? [];

if (empty($selected_product_ids)) {
  setFlash('error', "Pilih produk terlebih dahulu untuk checkout.");
  header("Location: ../cart/index.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$cartModel = new CartModel($conn);

// Dapatkan item keranjang berdasarkan ID yang dipilih
$cart_items = $cartModel->getCartItemsByIds($selected_product_ids);

// Hitung total harga
$total_price = array_sum(array_map(function ($item) {
  return $item['price'] * $item['quantity'];
}, $cart_items));
