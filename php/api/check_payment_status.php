<?php
/**
 * =====================================================
 * FILE: api/check_payment_status.php
 * Mục đích: Lấy thông tin thanh toán VietQR từ CSDL (bảng congthanhtoan & giaodich)
 *           và kiểm tra trạng thái thanh toán realtime
 * =====================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/../../csdl/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/adapters/VietQRAdapter.php';

$orderId = trim($_GET['order_id'] ?? '');

if (empty($orderId)) {
    jsonResponse(false, 'Thiếu mã đơn hàng (order_id)', [], 400);
}

// Bảng tên đầy đủ các ngân hàng phổ biến tại Việt Nam
$bankNames = [
    'VCB'   => 'Vietcombank (Ngân hàng Ngoại thương Việt Nam)',
    'TCB'   => 'Techcombank (Ngân hàng Kỹ thương Việt Nam)',
    'MB'    => 'MB Bank (Ngân hàng TMCP Quân Đội)',
    'ACB'   => 'ACB (Ngân hàng Á Châu)',
    'BIDV'  => 'BIDV (Ngân hàng Đầu tư và Phát triển VN)',
    'VPB'   => 'VPBank (Ngân hàng Việt Nam Thịnh Vượng)',
    'TPB'   => 'TPBank (Ngân hàng Tiên Phong)',
    'STB'   => 'Sacombank (Ngân hàng Sài Gòn Thương Tín)',
    'VIB'   => 'VIB (Ngân hàng Quốc tế Việt Nam)',
    'MSB'   => 'MSB (Ngân hàng Hàng Hải Việt Nam)',
    'VBA'   => 'Agribank (Ngân hàng Nông nghiệp & PTNT)',
    'SHB'   => 'SHB (Ngân hàng Sài Gòn - Hà Nội)',
    'OCB'   => 'OCB (Ngân hàng Phương Đông)',
    'HDB'   => 'HDBank (Ngân hàng Phát triển TP.HCM)',
    'LPB'   => 'LPBank (Ngân hàng Bưu điện Liên Việt)',
    'SEAB'  => 'SeABank (Ngân hàng Đông Nam Á)',
    'SCB'   => 'SCB (Ngân hàng Sài Gòn)'
];

try {
    $pdo = getDB();

    // 1. Tìm thông tin giao dịch trong bảng giaodich
    $stmt = $pdo->prepare("
        SELECT 
            ID, CuaHang_id, CongThanhToan_id, MaDonHang, SoTien, 
            MoTa, LoaiCongThanhToan, TrangThai, DuLieuQR, DuongDanThanhToan,
            MaGiaoDichCongThanhToan, ThoiGianThanhToan, ThoiGianTao
        FROM giaodich
        WHERE MaDonHang = ?
        ORDER BY ThoiGianTao DESC
        LIMIT 1
    ");
    $stmt->execute([$orderId]);
    $giaoDich = $stmt->fetch();

    $soTien = $giaoDich ? (int)$giaoDich['SoTien'] : (int)($_GET['amount'] ?? 297000);
    $trangThai = $giaoDich ? $giaoDich['TrangThai'] : 'PENDING';
    $shopId = $giaoDich ? $giaoDich['CuaHang_id'] : null;
    $gwId = $giaoDich ? $giaoDich['CongThanhToan_id'] : null;

    // 2. Lấy cấu hình cổng thanh toán VietQR TỪ CSDL (bảng congthanhtoan)
    $gateway = null;
    if ($gwId) {
        $stmtGw = $pdo->prepare("SELECT * FROM congthanhtoan WHERE ID = ?");
        $stmtGw->execute([$gwId]);
        $gateway = $stmtGw->fetch();
    }

    if (!$gateway) {
        // Lấy cổng VietQR đang hoạt động mới nhất trong CSDL
        if ($shopId) {
            $stmtGw = $pdo->prepare("
                SELECT * FROM congthanhtoan 
                WHERE CuaHang_id = ? AND LoaiCongThanhToan = 'VIETQR' AND TrangThaiHoatDong = 1 
                ORDER BY ThoiGianTao DESC LIMIT 1
            ");
            $stmtGw->execute([$shopId]);
            $gateway = $stmtGw->fetch();
        }
        
        if (!$gateway) {
            $stmtGw = $pdo->query("
                SELECT * FROM congthanhtoan 
                WHERE LoaiCongThanhToan = 'VIETQR' AND TrangThaiHoatDong = 1 
                ORDER BY ThoiGianTao DESC LIMIT 1
            ");
            $gateway = $stmtGw->fetch();
        }
    }

    // 3. Trích xuất thông tin tài khoản ngân hàng từ cấu hình CSDL
    $accountNo = '0389336829';
    $bankCode = 'MB';
    $accountName = 'FRESHMART STORE';
    $bankBin = '970422';

    if ($gateway) {
        if (!empty($gateway['MaNguoiBan'])) {
            $accountNo = trim($gateway['MaNguoiBan']);
        }
        $extra = !empty($gateway['CauHinhThem']) 
            ? (is_string($gateway['CauHinhThem']) ? json_decode($gateway['CauHinhThem'], true) : $gateway['CauHinhThem'])
            : [];

        if (!empty($extra['bank_code'])) {
            $bankCode = strtoupper(trim($extra['bank_code']));
        }
        if (!empty($extra['account_name'])) {
            $accountName = trim($extra['account_name']);
        }
        if (!empty($extra['bank_bin'])) {
            $bankBin = trim($extra['bank_bin']);
        }
    }

    $bankFullName = $bankNames[$bankCode] ?? "$bankCode Bank";

    // 4. Sinh link ảnh VietQR chuẩn NAPAS dựa trên dữ liệu CSDL
    $qrImage = "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.png?amount={$soTien}&addInfo={$orderId}&accountName=" . urlencode($accountName);

    $isPaid = ($trangThai === 'SUCCESS');

    jsonResponse(true, 'Lấy thông tin thanh toán thành công', [
        'giao_dich_id'           => $giaoDich ? $giaoDich['ID'] : null,
        'ma_don_hang'            => $orderId,
        'so_tien'                => $soTien,
        'loai_cong'              => 'VIETQR',
        'trang_thai'             => $trangThai,
        'is_paid'                => $isPaid,
        'bank_code'              => $bankCode,
        'bank_name'              => $bankFullName,
        'bank_bin'               => $bankBin,
        'account_no'             => $accountNo,
        'account_name'           => $accountName,
        'qr_image'               => $qrImage,
        'qr_data'                => $giaoDich['DuLieuQR'] ?? '',
        'thoi_gian_thanh_toan'   => $giaoDich['ThoiGianThanhToan'] ?? null,
        'ma_giao_dich_cong'      => ($giaoDich && !empty($giaoDich['MaGiaoDichCongThanhToan'])) 
                                    ? $giaoDich['MaGiaoDichCongThanhToan'] 
                                    : ('WH-' . substr(md5($orderId), 0, 10)),
        'thoi_gian_tao'          => $giaoDich['ThoiGianTao'] ?? null
    ]);

} catch (Exception $e) {
    jsonResponse(false, 'Lỗi hệ thống: ' . $e->getMessage(), [], 500);
}
