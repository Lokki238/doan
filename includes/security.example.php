<?php
/**
 * =====================================================
 * FILE: includes/security.example.php
 * Mục đích: File mẫu mã hóa / giải mã (không chứa key thật)
 * =====================================================
 */

define('ENCRYPTION_KEY', getenv('ENCRYPTION_KEY') ?: 'CHANGE_THIS_TO_YOUR_32_CHAR_SECRET_KEY');

function encryptData(string $plainText): string {
    if (empty($plainText)) {
        return '';
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

function decryptData(string $encryptedText): string {
    if (empty($encryptedText)) {
        return '';
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
