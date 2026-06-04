<?php
// helpers/jwt_helper.php - Helper Stateless JWT tanpa dependensi eksternal

class JWTHelper {
    // Secret Key untuk enkripsi/tanda tangan token. Di lingkungan production sebaiknya diletakkan di file config aman.
    private static $secret = "Zerovaa_EcoFriendly_Super_Secret_Key_987654321!!!";

    // Encode string ke format Base64Url
    private static function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    // Decode string dari format Base64Url
    private static function base64UrlDecode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }

    /**
     * Membuat Token JWT baru.
     * 
     * @param array $payload Data payload user (seperti user_id, email, role)
     * @param int $expirySeconds Durasi masa aktif token dalam detik (default 1 jam / 3600 detik)
     * @return string Token JWT lengkap
     */
    public static function generate($payload, $expirySeconds = 3600) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        $payload['iat'] = time();
        $payload['exp'] = time() + $expirySeconds;
        $payloadJson = json_encode($payload);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payloadJson);

        // Membuat signature SHA256 hmac
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * Memvalidasi token JWT dan mengembalikan payload jika valid.
     * 
     * @param string $jwt Token JWT
     * @return array|false Payload jika valid, false jika tidak valid / kedaluwarsa
     */
    public static function validate($jwt) {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return false;
        }

        list($header, $payload, $signature) = $parts;

        // Buat signature pembanding
        $validSignature = hash_hmac('sha256', $header . "." . $payload, self::$secret, true);
        $base64UrlValidSignature = self::base64UrlEncode($validSignature);

        // Hindari serangan timing-attack dengan menggunakan hash_equals
        if (!hash_equals($base64UrlValidSignature, $signature)) {
            return false;
        }

        // Parse payload
        $payloadData = json_decode(self::base64UrlDecode($payload), true);
        if (!$payloadData || !isset($payloadData['exp'])) {
            return false;
        }

        // Cek kedaluwarsa
        if (time() >= $payloadData['exp']) {
            return false;
        }

        return $payloadData;
    }
}
