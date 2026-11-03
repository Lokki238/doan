<?php
/**
 * =====================================================
 * FILE: api/payments.php
 * Mục đích: Lấy danh sách giao dịch + bộ lọc + thống kê nhanh
 * Tuần 9 - Dashboard
 * =====================================================
 *
 * Method: GET
 * Query params:
 *   - trang_thai   : PENDING | SUCCESS | FAILED | all
 *   - loai_cong    : VIETQR | MOMO | ... | all
 *   - tu_ngay      : YYYY-MM-DD
 *   - den_ngay     : YYYY-MM-DD
 *   - limit        : số bản ghi (mặc định 50)
 *   - offset       : phân trang
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Cần đăng nhập', [], 401);
}

$user = getCurrentUser();
$shopId = $user['shop_id'];

if (empty($shopId)) {
    jsonResponse(false, 'Tài khoản chưa gắn cửa hàng', [], 400);
}

// ----- Lấy tham số lọc -----
$trangThai = $_GET['trang_thai'] ?? 'all';
$loaiCong  = $_GET['loai_cong'] ?? 'all';
$tuNgay    = $_GET['tu_ngay'] ?? '';
$denNgay   = $_GET['den_ngay'] ?? '';
$limit     = min(100, max(1, (int)($_GET['limit'] ?? 50)));
$offset    = max(0, (int)($_GET['offset'] ?? 0));

try {
    $pdo = getDB();

    // Xây dựng câu SQL động an toàn
    $where = ["CuaHang_id = ?"];
    $params = [$shopId];

    if ($trangThai !== 'all' && in_array($trangThai, ['PENDING', 'SUCCESS', 'FAILED', 'EXPIRED', 'CANCELLED'])) {
        $where[] = "TrangThai = ?";
        $params[] = $trangThai;
    }
    if ($loaiCong !== 'all' && in_array($loaiCong, ['VIETQR', 'MOMO', 'VNPAY', 'ZALOPAY'])) {
        $where[] = "LoaiCongThanhToan = ?";
        $params[] = $loaiCong;
    }
    if ($tuNgay) {
        $where[] = "DATE(ThoiGianTao) >= ?";
        $params[] = $tuNgay;
    }
    if ($denNgay) {
        $where[] = "DATE(ThoiGianTao) <= ?";
        $params[] = $denNgay;
    }

    $whereSql = implode(' AND ', $where);

    // Đếm tổng
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM GiaoDich WHERE $whereSql");
    $stmt->execute($params);
    $total = (int)$stmt->fetchColumn();

    // Lấy danh sách
    $sql = "
        SELECT 
            ID, MaDonHang, SoTien, MoTa, LoaiCongThanhToan,
            TrangThai, DuLieuQR, DuongDanThanhToan,
            MaGiaoDichCongThanhToan, ThoiGianThanhToan,
            ThoiGianTao, ThoiGianCapNhat
        FROM GiaoDich
        WHERE $whereSql
        ORDER BY ThoiGianTao DESC
        LIMIT $limit OFFSET $offset
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $list = $stmt->fetchAll();

    // Thống kê nhanh (cho dashboard)
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) AS tong_giao_dich,
            SUM(CASE WHEN TrangThai = 'SUCCESS' THEN 1 ELSE 0 END) AS thanh_cong,
            SUM(CASE WHEN TrangThai = 'PENDING' THEN 1 ELSE 0 END) AS dang_cho,
            SUM(CASE WHEN TrangThai = 'FAILED' THEN 1 ELSE 0 END) AS that_bai,
            COALESCE(SUM(CASE WHEN TrangThai = 'SUCCESS' THEN SoTien ELSE 0 END), 0) AS tong_doanh_thu,
            COALESCE(SUM(CASE WHEN TrangThai = 'SUCCESS' AND DATE(ThoiGianThanhToan) = CURDATE() THEN SoTien ELSE 0 END), 0) AS doanh_thu_hom_nay
        FROM GiaoDich
        WHERE CuaHang_id = ?
    ");
    $stmt->execute([$shopId]);
    $stats = $stmt->fetch();

    // Doanh thu 7 ngày gần nhất (cho biểu đồ)
    $stmt = $pdo->prepare("
        SELECT 
            DATE(ThoiGianThanhToan) AS ngay,
            SUM(SoTien) AS doanh_thu
        FROM GiaoDich
        WHERE CuaHang_id = ?
          AND TrangThai = 'SUCCESS'
          AND ThoiGianThanhToan >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(ThoiGianThanhToan)
        ORDER BY ngay ASC
    ");
    $stmt->execute([$shopId]);
    $chartRaw = $stmt->fetchAll();

    // Điền đủ 7 ngày (kể cả ngày không có doanh thu)
    $chartData = [];
    for ($i = 6; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime("-$i days"));
        $found = 0;
        foreach ($chartRaw as $row) {
            if ($row['ngay'] === $day) {
                $found = (int)$row['doanh_thu'];
                break;
            }
        }
        $chartData[] = [
            'ngay' => $day,
            'label' => date('d/m', strtotime($day)),
            'doanh_thu' => $found
        ];
    }

    jsonResponse(true, 'OK', [
        'stats' => $stats,
        'chart' => $chartData,
        'total' => $total,
        'payments' => $list
    ]);

} catch (Exception $e) {
    jsonResponse(false, 'Lỗi: ' . $e->getMessage(), [], 500);
}
