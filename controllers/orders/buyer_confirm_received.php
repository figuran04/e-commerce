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

$user_id   = $_SESSION['user_id'];
$order_id  = $_POST['order_id'] ?? null;

if (!$order_id) {
  setFlash('error', "ID pesanan tidak ditemukan.");
  header('Location: ../../views/orders/user_orders.php');
  exit;
}

$orderModel   = new OrderModel($conn);
$productModel = new ProductModel($conn);
$userModel    = new UserModel($conn);

$user = $userModel->getUserById($user_id);
// Ambil detail order
$order = $orderModel->getOrderById($order_id);

if (!$order || $order['user_id'] != $user_id) {
  setFlash('error', "Pesanan tidak ditemukan atau bukan milik Anda.");
  header('Location: ../../views/orders/user_orders.php');
  exit;
}

// Update status pesanan
$success = $orderModel->updateStatus($order_id, 'Selesai', 'Dikirim', 'user_id', $user_id);

if ($success) {
  // Tambah jumlah produk terjual
  $items = $orderModel->getOrderItems($order_id);
  foreach ($items as $item) {
    $productModel->incrementSoldCount($item['product_id'], $item['quantity']);
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
    $message = "Halo kak, saya *{$user['name']}* sudah menerima pesanan dengan ID *{$order['id']}* dan menyelesaikan transaksi.\n" .
      "📝 Detail pesanan:\n$orderLink\n\nTerima kasih atas layanan dan kerjasamanya 😊";

    $waLink = "https://wa.me/{$phone}?text=" . urlencode($message);

    $_SESSION['wa_redirect'] = $waLink;
    setFlash('success', "Pesanan selesai. Anda akan diarahkan ke WhatsApp dalam beberapa detik...");
    header('Location: ../../views/orders/user_orders.php');
    exit;
  } else {
    setFlash('warning', "Pesanan selesai. Tapi penjual belum memiliki nomor WhatsApp.");
  }
} else {
  setFlash('error', "Gagal menyelesaikan pesanan.");
}

header('Location: ../../views/orders/user_orders.php');
exit;
