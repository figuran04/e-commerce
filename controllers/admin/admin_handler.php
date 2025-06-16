<?php

require_once '../../models/ProductModel.php';
require_once '../../models/CategoryModel.php';
require_once '../../models/UserModel.php';
require_once '../../views/partials/alerts.php';

// Cek autentikasi dan hak admin
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}


// Inisialisasi model
$productModel = new ProductModel($conn);
$categoryModel = new CategoryModel($conn);
$userModel = new UserModel($conn);

// Ambil data
$products = method_exists($productModel, 'all') ? $productModel->all() : [];
$categories = method_exists($categoryModel, 'getAll') ? $categoryModel->getAll() : [];
$users = method_exists($userModel, 'all') ? $userModel->all() : [];

// Tab navigasi
$tab = $_GET['tab'] ?? 'products';
$admin_data = [];
$search = $_GET['search'] ?? '';

if ($tab === 'categories') {
  $rootCategories = $categoryModel->getAll();
}
// Ambil data sesuai tab dan search
switch ($tab) {
  case 'products':
    $admin_data = method_exists($productModel, 'search') && $search ? $productModel->search($search) : $products;
    break;
  case 'categories':
    $admin_data = method_exists($categoryModel, 'search') && $search ? $categoryModel->search($search) : $categories;
    break;
  case 'users':
    $admin_data = method_exists($userModel, 'search') && $search ? $userModel->search($search) : $users;
    break;
  default:
    $admin_data = $products;
    $tab = 'products';
    break;
}

// Pastikan data selalu array, jika tidak maka kosongkan
if (!is_array($admin_data)) {
  $admin_data = [];
}
