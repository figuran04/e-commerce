<?php
// controllers/api/auth/login.php - API Endpoint untuk Login User (JWT)

require_once __DIR__ . '/../../../models/UserModel.php';
require_once __DIR__ . '/../../../models/StoreModel.php';
require_once __DIR__ . '/../../../helpers/jwt_helper.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data input baik berupa JSON maupun POST form-data biasa
    $input = json_decode(file_get_contents('php://input'), true);
    
    $email = isset($input['email']) ? filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL) : '';
    $password = isset($input['password']) ? trim($input['password']) : '';

    // Fallback jika dikirimkan sebagai standar application/x-www-form-urlencoded
    if (empty($email) && empty($password)) {
        $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    }

    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Email dan password wajib diisi."
        ]);
        exit;
    }

    $userModel = new UserModel();
    $user = $userModel->getByEmail($email);

    // Validasi keberadaan user & password hash
    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Email atau password salah."
        ]);
        exit;
    }

    // Cari tahu apakah user memiliki toko
    $storeModel = new StoreModel($conn);
    $store = $storeModel->getStoreByUserId($user['id']);
    $store_id = $store ? $store['id'] : null;

    // Siapkan data payload
    $payload = [
        "user_id" => $user['id'],
        "user_name" => $user['name'],
        "user_email" => $user['email'],
        "user_role" => $user['role'],
        "store_id" => $store_id
    ];

    // Generate token JWT yang aktif selama 12 jam (43200 detik)
    $token = JWTHelper::generate($payload, 43200);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Login berhasil.",
        "token" => $token,
        "user" => [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "role" => $user['role'],
            "store_id" => $store_id
        ]
    ]);
    exit;
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Metode HTTP '" . $_SERVER['REQUEST_METHOD'] . "' tidak didukung oleh endpoint ini."
    ]);
    exit;
}
