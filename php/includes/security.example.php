<?php
/**
 * =====================================================
 * FILE: includes/security.example.php
 * Mục đích: File mẫu mã hóa / giải mã bảo mật AES-256-CBC
 * Tuần 8 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 */

if (!defined('ENCRYPTION_KEY')) {
    $envKey = getenv('ENCRYPTION_KEY');
    if (!$envKey && file_exists(__DIR__ . '/../../.env')) {
        $envLines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($envLines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) continue;
            if (str_contains($line, '=')) {
                list($k, $v) = explode('=', $line, 2);
                if (trim($k) === 'ENCRYPTION_KEY') {
                    $envKey = trim($v, " \t\n\r\0\x0B\"'");
                    break;
                }
            }
        }
    }
    define('ENCRYPTION_KEY', $envKey ?: '');
}

/**
 * Mã hóa chuỗi bằng AES-256-CBC
 */
function encryptData(string $plainText): string {
    if (empty($plainText)) {
        return '';
    }

    if (empty(ENCRYPTION_KEY)) {
        throw new Exception('Lỗi bảo mật: Chưa cấu hình ENCRYPTION_KEY trong file .env hoặc biến môi trường.');
    }

    $key = hash('sha256', ENCRYPTION_KEY, true);
    $iv  = openssl_random_pseudo_bytes(16);

    $encrypted = openssl_encrypt(
        $plainText,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return base64_encode($iv . $encrypted);
}

/**
 * Giải mã chuỗi đã mã hóa
 */
function decryptData(string $encryptedText): string {
    if (empty($encryptedText)) {
        return '';
    }

    if (empty(ENCRYPTION_KEY)) {
        throw new Exception('Lỗi bảo mật: Chưa cấu hình ENCRYPTION_KEY trong file .env hoặc biến môi trường.');
    }

    $data = base64_decode($encryptedText);
    if ($data === false || strlen($data) < 17) {
        return '';
    }

    $key = hash('sha256', ENCRYPTION_KEY, true);
    $iv  = substr($data, 0, 16);
    $encrypted = substr($data, 16);

    $decrypted = openssl_decrypt(
        $encrypted,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return $decrypted !== false ? $decrypted : '';
}
