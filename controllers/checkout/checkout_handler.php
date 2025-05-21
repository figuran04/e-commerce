<?php
require_once '../../config/init.php';
require_once '../../models/CartModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$cartModel = new CartModel($conn);
$cartData = $cartModel->getCartItemsByUserId($user_id);

$cart_items = $cartData['items'];
$total_price = $cartData['total_price'];
