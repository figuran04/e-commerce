<?php
// controllers/api/auth/logout.php
// Endpoint: POST /api/auth/logout
// Tujuan: Destroy session PHP dan hapus cookie JWT saat user logout.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

// Hapus cookie JWT
setcookie('zerovaa_jwt', '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'samesite' => 'Strict',
]);

http_response_code(200);
echo json_encode(["status" => "success", "message" => "Berhasil logout."]);
