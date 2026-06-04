<?php
// controllers/api/auth/sync_session.php
// Endpoint: POST /api/auth/sync_session (Protected)
// Tujuan: Terima JWT yang valid, lalu set $_SESSION agar halaman PHP lama
//         (yang masih bergantung pada $_SESSION) tetap berfungsi normal.

$userId = $currentUser['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Token tidak valid."]);
    exit;
}

require_once __DIR__ . '/../../../models/UserModel.php';
require_once __DIR__ . '/../../../models/StoreModel.php';

$userModel  = new UserModel();
$storeModel = new StoreModel($conn);

$user  = $userModel->getUserById($userId);
$store = $storeModel->getStoreByUserId($userId);

if (!$user) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan."]);
    exit;
}

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set session yang sama persis seperti saat login konvensional
$_SESSION['user_id']    = $user['id'];
$_SESSION['user_name']  = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role']  = $user['role'];
$_SESSION['is_admin']   = ($user['role'] === 'admin') ? 1 : 0;

if ($store) {
    $_SESSION['store_id']   = $store['id'];
    $_SESSION['store_name'] = $store['name'];
}

http_response_code(200);
echo json_encode([
    "status"  => "success",
    "message" => "Session berhasil disinkronkan.",
    "session" => [
        "user_id"   => $_SESSION['user_id'],
        "user_name" => $_SESSION['user_name'],
        "is_admin"  => $_SESSION['is_admin'],
        "store_id"  => $_SESSION['store_id'] ?? null,
    ]
]);
