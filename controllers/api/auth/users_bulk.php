<?php
// controllers/api/auth/users_bulk.php

require_once __DIR__ . '/../../../models/UserModel.php';

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

$userModel = new UserModel(); // already uses $conn_auth
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $conn_auth->prepare("SELECT id, name, email, phone, address, role, status FROM users WHERE id IN ($placeholders)");
$stmt->execute($ids);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map users by their ID
$mapped = [];
foreach ($users as $u) {
    $mapped[$u['id']] = $u;
}

http_response_code(200);
echo json_encode([
    "status" => "success",
    "data" => $mapped
]);
