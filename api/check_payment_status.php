<?php
/**
 * =====================================================
 * FILE: api/check_payment_status.php
 * Mục đích: Kiểm tra trạng thái thanh toán realtime cho trang payment_qr.html
 * =====================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';

$orderId = trim($_GET['order_id'] ?? '');

if (empty($orderId)) {
    jsonResponse(false, 'Thiếu mã đơn hàng (order_id)', [], 400);
}

try {
    $pdo = getDB();

    $stmt = $pdo->prepare("
        SELECT 
            ID, CuaHang_id, MaDonHang, SoTien, 
            MoTa, LoaiCongThanhToan, TrangThai, 
            MaGiaoDichCongThanhToan, ThoiGianThanhToan, ThoiGianTao
        FROM giaodich
        WHERE MaDonHang = ?
        ORDER BY ThoiGianTao DESC
        LIMIT 1
    ");
    $stmt->execute([$orderId]);
    $giaoDich = $stmt->fetch();

    if (!$giaoDich) {
        jsonResponse(false, 'Không tìm thấy giao dịch với mã: ' . $orderId, [], 404);
    }

    $isPaid = ($giaoDich['TrangThai'] === 'SUCCESS');

    jsonResponse(true, 'Lấy trạng thái thành công', [
        'giao_dich_id'           => $giaoDich['ID'],
        'ma_don_hang'            => $giaoDich['MaDonHang'],
        'so_tien'                => (int)$giaoDich['SoTien'],
        'loai_cong'              => $giaoDich['LoaiCongThanhToan'],
        'trang_thai'             => $giaoDich['TrangThai'],
        'is_paid'                => $isPaid,
        'thoi_gian_thanh_toan'   => $giaoDich['ThoiGianThanhToan'],
        'ma_giao_dich_cong'      => $giaoDich['MaGiaoDichCongThanhToan'] ?: ('WH-' . substr(md5($giaoDich['ID']), 0, 10)),
        'thoi_gian_tao'          => $giaoDich['ThoiGianTao']
    ]);

} catch (Exception $e) {
    jsonResponse(false, 'Lỗi hệ thống: ' . $e->getMessage(), [], 500);
}
