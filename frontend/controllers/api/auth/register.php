<?php
// controllers/api/auth/register.php - API Endpoint: POST /api/auth/register

require_once __DIR__ . '/../../../config/init.php';
require_once __DIR__ . '/../../../models/UserModel.php';
require_once __DIR__ . '/../../../helpers/jwt_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode HTTP tidak didukung."]);
    exit;
}

// Support JSON body maupun form-data
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$name     = trim($input['name']     ?? $_POST['name']     ?? '');
$email    = trim($input['email']    ?? $_POST['email']    ?? '');
$password = trim($input['password'] ?? $_POST['password'] ?? '');

if (empty($name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Nama, email, dan password wajib diisi."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Format email tidak valid."]);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Password minimal 6 karakter."]);
    exit;
}

$userModel = new UserModel();

if ($userModel->isEmailExist($email)) {
    http_response_code(409);
    echo json_encode(["status" => "error", "message" => "Email sudah terdaftar."]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

if ($userModel->register($name, $email, $hashedPassword)) {
    $user = $userModel->getByEmail($email);

    $payload = [
        "user_id"    => $user['id'],
        "user_name"  => $user['name'],
        "user_email" => $user['email'],
        "user_role"  => $user['role'],
        "store_id"   => null
    ];
    $token = JWTHelper::generate($payload, 43200);

    http_response_code(201);
    echo json_encode([
        "status"  => "success",
        "message" => "Registrasi berhasil.",
        "token"   => $token,
        "user"    => [
            "id"    => $user['id'],
            "name"  => $user['name'],
            "email" => $user['email'],
            "role"  => $user['role']
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Gagal mendaftar, silakan coba lagi."]);
}
