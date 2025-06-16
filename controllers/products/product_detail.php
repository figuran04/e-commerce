<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../views/partials/alerts.php'; // Tambahkan ini

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  setFlash('error', "Produk tidak ditemukan.");
  header("Location: ../../views/home");
  exit;
}

$id = $_GET['id'];
$productModel = new ProductModel($conn);
$product = $productModel->getById($id);

if (!$product) {
  setFlash('error', "Produk tidak ditemukan.");
  header("Location: ../../views/home");
  exit;
}

$_SESSION['product_detail'] = $product;
header("Location: ../../views/product_detail?id=$id");
exit;
