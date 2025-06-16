<?php
require_once '../../config/init.php';
require_once '../../models/OrderModel.php';
require_once '../../models/ProductModel.php';
require_once '../../models/UserModel.php';
require_once '../../views/partials/alerts.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../../views/login/');
  exit;
}

$productModel = new ProductModel($conn);
$orderModel   = new OrderModel($conn);
$userModel    = new UserModel($conn);

$user_id  = $_SESSION['user_id'];
$order_id = $_POST['order_id'] ?? null;
$user = $userModel->getUserById($user_id);

if (!$order_id) {
  setFlash('error', "ID pesanan tidak ditemukan.");
  header('Location: ../../views/orders/user_orders.php');
  exit;
}

// Ambil data order
$order = $orderModel->getOrderById($order_id);
if (!$order || $order['user_id'] != $user_id) {
  setFlash('error', "Pesanan tidak ditemukan atau bukan milik Anda.");
  header('Location: ../../views/orders/user_orders.php');
  exit;
}

// Update status pesanan
$success = $orderModel->updateStatus($order_id, 'Dibatalkan', 'Dipesan', 'user_id', $user_id);

if ($success) {
  // Kembalikan stok produk
  $items = $orderModel->getOrderItems($order_id);
  foreach ($items as $item) {
    $productModel->increaseStock($item['product_id'], $item['quantity']);
  }

  // Kirim WA ke penjual
  $storeOwner = $userModel->getUserByStoreId($order['store_id']);
  $phone = $storeOwner['phone'] ?? null;

  if ($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (strpos($phone, '0') === 0) {
      $phone = '62' . substr($phone, 1);
    }

    $orderLink = $BASE_URL . '/orders/?id=' . $order_id;
    $message = "Halo kak, saya *{$user['name']}* baru saja membatalkan pesanan berikut:\n" .
      "🛒 *ID Pesanan:* {$order['id']}\n" .
      "📝 Detailnya bisa dicek di:\n$orderLink\n\nTerima kasih atas pengertiannya.";

    $waLink = "https://wa.me/{$phone}?text=" . urlencode($message);

    $_SESSION['wa_redirect'] = $waLink;
    setFlash('success', "Pesanan berhasil dibatalkan. Anda akan diarahkan ke WhatsApp dalam beberapa detik...");
    header('Location: ../../views/orders/user_orders.php');
    exit;
  } else {
    setFlash('warning', "Pesanan dibatalkan. Namun penjual belum menambahkan nomor WhatsApp.");
  }
} else {
  setFlash('error', "Gagal membatalkan pesanan.");
}

header('Location: ../../views/orders/user_orders.php');
exit;
