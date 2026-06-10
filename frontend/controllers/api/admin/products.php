<?php
// controllers/api/admin/products.php
// Endpoint: GET /api/admin/products — Semua produk (admin only)

require_once __DIR__ . '/../../../models/ProductModel.php';

if (($currentUser['user_role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses ditolak. Hanya admin."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
    exit;
}

$productModel = new ProductModel();

// Dukung filter kategori dan sort
$categoryId = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? (int)$_GET['category_id'] : null;
$sort       = $_GET['sort'] ?? 'newest';
$search     = $_GET['search'] ?? '';

// Ambil semua produk
$products = $productModel->all();

// Filter by search
if ($search) {
    $products = array_filter($products, fn($p) => stripos($p['name'], $search) !== false);
}
// Filter by category
if ($categoryId) {
    $products = array_filter($products, fn($p) => (int)$p['category_id'] === $categoryId);
}
// Sort
usort($products, function($a, $b) use ($sort) {
    return match ($sort) {
        'price_asc'  => $a['price'] - $b['price'],
        'price_desc' => $b['price'] - $a['price'],
        'stock_asc'  => $a['stock'] - $b['stock'],
        'stock_desc' => $b['stock'] - $a['stock'],
        default      => $b['id'] - $a['id'], // newest
    };
});

http_response_code(200);
echo json_encode([
    "status" => "success",
    "count"  => count($products),
    "data"   => array_values($products)
]);
