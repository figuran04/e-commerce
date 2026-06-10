<?php
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../helpers/flash.php';
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/StoreModel.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../login');
  exit;
}

$userId = $_SESSION['user_id'];
$storeModel = new StoreModel();
$orderModel = new OrderModel();

// Ambil data toko
$store = $storeModel->getStoreByUserId($userId);

if (!$store) {
  setFlash('error', "Anda belum memiliki toko.");
  header('Location: ../store/create_store.php');
  exit;
}

$storeId = $store['id'];

// Ambil semua pesanan beserta item produk dalam satu toko
$orders = $orderModel->getOrdersByStoreIdWithItems($storeId);

// Filter status jika ada
$statusFilter = $_GET['status'] ?? 'Semua';

if ($statusFilter !== 'Semua') {
  $orders = array_filter($orders, function ($order) use ($statusFilter) {
    return $order['status'] === $statusFilter;
  });
}

$store_order_data = [
  'orders' => $orders,
  'statusFilter' => $statusFilter,
];
