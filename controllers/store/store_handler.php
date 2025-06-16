<?php
require_once '../../config/init.php';
require_once '../../models/UserModel.php';
require_once '../../models/ProductModel.php';
require_once '../../models/StoreModel.php';
require_once '../../views/partials/alerts.php';

$userModel = new UserModel($conn);
$productModel = new ProductModel($conn);
$storeModel = new StoreModel($conn);

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login");
  exit;
}

$session_user_id = $_SESSION['user_id'];
$session_store_id = $_SESSION['store_id'] ?? null;

$store_id = $_GET['id'] ?? $session_store_id;
$store = null;
$user = null;
$products = [];
$is_own_profile = false;

// Cek jika ada store_id dari GET atau session
if ($store_id) {
  $store = $storeModel->getStoreById($store_id);

  if ($store) {
    $products = $productModel->getProductsByStoreId($store_id, $_GET['sort'] ?? 'newest');
    $user = $userModel->getUserById($store['user_id']);
    $is_own_profile = ($session_user_id == $store['user_id']);
    // $phone = $user['phone'];
  }
} else {
  // Tidak ada store_id → bisa jadi user belum punya toko
  $store = null;
  $products = [];
  $user = $userModel->getUserById($session_user_id);
  $is_own_profile = true;
}
