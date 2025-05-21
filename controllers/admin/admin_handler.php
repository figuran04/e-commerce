<?php
require_once '../../models/ProductModel.php';
require_once '../../models/CategoryModel.php';
require_once '../../models/UserModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../../views/login");
  exit;
}

$products = ProductModel::all();
$categories = CategoryModel::all();
$users = UserModel::all();

$tab = $_GET['tab'] ?? 'products';
$data = [];

switch ($tab) {
  case 'products':
    $data = $products;
    break;
  case 'categories':
    $data = $categories;
    break;
  case 'users':
    $data = $users;
    break;
}
