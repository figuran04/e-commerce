<?php
require '../../config/init.php';
require_once '../../models/CartModel.php';
require_once '../../views/partials/alerts.php'; // <- Untuk setFlash

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_ids'])) {
  $ids = array_filter(explode(',', $_POST['selected_ids']), 'is_numeric');
  $userId = $_SESSION['user_id'] ?? 0;

  $cartModel = new CartModel($pdo);
  $cartModel->deleteItemsByIds($ids, $userId);

  setFlash('success', "Beberapa produk berhasil dihapus dari keranjang.");
}

header("Location: ../../views/cart/index.php");
exit;
