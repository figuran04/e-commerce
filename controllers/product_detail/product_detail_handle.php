<?php
require '../../config/init.php';
require '../../models/ProductModel.php';
require_once '../../models/StoreModel.php';
require '../../views/partials/alerts.php'; // ✅ Tambahkan ini untuk akses setFlash()

$productModel = new ProductModel($conn);
$storeModel = new StoreModel($conn);

$product_id = $_GET['id'] ?? null;

if (!$product_id || !is_numeric($product_id)) {
  setFlash('error', 'ID produk tidak valid.');
  header("Location: ../../views/products");
  exit;
}

$product = $productModel->getById((int)$product_id);

if (!$product) {
  setFlash('error', 'Produk tidak ditemukan.');
  header("Location: ../../views/products");
  exit;
}

// Setelah produk valid, ambil toko berdasarkan user_id dari produk
$store = $storeModel->getStoreByUserId($product['user_id']);
