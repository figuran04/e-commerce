<?php
require_once '../../config/init.php'; // harus memuat session_start dan fungsi flash
require_once '../../models/OrderModel.php';
require_once '../../helpers/flash.php';

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
  setFlash('error', 'ID pesanan tidak valid.');
  header('Location: ../home'); // redirect kembali ke daftar pesanan
  exit;
}

$orderModel = new OrderModel($pdo);
$order = $orderModel->getOrderByIdWithItemsAndUser($orderId);

$phone = $order['buyer_phone'];
if ($phone) {
  $phone = preg_replace('/[^0-9]/', '', $phone);
  if (strpos($phone, '0') === 0) {
    $phone = '62' . substr($phone, 1);
  }

  $message = "Halo *{$order['buyer_name']}*, info pesanan:";
  $waLink = "https://wa.me/{$phone}?text=" . urlencode($message);
}


if (!$order) {
  setFlash('error', 'Pesanan tidak ditemukan.');
  header('Location: ../home');
  exit;
}
