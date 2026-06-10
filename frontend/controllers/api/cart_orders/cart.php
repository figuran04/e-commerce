<?php
// controllers/api/cart_orders/cart.php - API Endpoint: GET/POST/DELETE /api/cart (Protected)
// $currentUser diisi oleh api/gateway.php setelah validasi JWT

require_once __DIR__ . '/../../../models/CartModel.php';
require_once __DIR__ . '/../../../models/ProductModel.php';

$userId = $currentUser['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Tidak terautentikasi."]);
    exit;
}

$cartModel    = new CartModel();
$productModel = new ProductModel();

// ─────────────────────────── GET: Ambil isi keranjang ────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $items = $cartModel->getCartItems($userId);

    // Hitung total harga
    $total = array_reduce($items, function ($carry, $item) {
        return $carry + ($item['price'] * $item['quantity']);
    }, 0);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data"   => [
            "items"       => $items,
            "total_price" => (float)$total,
            "item_count"  => count($items)
        ]
    ]);
    exit;
}

// ──────────────────────── POST: Tambah/update item di keranjang ──────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input      = json_decode(file_get_contents('php://input'), true) ?? [];
    $productId  = (int)($input['product_id'] ?? $_POST['product_id'] ?? 0);
    $quantity   = (int)($input['quantity']   ?? $_POST['quantity']   ?? 1);

    if (!$productId || $quantity < 1) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "product_id dan quantity (min 1) wajib diisi."]);
        exit;
    }

    // Validasi produk ada & stok cukup
    $product = $productModel->getById($productId);
    if (!$product) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Produk tidak ditemukan."]);
        exit;
    }
    if ($product['stock'] < $quantity) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Stok produk tidak mencukupi (tersedia: {$product['stock']})."]);
        exit;
    }

    $cartId = $cartModel->addToCart($userId, $productId, $quantity);
    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "message" => "Produk berhasil ditambahkan ke keranjang.",
        "cart_id" => $cartId
    ]);
    exit;
}

// ─────────────────────── DELETE: Hapus item dari keranjang ───────────────────
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input  = json_decode(file_get_contents('php://input'), true) ?? [];
    $cartId = (int)($input['cart_id'] ?? $_GET['cart_id'] ?? 0);

    if (!$cartId) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "cart_id wajib diisi."]);
        exit;
    }

    $deleted = $cartModel->removeCartItem($cartId, $userId);
    if ($deleted) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Item berhasil dihapus dari keranjang."]);
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Item tidak ditemukan atau bukan milik Anda."]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
