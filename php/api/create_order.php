<?php
/**
 * =====================================================
 * FILE: api/create_order.php
 * Mục đích: Tạo đơn hàng từ trang Checkout (hỗ trợ VietQR, COD, MoMo, Card)
 * Tuần 8-9 - Hệ thống thanh toán FreshMart
 * =====================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../csdl/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/adapters/VietQRAdapter.php';

// 1. Kiểm tra đăng nhập
if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập để đặt hàng', [], 401);
}

$user = getCurrentUser();
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$soTien      = (int)($input['so_tien'] ?? 297000);
$phuongThuc  = strtoupper(trim($input['phuong_thuc'] ?? 'VIETQR'));
$hoTen       = trim($input['ho_ten'] ?? ($user['ho_ten'] ?? 'Khách hàng'));
$sdt         = trim($input['so_dien_thoai'] ?? '');
$diaChi      = trim($input['dia_chi'] ?? '');
$ghiChu      = trim($input['ghi_chu'] ?? '');

if ($soTien <= 0) {
    $soTien = 297000;
}

// Sinh mã đơn hàng nhận diện: FM + 6 số (ví dụ FM583921)
$maDonHang = trim($input['ma_don_hang'] ?? '');
if (empty($maDonHang)) {
    $maDonHang = 'FM' . mt_rand(100000, 999999);
}

try {
    $pdo = getDB();

    // 2. Tìm hoặc liên kết Cửa hàng
    $shopId = $user['shop_id'] ?? null;
    if (empty($shopId)) {
        $stmtShop = $pdo->query("SELECT ID FROM cuahang ORDER BY ThoiGianTao ASC LIMIT 1");
        $shopRow = $stmtShop->fetch();
        if ($shopRow) {
            $shopId = $shopRow['ID'];
        } else {
            $shopId = generateUUID();
            $pdo->prepare("INSERT INTO cuahang (ID, NguoiDung_id, TenCH, MoTaCH) VALUES (?, ?, 'FreshMart Official', 'Cửa hàng thực phẩm tươi sạch')")
                ->execute([$shopId, $user['user_id']]);
        }
    }

    // 3. Cấu hình cổng VietQR
    $qrData      = '';
    $qrImage     = '';
    $bankCode    = 'MB';
    $bankBin     = '970422';
    $accountNo   = '0389336829';
    $accountName = 'FRESHMART STORE';
    $gatewayId   = null;

    if ($phuongThuc === 'VIETQR' || $phuongThuc === 'BANK') {
        $stmtGw = $pdo->prepare("SELECT * FROM congthanhtoan WHERE LoaiCongThanhToan = 'VIETQR' AND TrangThaiHoatDong = 1 ORDER BY ThoiGianTao DESC LIMIT 1");
        $stmtGw->execute();
        $gw = $stmtGw->fetch();

        if ($gw) {
            $gatewayId = $gw['ID'];
            if (!empty($gw['MaNguoiBan'])) $accountNo = $gw['MaNguoiBan'];
            $extra = !empty($gw['CauHinhThem']) ? (is_string($gw['CauHinhThem']) ? json_decode($gw['CauHinhThem'], true) : $gw['CauHinhThem']) : [];
            if (!empty($extra['bank_code'])) $bankCode = strtoupper($extra['bank_code']);
            if (!empty($extra['bank_bin'])) $bankBin = $extra['bank_bin'];
            if (!empty($extra['account_name'])) $accountName = $extra['account_name'];
        }

        // Gọi VietQRAdapter
        $adapter = new VietQRAdapter();
        $adapterResult = $adapter->createPayment([
            'MaNguoiBan'  => $accountNo,
            'CauHinhThem' => [
                'bank_code'    => $bankCode,
                'bank_bin'     => $bankBin,
                'account_name' => $accountName
            ]
        ], $soTien, $maDonHang, "Thanh toan don $maDonHang");

        if ($adapterResult['success']) {
            $qrData = $adapterResult['qr_data'];
        }

        // Link ảnh VietQR chuẩn NAPAS
        $qrImage = "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.png?amount={$soTien}&addInfo={$maDonHang}&accountName=" . urlencode($accountName);
    }

    // 4. Lưu giao dịch vào bảng giaodich với trạng thái PENDING
    $giaoDichId = generateUUID();
    $moTa = "Đơn {$maDonHang} - KH: {$hoTen}";
    if (!empty($sdt)) $moTa .= " ({$sdt})";
    if (!empty($diaChi)) $moTa .= " - ĐC: {$diaChi}";
    $moTa = mb_substr($moTa, 0, 250);

    $loaiCongDB = ($phuongThuc === 'BANK') ? 'VIETQR' : $phuongThuc;

    $stmt = $pdo->prepare("
        INSERT INTO giaodich (
            ID, CuaHang_id, CongThanhToan_id, MaDonHang,
            SoTien, MoTa, LoaiCongThanhToan, TrangThai,
            DuLieuQR, DuongDanThanhToan, ThoiGianTao
        ) VALUES (
            ?, ?, ?, ?,
            ?, ?, ?, 'PENDING',
            ?, ?, NOW()
        )
    ");
    $stmt->execute([
        $giaoDichId,
        $shopId,
        $gatewayId,
        $maDonHang,
        $soTien,
        $moTa,
        $loaiCongDB,
        $qrData,
        $qrImage
    ]);

    jsonResponse(true, 'Tạo đơn hàng thành công', [
        'giao_dich_id'    => $giaoDichId,
        'ma_don_hang'     => $maDonHang,
        'so_tien'         => $soTien,
        'phuong_thuc'     => $phuongThuc,
        'trang_thai'      => 'PENDING',
        'bank_code'       => $bankCode,
        'account_no'      => $accountNo,
        'account_name'    => $accountName,
        'qr_image'        => $qrImage,
        'qr_data'         => $qrData,
        'redirect_url'    => ($phuongThuc === 'VIETQR' || $phuongThuc === 'BANK')
                              ? "payment_qr.html?order_id={$maDonHang}&amount={$soTien}"
                              : "index.html"
    ]);

} catch (Exception $e) {
    jsonResponse(false, 'Lỗi hệ thống khi tạo đơn hàng: ' . $e->getMessage(), [], 500);
}
