<?php
require_once '../../config/init.php';
require_once '../../models/OrderModel.php';
require_once '../../models/UserModel.php'; // Untuk ambil info pembeli
require_once '../../views/partials/alerts.php';

$orderModel = new OrderModel($conn);
$userModel = new UserModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
  $order_id = (int) $_POST['order_id'];

  $order = $orderModel->getOrderById($order_id);
  if (!$order || $order['store_id'] != ($_SESSION['store_id'] ?? null)) {
    setFlash('error', "Anda tidak berhak menolak pesanan ini.");
    header('Location: ../../views/orders/store_orders.php');
    exit;
  }

  $success = $orderModel->updateOrderStatus($order_id, 'Ditolak');

  if ($success) {
    $buyer = $userModel->getUserById($order['user_id']);
    $phone = $buyer['phone'] ?? null;

    if ($phone) {
      // Format nomor HP ke format WhatsApp
      $phone = preg_replace('/[^0-9]/', '', $phone);
      if (strpos($phone, '0') === 0) {
        $phone = '62' . substr($phone, 1);
      }

      // Buat pesan WhatsApp
      $orderLink = $BASE_URL . '/orders/?id=' . $order_id;
      $message = "Halo *{$buyer['name']}*, mohon maaf, pesanan kamu kami tolak karena alasan tertentu.\n" .
        "🛒 *ID Pesanan:* {$order['id']}\n\n" .
        "Cek detail pesanan di:\n{$orderLink}\n\n" .
        "Terima kasih atas pengertiannya.";

      $waLink = "https://wa.me/{$phone}?text=" . urlencode($message);
      $_SESSION['wa_redirect'] = $waLink;
      setFlash('success', "Pesanan berhasil ditolak. Anda akan diarahkan ke WhatsApp dalam beberapa detik...");
      header('Location: ../../views/orders/store_orders.php');
      exit;
    } else {
      setFlash('warning', 'Pesanan ditolak, tetapi nomor WhatsApp pembeli tidak tersedia.');
    }
  } else {
    setFlash('error', 'Gagal menolak pesanan.');
  }

  header('Location: ../../views/orders/store_orders.php');
  exit;
}
