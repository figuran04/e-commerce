<?php
require '../../config/init.php';
require '../../models/OrderModel.php';
require '../../models/CartModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../views/login");
  exit;
}

$orderModel = new OrderModel($conn);
$cartModel = new CartModel($conn);
$user_id = $_SESSION['user_id'];

$cartItems = $cartModel->getCartItems($user_id);

if (count($cartItems) === 0) {
  header("Location: ../../views/cart?error=empty_cart");
  exit;
}

$order_id = $orderModel->createOrder($user_id, $cartItems);

if ($order_id) {
  header("Location: ../../views/order/order_success.php?order_id=$order_id");
  exit;
} else {
  header("Location: ../../views/cart?error=order_failed");
  exit;
}
