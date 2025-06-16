<?php
require_once '../../config/init.php';
require_once '../../models/OrderModel.php';
require_once '../../models/UserModel.php'; // Dibutuhkan untuk ambil data pembeli
require_once '../../views/partials/alerts.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../../views/login/');
  exit;
}

$order_id = $_POST['order_id'] ?? null;

if (!$order_id) {
  setFlash('error', "ID pesanan tidak ditemukan.");
  header('Location: ../../views/orders/store_orders.php');
  exit;
}

$orderModel = new OrderModel($conn);
$order = $orderModel->getOrderById($order_id);

if (!$order || $order['store_id'] != ($_SESSION['store_id'] ?? null)) {
  setFlash('error', "Anda tidak berhak mengakses pesanan ini.");
  header('Location: ../../views/orders/store_orders.php');
  exit;
}

// Update status pesanan
$success = $orderModel->updateStatus($order_id, 'Dikirim', 'Dipesan', 'store_id', $order['store_id']);

if ($success) {
  // Ambil data user (pembeli)
  $userModel = new UserModel($conn);
  $buyer = $userModel->getUserById($order['user_id']);

  $phone = $buyer['phone'] ?? null;

  if ($phone) {
    // Format nomor WA
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (strpos($phone, '0') === 0) {
      $phone = '62' . substr($phone, 1);
    }

    // Buat link ke detail pesanan
    $orderLink = $BASE_URL . '/orders/?id=' . $order_id;

    // Pesan WA
    $message = "Hai *{$buyer['name']}*, pesanan kamu sudah kami kirim!\n" .
      "🛒 *ID Pesanan:* {$order['id']}\n" .
      "🔍 Cek dan lacak di:\n$orderLink\n\n" .
      "Klik *Selesaikan* jika sudah diterima. Terima kasih 😊";

    $waLink = "https://wa.me/{$phone}?text=" . urlencode($message);

    $_SESSION['wa_redirect'] = $waLink;
    setFlash('success', "Pesanan berhasil dikirim. Anda akan diarahkan ke WhatsApp dalam beberapa detik...");
    header('Location: ../../views/orders/store_orders.php');
    exit;
  } else {
    setFlash('warning', "Pesanan berhasil dikirim. Namun, nomor WhatsApp pembeli tidak tersedia.");
  }
} else {
  setFlash('error', "Gagal memperbarui status pesanan.");
}

header('Location: ../../views/orders/store_orders.php');
exit;
