<?php
require_once '../../config/init.php';
require_once '../../models/ProductModel.php';
require_once '../../models/CategoryModel.php';

$productModel = new ProductModel($conn);
$categoryModel = new CategoryModel($conn);

$pageTitle = "Daftar Produk";
$categoryID = null;
$categoryName = null;
$categoryIDs = []; // untuk query produk multi kategori

// Cek apakah ada filter kategori
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
  $categoryID = intval($_GET['category']);
  $categoryName = $categoryModel->getNameById($categoryID);

  if ($categoryName) {
    $pageTitle = "Kategori: " . htmlspecialchars($categoryName);
    $categoryIDs[] = $categoryID;

    // Cari anak-anak dari kategori utama
    $childs = $categoryModel->getChildren($categoryID);
    foreach ($childs as $child) {
      $categoryIDs[] = $child['id'];

      // Cari cucu dari tiap anak
      $grandChildren = $categoryModel->getChildren($child['id']);
      foreach ($grandChildren as $grandChild) {
        $categoryIDs[] = $grandChild['id'];
      }
    }
  } else {
    $categoryID = null;
  }
}

// Ambil semua kategori untuk keperluan view (opsional)
$categoriesResult = $categoryModel->getAll();
$rootCategories = [];
$childCategories = [];

if (isset($categoriesResult) && is_array($categoriesResult)) {
  foreach ($categoriesResult as $row) {
    if (is_null($row['parent_id'])) {
      $rootCategories[] = $row;
    } else {
      $childCategories[] = $row;
    }
  }

  // Thumbnail dari anak kategori saja (bukan root)
  $categoryThumbnails = $productModel->getTopImagesByCategory(array_column($childCategories, 'id'));
}

// Ambil produk berdasarkan kategori (multi ID jika ada)
if (!empty($categoryIDs)) {
  $products = $productModel->getByMultipleCategories($categoryIDs);
} else {
  $products = $productModel->getAll();
}

// Tambahkan nama kategori ke setiap produk
foreach ($products as &$product) {
  $category = $categoryModel->getById($product['category_id']);
  $product['category'] = $category['name'] ?? 'Tanpa Kategori';
}
unset($product); // best practice untuk foreach-by-reference
