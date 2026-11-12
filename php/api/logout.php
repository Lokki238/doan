<?php
/**
 * =====================================================
 * FILE: api/logout.php
 * Mục đích: API Đăng xuất
 * Tuần 7 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * Cách hoạt động:
 * 1. Gọi hàm logout() trong includes/auth.php
 *    → Xóa Session + xóa cookie
 * 2. Nếu request đến từ JavaScript (fetch/AJAX) → trả JSON
 * 3. Nếu request đến từ thẻ <a href="..."> → redirect về trang login
 */

require_once __DIR__ . '/../includes/auth.php';

// Thực hiện đăng xuất
logout();

// -----------------------------------------------------
// Phân biệt loại request để trả về phù hợp
// -----------------------------------------------------

// Kiểm tra xem có phải request AJAX / fetch không
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
       || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

if ($isAjax) {
    // Trả về JSON cho frontend JavaScript
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'message' => 'Đã đăng xuất thành công'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Nếu không phải AJAX → chuyển hướng về trang đăng nhập
header('Location: /html/login.html');
exit;
