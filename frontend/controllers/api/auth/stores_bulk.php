<?php
// controllers/api/auth/stores_bulk.php

require_once __DIR__ . '/../../../models/StoreModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$ids = $input['ids'] ?? [];

if (empty($ids) || !is_array($ids)) {
    http_response_code(200);
    echo json_encode(["status" => "success", "data" => []]);
    exit;
}

// Ensure all IDs are integers
$ids = array_map('intval', $ids);

$storeModel = new StoreModel();
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $conn_auth->prepare("SELECT id, user_id, name, address, description FROM stores WHERE id IN ($placeholders)");
$stmt->execute($ids);
$stores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map stores by their ID for easy lookup
$mapped = [];
foreach ($stores as $s) {
    $mapped[$s['id']] = $s;
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "data" => $mapped
]);
