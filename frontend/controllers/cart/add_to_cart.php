<?php
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../helpers/flash.php';
require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login/");
  exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int)($_POST['product_id'] ?? 0);
$quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

if ($product_id <= 0) {
  setFlash('error', 'Produk tidak valid.');
  header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '../../views/home/'));
  exit;
}

$productModel = new ProductModel();
$product = $productModel->getById($product_id);

if (!$product) {
  setFlash('error', 'Produk tidak ditemukan.');
  header('Location: ../../views/home/');
  exit;
}

if ((int)$product['stock'] < $quantity) {
  setFlash('error', 'Stok produk tidak mencukupi untuk jumlah yang Anda pilih.');
  header('Location: ../../views/product_detail/?id=' . $product_id);
  exit;
}

$cartModel = new CartModel();
$cart_id = $cartModel->addToCart($user_id, $product_id, $quantity);

if ($cart_id <= 0) {
  setFlash('error', 'Gagal menambahkan produk ke keranjang. Stok mungkin sudah habis.');
  header('Location: ../../views/product_detail/?id=' . $product_id);
  exit;
}

// Jika tombol "Beli Sekarang" ditekan
if (isset($_POST['buy_now'])) {
  $_SESSION['selected_product_ids'] = [$cart_id]; // simpan ke sesi untuk checkout
  header("Location: ../../views/checkout/index.php");
  exit;
}

setFlash('success', "Produk berhasil ditambahkan ke keranjang.");
header("Location: $BASE_URL/product_detail/?id=$product_id");
exit;

