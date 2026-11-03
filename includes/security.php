<?php
/**
 * =====================================================
 * FILE: includes/security.php
 * Mục đích: Mã hóa / giải mã thông tin nhạy cảm (API Key, Secret Key)
 * Tuần 8 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * Tại sao cần mã hóa?
 * - API Key và Secret Key của cổng thanh toán rất nhạy cảm
 * - Không được lưu plain text trong database
 * - Chỉ giải mã khi thực sự cần gọi sang cổng thanh toán
 *
 * Cách hoạt động:
 * - Dùng OpenSSL AES-256-CBC
 * - Key bí mật nằm trong file này (trong thực tế nên để ở biến môi trường)
 * - Mỗi lần mã hóa tạo IV ngẫu nhiên → kết quả khác nhau dù cùng input
 */

// =====================================================
// KEY BÍ MẬT - ĐỔI CHUỖI NÀY THÀNH CHUỖI DÀI, NGẪU NHIÊN CỦA BẠN
// (ít nhất 32 ký tự). KHÔNG ĐƯỢC COMMIT LÊN GIT CÔNG KHAI!
// =====================================================
define('ENCRYPTION_KEY', 'FreshMart_SecretKey_2026_ThayDoiNgay_Abc123!@#');

/**
 * Mã hóa chuỗi (API Key, Secret...)
 * 
 * @param string $plainText  Chuỗi cần mã hóa
 * @return string            Chuỗi đã mã hóa (base64), an toàn để lưu DB
 */
function encryptData(string $plainText): string {
    if (empty($plainText)) {
        return '';
    }

    $key = hash('sha256', ENCRYPTION_KEY, true); // 32 bytes key
    $iv  = openssl_random_pseudo_bytes(16);      // IV ngẫu nhiên 16 bytes

    $encrypted = openssl_encrypt(
        $plainText,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    // Kết hợp IV + dữ liệu mã hóa rồi encode base64 để lưu dễ dàng
    return base64_encode($iv . $encrypted);
}

/**
 * Giải mã chuỗi đã mã hóa
 * 
 * @param string $encryptedText  Chuỗi base64 từ database
 * @return string                Chuỗi gốc (plain text)
 */
function decryptData(string $encryptedText): string {
    if (empty($encryptedText)) {
        return '';
    }

    $data = base64_decode($encryptedText);
    if ($data === false || strlen($data) < 17) {
        return '';
    }

    $key = hash('sha256', ENCRYPTION_KEY, true);
    $iv  = substr($data, 0, 16);          // 16 byte đầu là IV
    $encrypted = substr($data, 16);       // Phần còn lại là dữ liệu

    $decrypted = openssl_decrypt(
        $encrypted,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return $decrypted !== false ? $decrypted : '';
}
