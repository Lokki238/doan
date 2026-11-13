<?php
/**
 * =====================================================
 * FILE: api/notifications.php
 * Mục đích: API lấy thông báo đơn hàng chờ thanh toán theo thời gian thực
 * Dành cho: Toàn bộ tài khoản có quyền từ Nhân viên trở lên (STAFF, OWNER, ADMIN, QUAN_TRI)
 * =====================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../../csdl/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Kiểm tra quyền: Chỉ tài khoản từ Nhân viên trở lên (isPrivileged) mới được nhận thông báo
if (!isPrivileged()) {
    echo json_encode([
        'success' => false,
        'message' => 'Bạn không có quyền truy cập thông báo này.',
        'data' => [
            'is_privileged' => false
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$user = getCurrentUser();

try {
    $pdo = getDB();

    // 1. Đếm tổng số đơn hàng đang chờ thanh toán (TrangThai = 'PENDING')
    $stmtCount = $pdo->query("SELECT COUNT(*) FROM giaodich WHERE TrangThai = 'PENDING'");
    $totalPending = (int)$stmtCount->fetchColumn();

    // 2. Lấy danh sách 10 đơn hàng PENDING mới nhất
    $stmtOrders = $pdo->prepare("
        SELECT 
            ID, 
            MaDonHang, 
            SoTien, 
            MoTa, 
            LoaiCongThanhToan, 
            TrangThai, 
            ThoiGianTao
        FROM giaodich 
        WHERE TrangThai = 'PENDING'
        ORDER BY ThoiGianTao DESC 
        LIMIT 10
    ");
    $stmtOrders->execute();
    $rows = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

    $now = time();
    $orders = [];
    foreach ($rows as $r) {
        $createdTime = strtotime($r['ThoiGianTao']);
        $diffSeconds = max(0, $now - $createdTime);

        if ($diffSeconds < 60) {
            $timeAgo = 'Vừa xong';
        } elseif ($diffSeconds < 3600) {
            $timeAgo = floor($diffSeconds / 60) . ' phút trước';
        } else {
            $timeAgo = floor($diffSeconds / 3600) . ' giờ trước';
        }

        $orders[] = [
            'id'              => $r['ID'],
            'ma_don_hang'     => $r['MaDonHang'],
            'so_tien'         => (int)$r['SoTien'],
            'so_tien_format'  => number_format($r['SoTien'], 0, ',', '.') . ' ₫',
            'mo_ta'           => $r['MoTa'] ?: ('Đơn hàng #' . $r['MaDonHang']),
            'loai_cong'       => $r['LoaiCongThanhToan'] ?: 'VIETQR',
            'thoi_gian_tao'   => $r['ThoiGianTao'],
            'thoi_gian_truoc' => $timeAgo,
            'is_just_now'     => ($diffSeconds <= 60)
        ];
    }

    // 3. Lấy đơn hàng mới nhất vừa thanh toán thành công trong 1 phút qua
    $stmtSuccess = $pdo->query("
        SELECT ID, MaDonHang, SoTien, ThoiGianThanhToan 
        FROM giaodich 
        WHERE TrangThai = 'SUCCESS' AND ThoiGianThanhToan >= NOW() - INTERVAL 1 MINUTE 
        ORDER BY ThoiGianThanhToan DESC 
        LIMIT 1
    ");
    $latestSuccess = $stmtSuccess->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'is_privileged'  => true,
            'user'           => [
                'ho_ten'  => $user['ho_ten'] ?? '',
                'vai_tro' => $user['vai_tro'] ?? 'STAFF'
            ],
            'total_pending'  => $totalPending,
            'orders'         => $orders,
            'latest_success' => $latestSuccess ?: null,
            'server_time'    => date('Y-m-d H:i:s')
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi truy vấn thông báo: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
