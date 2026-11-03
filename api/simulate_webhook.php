<?php
/**
 * =====================================================
 * FILE: api/simulate_webhook.php
 * Mục đích: Giả lập webhook VietQR để test (chỉ dùng khi demo/đồ án)
 * Tuần 9
 * =====================================================
 *
 * Trong thực tế webhook đến từ ngân hàng/Casso.
 * File này giúp bạn test nhanh mà không cần public URL.
 *
 * Cách dùng:
 * POST { "ma_don_hang": "DH...", "so_tien": 50000, "status": "SUCCESS" }
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/auth.php';

// Chỉ cho phép người đã đăng nhập gọi (tránh bị lạm dụng)
if (!isLoggedIn()) {
    jsonResponse(false, 'Cần đăng nhập để giả lập webhook', [], 401);
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$maDonHang = trim($input['ma_don_hang'] ?? '');
$soTien    = (int)($input['so_tien'] ?? 0);
$status    = strtoupper($input['status'] ?? 'SUCCESS');

if (empty($maDonHang)) {
    jsonResponse(false, 'Thiếu ma_don_hang', [], 400);
}

// Giả lập dữ liệu webhook gửi sang webhook_vietqr.php
$fakeWebhookData = [
    'description' => $maDonHang,
    'amount'      => $soTien,
    'tid'         => 'SIM' . time(),
    'status'      => $status,
    'when'        => date('Y-m-d H:i:s'),
    'source'      => 'simulate'
];

// Gọi nội bộ webhook (dùng cURL hoặc include)
// Cách đơn giản nhất: gửi request đến chính webhook_vietqr.php
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/webhook_vietqr.php',
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($fakeWebhookData),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

jsonResponse(
    $result['success'] ?? false,
    $result['message'] ?? 'Không nhận được phản hồi từ webhook',
    ['webhook_response' => $result, 'http_code' => $httpCode]
);
