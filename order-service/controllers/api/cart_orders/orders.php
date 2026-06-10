<?php
// controllers/api/cart_orders/orders.php - API Endpoint: GET/POST /api/orders (Protected)
// $currentUser diisi oleh api/gateway.php setelah validasi JWT

require_once __DIR__ . '/../../../models/OrderModel.php';
require_once __DIR__ . '/../../../models/CartModel.php';
require_once __DIR__ . '/../../../helpers/service_helper.php';

$userId = $currentUser['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Tidak terautentikasi."]);
    exit;
}

$orderModel   = new OrderModel();
$cartModel    = new CartModel();

// ─────────────────────────── GET: Riwayat pesanan user ───────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $orders = $orderModel->getOrdersByUserIdWithItems($userId);
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "count"  => count($orders),
        "data"   => $orders
    ]);
    exit;
}

// ────────────────────────── POST: Buat pesanan baru (Checkout) ───────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input    = json_decode(file_get_contents('php://input'), true) ?? [];

    // Terima daftar cart_id yang ingin di-checkout
    $cartIds  = $input['cart_ids'] ?? [];

    if (empty($cartIds) || !is_array($cartIds)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Harap sertakan cart_ids (array ID item keranjang yang akan di-checkout)."]);
        exit;
    }
    $cartIds = array_map('intval', $cartIds);

    // Validasi: pastikan alamat user sudah diisi
    $users = ServiceHelper::fetchUsers([$userId]);
    $user  = $users[$userId] ?? null;
    if (!$user || empty($user['address'])) {
        http_response_code(422);
        echo json_encode(["status" => "error", "message" => "Silakan lengkapi alamat Anda di profil sebelum checkout."]);
        exit;
    }

    // Ambil item keranjang berdasarkan cart_ids
    $cartItems = $cartModel->getCartItemsByIds($cartIds);
    if (empty($cartItems)) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Item keranjang tidak ditemukan."]);
        exit;
    }

    // Validasi kepemilikan setiap cart item (keamanan)
    foreach ($cartItems as $item) {
        if (!$cartModel->isCartItemOwnedByUser((int)$item['cart_id'], $userId)) {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "Anda tidak memiliki akses ke salah satu item keranjang."]);
            exit;
        }
    }

    // Validasi semua produk dari toko yang sama
    $storeIds = array_unique(array_column($cartItems, 'store_id'));
    if (count($storeIds) > 1) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Semua produk dalam satu checkout harus berasal dari toko yang sama."]);
        exit;
    }

    $storeId = (int)$storeIds[0];

    // Validasi stok dan hitung total
    $productIds = array_column($cartItems, 'product_id');
    $products   = ServiceHelper::fetchProducts($productIds);

    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $product = $products[$item['product_id']] ?? null;
        if (!$product) {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Produk '{$item['name']}' tidak ditemukan."]);
            exit;
        }
        if ($product['stock'] < $item['quantity']) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Stok produk '{$item['name']}' tidak mencukupi (stok tersisa: {$product['stock']})."]);
            exit;
        }
        $totalPrice += $item['price'] * $item['quantity'];
    }

    // Buat record pesanan di DB
    $orderId = $orderModel->createOrder($userId, $storeId, $totalPrice);
    if (!$orderId) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal memproses pesanan."]);
        exit;
    }

    // Simpan detail pesanan & kurangi stok
    foreach ($cartItems as $item) {
        $orderModel->addOrderDetail($orderId, $item['product_id'], $item['quantity'], $item['price']);
        $orderModel->updateProductStock($item['product_id'], $item['quantity']);
    }

    // Hapus item dari keranjang
    $cartModel->removeItemsByIds($userId, $cartIds);

    http_response_code(201);
    echo json_encode([
        "status"   => "success",
        "message"  => "Pesanan berhasil dibuat.",
        "order_id" => (int)$orderId,
        "data"     => [
            "order_id"    => (int)$orderId,
            "store_id"    => $storeId,
            "total_price" => (float)$totalPrice,
            "status"      => "Dipesan",
            "items"       => $cartItems
        ]
    ]);
    exit;
}

http_response_code(405);
echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
