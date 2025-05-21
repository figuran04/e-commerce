<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../models/CategoryModel.php';

$productModel = new ProductModel($conn);
$categoryModel = new CategoryModel($conn);

$pageTitle = "Daftar Produk";
$categoryID = null;
$categoryName = null;

// Cek apakah ada filter kategori
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
  $categoryID = intval($_GET['category']); // gunakan intval untuk memastikan tipe int
  $categoryName = $categoryModel->getNameById($categoryID);

  if ($categoryName) {
    $pageTitle = "Kategori: " . htmlspecialchars($categoryName);
  } else {
    // Kategori tidak ditemukan → beri fallback
    $categoryID = null;
  }
}

// Ambil semua kategori & produk (filter jika perlu)
$categoriesResult = $categoryModel->getAll(); // ini bisa diproses di view
$products = $productModel->getAll($categoryID);
