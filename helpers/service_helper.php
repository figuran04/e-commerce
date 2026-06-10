<?php
// helpers/service_helper.php

class ServiceHelper
{
    /**
     * Get the base URL for the requested service (auth or api).
     * Automatically detects whether virtual hosts are configured.
     */
    public static function getBaseUrl(string $service): string
    {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = rtrim(getenv('APP_URL') ?: 'http://localhost/5/e-commerce', '/');
        
        // If we are using the zerovaa subdomains
        if (strpos($host, 'zerovaa.com') !== false) {
            if ($service === 'auth') {
                return "http://auth.zerovaa.com/api/";
            } else {
                return "http://api.zerovaa.com/api/";
            }
        }
        
        // Fallback for local or Docker deployment
        if ($service === 'auth') {
            return $baseUrl . '/api/gateway_auth.php?route=';
        }

        return $baseUrl . '/api/gateway.php?route=';
    }

    /**
     * Perform an internal HTTP call to a microservice.
     */
    public static function call(string $service, string $endpoint, string $method = 'GET', $data = null): array
    {
        $baseUrl = self::getBaseUrl($service);
        
        // Construct final URL
        // If localhost gateway routing, append to query param. Otherwise, append to path.
        if (strpos($baseUrl, '?route=') !== false) {
            $url = $baseUrl . $endpoint;
        } else {
            $url = $baseUrl . $endpoint;
        }

        $ch = curl_init();
        
        // Set request method and data
        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif (strtoupper($method) === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif (strtoupper($method) === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } else {
            // GET, append query parameters if it's an array
            if ($data !== null && is_array($data)) {
                $query = http_build_query($data);
                $url .= (strpos($url, '?') !== false ? '&' : '?') . $query;
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Internal-Call: true' // Header flag to identify internal calls
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds timeout

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return [
                'status' => 'error',
                'message' => "Internal connection failed to $url. Error: $error"
            ];
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status' => 'error',
                'message' => "Failed to parse JSON response from $url. Raw output: " . substr($response, 0, 200)
            ];
        }

        return $decoded;
    }

    /**
     * Fetch products bulk by IDs.
     */
    public static function fetchProducts(array $productIds): array
    {
        if (empty($productIds)) return [];
        
        $res = self::call('api', 'products/bulk', 'POST', ['ids' => $productIds]);
        if (isset($res['status']) && $res['status'] === 'success') {
            return $res['data'] ?? [];
        }
        return [];
    }

    /**
     * Fetch stores bulk by IDs.
     */
    public static function fetchStores(array $storeIds): array
    {
        if (empty($storeIds)) return [];
        
        $res = self::call('auth', 'stores/bulk', 'POST', ['ids' => $storeIds]);
        if (isset($res['status']) && $res['status'] === 'success') {
            return $res['data'] ?? [];
        }
        return [];
    }

    /**
     * Fetch users bulk by IDs.
     */
    public static function fetchUsers(array $userIds): array
    {
        if (empty($userIds)) return [];
        
        $res = self::call('auth', 'users/bulk', 'POST', ['ids' => $userIds]);
        if (isset($res['status']) && $res['status'] === 'success') {
            return $res['data'] ?? [];
        }
        return [];
    }

    /**
     * Call Product Service to decrease stock for an item.
     */
    public static function decreaseStock(int $productId, int $quantity): bool
    {
        $res = self::call('api', 'products/decrease-stock', 'POST', [
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
        return isset($res['status']) && $res['status'] === 'success';
    }

    /**
     * Call Product Service to increase/restore stock for an item.
     */
    public static function increaseStock(int $productId, int $quantity): bool
    {
        $res = self::call('api', 'products/increase-stock', 'POST', [
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
        return isset($res['status']) && $res['status'] === 'success';
    }

    /**
     * Fetch products bulk by IDs with fallback to local database.
     * Tries API first, falls back to local ProductModel if API fails.
     */
    public static function fetchProductsWithFallback(array $productIds): array
    {
        if (empty($productIds)) return [];
        
        // Try API first
        $res = self::call('api', 'products/bulk', 'POST', ['ids' => $productIds]);
        if (isset($res['status']) && $res['status'] === 'success' && !empty($res['data'])) {
            return $res['data'] ?? [];
        }
        
        // Fallback to local database
        global $conn_products;
        if (!isset($conn_products)) {
            return [];
        }
        
        try {
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $stmt = $conn_products->prepare("SELECT id, name, price, stock, image, store_id FROM products WHERE id IN ($placeholders)");
            $stmt->execute($productIds);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Map products by their ID for easy lookup
            $mapped = [];
            foreach ($products as $p) {
                $mapped[$p['id']] = $p;
            }
            return $mapped;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Fetch single product with fallback to local database.
     * Tries API first, falls back to local ProductModel if API fails.
     */
    public static function fetchProductWithFallback(int $productId): ?array
    {
        if ($productId <= 0) return null;
        
        // Try API first
        $res = self::call('api', 'products/bulk', 'POST', ['ids' => [$productId]]);
        if (isset($res['status']) && $res['status'] === 'success' && !empty($res['data'])) {
            return $res['data'][$productId] ?? null;
        }
        
        // Fallback to local database
        global $conn_products;
        if (!isset($conn_products)) {
            return null;
        }
        
        try {
            $stmt = $conn_products->prepare("SELECT id, name, price, stock, image, store_id FROM products WHERE id = ?");
            $stmt->execute([$productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            return null;
        }
    }
}
