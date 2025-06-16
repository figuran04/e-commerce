<?php
require '../../config/init.php';
require '../../models/OrderModel.php';
require '../../models/CartModel.php';
require '../../models/ProductModel.php';
require '../../models/UserModel.php';
require '../../views/partials/alerts.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: ../../views/login/index.php');
  exit;
}

$productModel = new ProductModel($conn);
$orderModel = new OrderModel($conn);
$cartModel = new CartModel($conn);
$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];
$user = $userModel->getUserById($userId);

if (empty($user['address'])) {
  setFlash('error', "Silakan lengkapi alamat Anda sebelum melakukan pemesanan.");
  header('Location: ../../views/profile/edit_profile.php');
  exit;
}

// Ambil produk yang dipilih
$selected_product_ids = $_SESSION['selected_product_ids'] ?? [];
if (empty($selected_product_ids)) {
  setFlash('error', "Tidak ada produk yang dipilih untuk checkout.");
  header('Location: ../../views/cart/index.php');
  exit;
}

$cart_items = $cartModel->getCartItemsByIds($selected_product_ids);
if (empty($cart_items)) {
  setFlash('error', "Produk tidak ditemukan atau sudah tidak tersedia.");
  header('Location: ../../views/cart/index.php');
  exit;
}

$store_id = $cart_items[0]['store_id'] ?? null;
if (!$store_id) {
  setFlash('error', "Produk tidak terkait dengan toko manapun.");
  header("Location: ../../views/checkout/index.php");
  exit;
}

// Hitung total harga
$total_price = 0;
foreach ($cart_items as $item) {
  $total_price += $item['price'] * $item['quantity'];
}

// Buat order baru
$order_id = $orderModel->createOrder($userId, $store_id, $total_price);
if (!$order_id) {
  setFlash('error', "Gagal memproses pesanan.");
  header('Location: ../../views/checkout/index.php');
  exit;
}

// Simpan detail order & update stok
foreach ($cart_items as $item) {
  $product_id = $item['product_id'];
  $quantity = $item['quantity'];
  $price = $item['price'];

  $success = $orderModel->addOrderDetail($order_id, $product_id, $quantity, $price);
  if (!$success) {
    setFlash('error', "Gagal menambahkan detail pesanan.");
    header('Location: ../../views/checkout/index.php');
    exit;
  }

  $productModel->decreaseStock($product_id, $quantity);
}

// Hapus item dari keranjang
$cart_ids_to_remove = array_column($cart_items, 'cart_id');
$cartModel->removeItemsByIds($userId, $cart_ids_to_remove);

// Kirim notifikasi WhatsApp ke pemilik toko
$storeOwner = $userModel->getUserByStoreId($store_id);
$phone = $storeOwner['phone'] ?? null;

if ($phone) {
  // Format nomor: 0812xxx -> 62812xxx
  $phone = preg_replace('/[^0-9]/', '', $phone);
  if (strpos($phone, '0') === 0) {
    $phone = '62' . substr($phone, 1);
  }

  // Buat link ke halaman pesanan
  $orderLink = $BASE_URL . '/orders/?id=' . $order_id;

  // Buat pesan WhatsApp
  $message = "Halo kak, saya *{$user['name']}* baru saja memesan produk dari toko kakak 😊\n" .
    "🛒 *ID Pesanan:* {$order['id']}\n" .
    "📝 Berikut detailnya:\n$orderLink\n\nTerima kasih, ditunggu konfirmasinya ya!";

  $waLink = "https://wa.me/{$phone}?text=" . urlencode($message);

  // Bersihkan session sebelum redirect
  unset($_SESSION['selected_product_ids']);

  // Tambahkan pesan bahwa ini bagian dari transaksi
  $_SESSION['wa_redirect'] = $waLink;
  setFlash('success', "Order berhasil diproses! Lanjutkan transaksi via WhatsApp, diarahkan ke WhatsApp dalam beberapa detik...<br><a href={$waLink} class='underline'>Klik di sini jika tidak diarahkan otomatis</a>");
  header('Location: ../../views/orders/user_orders.php');
  exit;
} else {
  // Bersihkan session
  unset($_SESSION['selected_product_ids']);

  // Tampilkan pesan jika tidak ada nomor HP
  setFlash('warning', "Order berhasil diproses, namun penjual belum mengisi nomor WhatsApp. Silakan hubungi penjual atau menunggu penjual mengirim pesanan.");
  header('Location: ../../views/orders/user_orders.php');
  exit;
}
