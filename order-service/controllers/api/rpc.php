<?php
require_once __DIR__ . '/../../config/init.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['model']) || !isset($input['method'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid RPC request"]);
    exit;
}

$modelClass = $input['model'];
$method = $input['method'];
$args = $input['args'] ?? [];

$allowedModels = ['OrderModel', 'CartModel'];
if (!in_array($modelClass, $allowedModels)) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Model not allowed"]);
    exit;
}

require_once __DIR__ . '/../../models/' . $modelClass . '.php';

try {
    $modelInstance = new $modelClass();
    if (!method_exists($modelInstance, $method)) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Method not found"]);
        exit;
    }

    $result = call_user_func_array([$modelInstance, $method], $args);
    echo json_encode(["status" => "success", "data" => $result]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
