<?php
/**
 * =====================================================
 * FILE: api/me.php
 * Mục đích: Trả về trạng thái đăng nhập và thông tin tài khoản hiện tại
 * =====================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn()) {
    echo json_encode([
        'success' => true,
        'data' => [
            'logged_in' => false
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$user = getCurrentUser();

// Truy vấn thông tin cập nhật mới nhất từ DB
try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT ID, Email, HoTen, VaiTro, ThoiGianTao FROM nguoidung WHERE ID = ? LIMIT 1");
    $stmt->execute([$user['id']]);
    $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($dbUser) {
        $user['ho_ten'] = $dbUser['HoTen'];
        $user['email'] = $dbUser['Email'];
        $user['vai_tro'] = $dbUser['VaiTro'];
        $user['created_at'] = $dbUser['ThoiGianTao'];
        $_SESSION['ho_ten'] = $dbUser['HoTen'];
        $_SESSION['email'] = $dbUser['Email'];
        $_SESSION['vai_tro'] = $dbUser['VaiTro'];
    }
} catch (Exception $e) {
    // Giữ dữ liệu session nếu có lỗi kết nối
}

$role = strtoupper($user['vai_tro'] ?? 'USER');
$isPrivileged = in_array($role, ['OWNER', 'MANAGER', 'STAFF', 'ADMIN', 'QUAN_TRI']);

echo json_encode([
    'success' => true,
    'data' => [
        'logged_in'    => true,
        'id'           => $user['id'],
        'email'        => $user['email'],
        'ho_ten'       => $user['ho_ten'] ?: explode('@', $user['email'])[0],
        'vai_tro'      => $user['vai_tro'],
        'is_privileged'=> $isPrivileged,
        'shop_id'      => $user['shop_id'] ?? null,
        'ten_ch'       => $user['ten_ch'] ?? '',
        'created_at'   => $user['created_at'] ?? null
    ]
], JSON_UNESCAPED_UNICODE);
