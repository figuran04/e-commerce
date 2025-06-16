<?php
require '../../config/init.php';
require_once '../../models/CartModel.php';
require_once '../../views/partials/alerts.php'; // untuk setFlash

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login");
  exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

$cartModel = new CartModel($conn);
$cart_id = $cartModel->addToCart($user_id, $product_id, $quantity);

// Jika tombol "Beli Sekarang" ditekan
if (isset($_POST['buy_now'])) {
  $_SESSION['selected_product_ids'] = [$cart_id]; // simpan ke sesi untuk checkout
  header("Location: ../../views/checkout/index.php");
  exit;
}

setFlash('success', "Produk berhasil ditambahkan ke keranjang.");
header("Location: ../../views/product_detail/?id=$product_id");
exit;
