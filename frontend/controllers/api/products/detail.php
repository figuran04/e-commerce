<?php
// controllers/api/products/detail.php - API Endpoint: GET /api/products/detail?id=X

require_once __DIR__ . '/../../../models/ProductModel.php';
require_once __DIR__ . '/../../../models/StoreModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
    exit;
}

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Parameter 'id' produk wajib diisi dan harus berupa angka."]);
    exit;
}

$productModel = new ProductModel();
$product = $productModel->getById($id);

if (!$product) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Produk dengan id=$id tidak ditemukan."]);
    exit;
}

// Sertakan info toko dari store_id produk
$storeModel = new StoreModel();
$store = $storeModel->getStoreById($product['store_id'] ?? 0);

http_response_code(200);
echo json_encode([
    "status" => "success",
    "data"   => array_merge($product, ["store" => $store ?: null])
]);
