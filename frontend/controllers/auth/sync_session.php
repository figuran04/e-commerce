<?php
// controllers/auth/sync_session.php
// Endpoint: POST /controllers/auth/sync_session.php
// Tujuan: Terima JWT yang valid di frontend container, lalu set $_SESSION agar halaman PHP
//         pada container frontend tetap memiliki session user yang terautentikasi.

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/init.php';
require_once __DIR__ . '/../../helpers/jwt_helper.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/StoreModel.php';

// Ambil Authorization Header
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
if (empty($authHeader) && isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
}

if (empty($authHeader)) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Akses ditolak. Token autentikasi tidak ditemukan."]);
    exit;
}

$token = null;
if (preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)) {
    $token = $matches[1];
}

if (!$token) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Token format tidak valid."]);
    exit;
}

$currentUser = JWTHelper::validate($token);
$userId = $currentUser['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Token tidak valid atau telah kedaluwarsa."]);
    exit;
}

$userModel  = new UserModel();
$storeModel = new StoreModel();

$user  = $userModel->getUserById($userId);
if (!$user) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan."]);
    exit;
}

$store = $storeModel->getStoreByUserId($userId);

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set session
$_SESSION['user_id']    = $user['id'];
$_SESSION['user_name']  = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role']  = $user['role'];
$_SESSION['is_admin']   = ($user['role'] === 'admin') ? 1 : 0;

if ($store) {
    $_SESSION['store_id']   = $store['id'];
    $_SESSION['store_name'] = $store['name'];
} else {
    unset($_SESSION['store_id'], $_SESSION['store_name']);
}

http_response_code(200);
echo json_encode([
    "status"  => "success",
    "message" => "Session frontend berhasil disinkronkan.",
    "session" => [
        "user_id"   => $_SESSION['user_id'],
        "user_name" => $_SESSION['user_name'],
        "is_admin"  => $_SESSION['is_admin'],
        "store_id"  => $_SESSION['store_id'] ?? null,
    ]
]);
