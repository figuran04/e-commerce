<?php
require '../../config/init.php';
require_once '../../models/OrderModel.php';

$orderModel = new OrderModel($conn);
$user_id = $_SESSION['user_id'];  // Mendapatkan ID pengguna dari sesi

// Mendapatkan daftar pesanan pengguna
$orders = $orderModel->getUserOrders($user_id);
