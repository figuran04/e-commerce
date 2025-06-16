<?php
require '../../config/init.php'; // $conn = PDO instance
require '../../models/OrderModel.php';
require '../../models/StoreModel.php';
require '../../models/UserModel.php'; // ✅ tambahan

if (!isset($_SESSION['user_id'])) {
  header('Location: ../../views/login');
  exit;
}

$orderModel = new OrderModel($conn);
$storeModel = new StoreModel($conn);
$userModel = new UserModel($conn); // ✅ buat instance
$userId = $_SESSION['user_id'];

// Ambil semua order user dengan detail item
$orders = $orderModel->getOrdersByUserIdWithItems($userId);

// Filter berdasarkan status jika ada param ?status=...
$statusFilter = $_GET['status'] ?? 'Semua';

if ($statusFilter !== 'Semua') {
  $orders = array_values(array_filter($orders, function ($order) use ($statusFilter) {
    return $order['status'] === $statusFilter;
  }));
}

// Tambahkan store_phone untuk setiap order
foreach ($orders as &$order) {
  $store = $storeModel->getStoreById($order['store_id']);
  $userName = $userModel->getUserById($userId);
  if ($store) {
    $storeOwner = $userModel->getUserById($store['user_id']);
    $order['store_phone'] = $storeOwner['phone'] ?? null;
  } else {
    $order['store_phone'] = null;
  }
  $order['user_name'] = $userName['name'];
}


// Kirim data ke view
$order_data = [
  'orders' => array_values($orders),
  'statusFilter' => $statusFilter
];
