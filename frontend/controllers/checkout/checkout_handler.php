<?php
require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../helpers/jwt_helper.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/StoreModel.php';

// Jika session belum ada, coba re-sync dari Authorization header (fallback JWT)
if (!isset($_SESSION['user_id'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (empty($authHeader) && function_exists('getallheaders')) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
    }
    $token = null;
    if (preg_match('/Bearer\s(\S+)/i', $authHeader, $m)) {
        $token = $m[1];
    }
    if ($token) {
        $payload = JWTHelper::validate($token);
        if ($payload && isset($payload['user_id'])) {
            $uid = $payload['user_id'];
            $userModel  = new UserModel();
            $storeModel = new StoreModel();
            $user  = $userModel->getUserById($uid);
            $store = $storeModel->getStoreByUserId($uid);
            if ($user) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role']  = $user['role'];
                $_SESSION['is_admin']   = ($user['role'] === 'admin') ? 1 : 0;
                if ($store) {
                    $_SESSION['store_id']   = $store['id'];
                    $_SESSION['store_name'] = $store['name'];
                }
            }
        }
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/login/");
    exit;
}

require_once '../../models/CartModel.php';
require_once __DIR__ . '/../../helpers/flash.php';

// Hanya tangani jika POST dari cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
  $selected_product_ids = $_POST['selected_items'];
  $_SESSION['selected_product_ids'] = $selected_product_ids;
  header("Location: index.php"); // redirect untuk hindari resubmit POST
  exit;
}

// Ambil data dari session
$selected_product_ids = $_SESSION['selected_product_ids'] ?? [];

if (empty($selected_product_ids)) {
  setFlash('error', "Pilih produk terlebih dahulu untuk checkout.");
  header("Location: ../cart/index.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$cartModel = new CartModel();

// Dapatkan item keranjang berdasarkan ID yang dipilih
$cart_items = $cartModel->getCartItemsByIds($selected_product_ids);

// Hitung total harga
$total_price = array_sum(array_map(function ($item) {
  return $item['price'] * $item['quantity'];
}, $cart_items));
