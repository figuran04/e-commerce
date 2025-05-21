<?php
require_once '../../config/init.php';
require_once '../../models/UserModel.php';
require_once '../../models/ProductModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit;
}

$user_id = $_SESSION['user_id'];

$userModel = new UserModel($conn);
$productModel = new ProductModel($conn);

$profile_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];
$user = $userModel->getUserById($user_id);
$products = $productModel->getProductsByUserId($user_id);
