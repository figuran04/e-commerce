<?php
require '../../config/init.php';
require '../../models/ProductModel.php';

$productModel = new ProductModel($conn);
$product_id = $_GET['id'] ?? null;

if (!$product_id || !is_numeric($product_id)) {
  $_SESSION['error'] = "ID produk tidak valid.";
  header("Location: ../../views/products");
  exit;
}

$product = $productModel->getById((int)$product_id);

if (!$product) {
  $_SESSION['error'] = "Produk tidak ditemukan.";
  header("Location: ../../views/products");
  exit;
}
