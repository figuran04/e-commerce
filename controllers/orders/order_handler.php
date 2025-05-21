<?php
require '../../config/init.php';
require '../../models/OrderModel.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit;
}

if (!isset($_GET['order_id'])) {
  $_SESSION['error'] = "Pesanan tidak ditemukan!";
  header("Location: ../checkout");
  exit;
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

$orderModel = new OrderModel($conn);
$order = $orderModel->getOrderById($order_id, $user_id);

if (!$order) {
  $_SESSION['error'] = "Pesanan tidak ditemukan!";
  header("Location: ../checkout");
  exit;
}

$order_items = $orderModel->getOrderItems($order_id);
