<?php
/**
 * =====================================================
 * FILE: api/reports.php
 * Mục đích: API Báo cáo & Thống kê doanh thu theo thời gian
 * =====================================================
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

$range = $_GET['range'] ?? '7days';
$tuNgay = $_GET['tu_ngay'] ?? '';
$denNgay = $_GET['den_ngay'] ?? '';

// Xác định khoảng ngày
$today = date('Y-m-d');
switch ($range) {
    case 'today':
        $startDate = $today;
        $endDate = $today;
        break;
    case '30days':
        $startDate = date('Y-m-d', strtotime('-29 days'));
        $endDate = $today;
        break;
    case 'this_month':
        $startDate = date('Y-m-01');
        $endDate = $today;
        break;
    case 'last_month':
        $startDate = date('Y-m-01', strtotime('first day of last month'));
        $endDate = date('Y-m-t', strtotime('last day of last month'));
        break;
    case 'this_year':
        $startDate = date('Y-01-01');
        $endDate = $today;
        break;
    case 'custom':
        $startDate = $tuNgay ?: date('Y-m-d', strtotime('-6 days'));
        $endDate = $denNgay ?: $today;
        break;
    case '7days':
    default:
        $startDate = date('Y-m-d', strtotime('-6 days'));
        $endDate = $today;
        break;
}

try {
    $pdo = getDB();

    // 1. Chỉ số tổng quan (KPIs) trong khoảng thời gian
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) AS tong_don,
            COALESCE(SUM(CASE WHEN TrangThai = 'SUCCESS' THEN 1 ELSE 0 END), 0) AS don_thanh_cong,
            COALESCE(SUM(CASE WHEN TrangThai = 'PENDING' THEN 1 ELSE 0 END), 0) AS don_dang_cho,
            COALESCE(SUM(CASE WHEN TrangThai = 'FAILED' THEN 1 ELSE 0 END), 0) AS don_that_bai,
            COALESCE(SUM(CASE WHEN TrangThai = 'SUCCESS' THEN SoTien ELSE 0 END), 0) AS tong_doanh_thu
        FROM GiaoDich
        WHERE CuaHang_id = ? 
          AND DATE(ThoiGianTao) >= ? 
          AND DATE(ThoiGianTao) <= ?
    ");
    $stmt->execute([$shopId, $startDate, $endDate]);
    $kpi = $stmt->fetch();

    $tongDon = (int)$kpi['tong_don'];
    $donThanhCong = (int)$kpi['don_thanh_cong'];
    $tongDoanhThu = (int)$kpi['tong_doanh_thu'];
    $tyLeThanhCong = $tongDon > 0 ? round(($donThanhCong / $tongDon) * 100, 1) : 0;
    $giaTriTrungBinh = $donThanhCong > 0 ? round($tongDoanhThu / $donThanhCong) : 0;

    // 2. Thống kê theo ngày (Timeline)
    $stmt = $pdo->prepare("
        SELECT 
            DATE(ThoiGianTao) AS ngay,
            COUNT(*) AS tong_don,
            SUM(CASE WHEN TrangThai = 'SUCCESS' THEN 1 ELSE 0 END) AS thanh_cong,
            COALESCE(SUM(CASE WHEN TrangThai = 'SUCCESS' THEN SoTien ELSE 0 END), 0) AS doanh_thu
        FROM GiaoDich
        WHERE CuaHang_id = ? 
          AND DATE(ThoiGianTao) >= ? 
          AND DATE(ThoiGianTao) <= ?
        GROUP BY DATE(ThoiGianTao)
        ORDER BY ngay ASC
    ");
    $stmt->execute([$shopId, $startDate, $endDate]);
    $timelineRaw = $stmt->fetchAll();

    // Lập bản đồ ngày để điền đủ các ngày trống
    $timelineMap = [];
    foreach ($timelineRaw as $r) {
        $timelineMap[$r['ngay']] = $r;
    }

    $timeline = [];
    $current = strtotime($startDate);
    $end = strtotime($endDate);

    while ($current <= $end) {
        $d = date('Y-m-d', $current);
        $row = $timelineMap[$d] ?? null;
        $timeline[] = [
            'ngay'        => $d,
            'label'       => date('d/m', $current),
            'doanh_thu'   => $row ? (int)$row['doanh_thu'] : 0,
            'tong_don'    => $row ? (int)$row['tong_don'] : 0,
            'thanh_cong'  => $row ? (int)$row['thanh_cong'] : 0
        ];
        $current = strtotime('+1 day', $current);
    }

    // 3. Cơ cấu theo Cổng thanh toán (Gateways)
    $stmt = $pdo->prepare("
        SELECT 
            LoaiCongThanhToan AS loai_cong,
            COUNT(*) AS tong_don,
            SUM(CASE WHEN TrangThai = 'SUCCESS' THEN 1 ELSE 0 END) AS thanh_cong,
            COALESCE(SUM(CASE WHEN TrangThai = 'SUCCESS' THEN SoTien ELSE 0 END), 0) AS doanh_thu
        FROM GiaoDich
        WHERE CuaHang_id = ? 
          AND DATE(ThoiGianTao) >= ? 
          AND DATE(ThoiGianTao) <= ?
        GROUP BY LoaiCongThanhToan
    ");
    $stmt->execute([$shopId, $startDate, $endDate]);
    $gateways = $stmt->fetchAll();

    jsonResponse(true, 'OK', [
        'range'           => $range,
        'start_date'      => $startDate,
        'end_date'        => $endDate,
        'kpi'             => [
            'tong_doanh_thu'       => $tongDoanhThu,
            'don_thanh_cong'       => $donThanhCong,
            'don_dang_cho'         => (int)$kpi['don_dang_cho'],
            'don_that_bai'         => (int)$kpi['don_that_bai'],
            'tong_don'             => $tongDon,
            'ty_le_thanh_cong'     => $tyLeThanhCong,
            'gia_tri_trung_binh'   => $giaTriTrungBinh
        ],
        'timeline'        => $timeline,
        'gateways'        => $gateways
    ]);

} catch (Exception $e) {
    jsonResponse(false, 'Lỗi: ' . $e->getMessage(), [], 500);
}
