<?php
/**
 * =====================================================
 * FILE: api/create_payment.php
 * Mục đích: Tạo yêu cầu thanh toán + sinh mã QR / link
 * Tuần 8 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * Method: POST
 * Yêu cầu: Đã đăng nhập (Owner hoặc Staff)
 *
 * Dữ liệu nhận vào:
 *   - so_tien          (bắt buộc) Số tiền VND
 *   - ma_don_hang      (bắt buộc) Mã đơn hàng do shop tự tạo
 *   - mo_ta            (tùy chọn) Mô tả
 *   - loai_cong        (bắt buộc) VIETQR | MOMO | VNPAY | ZALOPAY
 *
 * Luồng xử lý:
 * 1. Kiểm tra đăng nhập
 * 2. Validate dữ liệu đầu vào
 * 3. Lấy cấu hình cổng thanh toán của shop từ DB
 * 4. Gọi Adapter tương ứng để tạo QR / payment link
 * 5. Lưu giao dịch vào bảng GiaoDich với trạng thái PENDING
 * 6. Trả về QR data hoặc link cho frontend hiển thị
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/adapters/VietQRAdapter.php';

// -----------------------------------------------------
// 1. KIỂM TRA ĐĂNG NHẬP
// Staff cũng được tạo thanh toán, chỉ Owner mới cấu hình cổng
// -----------------------------------------------------
if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập để tạo thanh toán', [], 401);
}

$user = getCurrentUser();
$pdo = getDB();
$shopId = $user['shop_id'] ?? null;

if (empty($shopId)) {
    $stmtShop = $pdo->query("SELECT ID FROM cuahang ORDER BY ThoiGianTao ASC LIMIT 1");
    $shopId = $stmtShop->fetchColumn();
}

// -----------------------------------------------------
// 2. NHẬN VÀ VALIDATE DỮ LIỆU
// -----------------------------------------------------
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$soTien     = (int)($input['so_tien'] ?? 0);
$maDonHang  = trim($input['ma_don_hang'] ?? '');
$moTa       = trim($input['mo_ta'] ?? '');
$loaiCong   = strtoupper(trim($input['loai_cong'] ?? 'VIETQR'));

$errors = [];
if ($soTien <= 0) {
    $errors[] = 'Số tiền phải lớn hơn 0';
}
if (empty($maDonHang)) {
    $errors[] = 'Mã đơn hàng không được để trống';
}
if (!in_array($loaiCong, ['VIETQR', 'MOMO', 'VNPAY', 'ZALOPAY'])) {
    $errors[] = 'Loại cổng thanh toán không hợp lệ';
}

if (!empty($errors)) {
    jsonResponse(false, implode('. ', $errors), [], 400);
}

// -----------------------------------------------------
// 3. XỬ LÝ TẠO THANH TOÁN
// -----------------------------------------------------
try {
    $pdo = getDB();

    // 3.1. Kiểm tra mã đơn hàng đã tồn tại chưa (trong cùng shop)
    $stmt = $pdo->prepare("SELECT ID FROM giaodich WHERE CuaHang_id = ? AND MaDonHang = ?");
    $stmt->execute([$shopId, $maDonHang]);
    if ($stmt->fetch()) {
        jsonResponse(false, 'Mã đơn hàng này đã tồn tại. Vui lòng dùng mã khác.', [], 409);
    }

    // 3.2. Lấy cấu hình cổng thanh toán của shop
    $stmt = $pdo->prepare("
        SELECT * FROM congthanhtoan 
        WHERE CuaHang_id = ? 
          AND LoaiCongThanhToan = ? 
          AND TrangThaiHoatDong = 1
        LIMIT 1
    ");
    $stmt->execute([$shopId, $loaiCong]);
    $gateway = $stmt->fetch();

    if (!$gateway) {
        jsonResponse(false, "Bạn chưa cấu hình cổng $loaiCong hoặc cổng đang bị tắt. Vui lòng vào trang Cấu hình cổng.", [], 400);
    }

    // 3.3. Gọi Adapter tương ứng
    $qrData = '';
    $paymentUrl = '';
    $adapterResult = null;

    switch ($loaiCong) {
        case 'VIETQR':
            $adapter = new VietQRAdapter();
            $adapterResult = $adapter->createPayment($gateway, $soTien, $maDonHang, $moTa);
            
            if (!$adapterResult['success']) {
                jsonResponse(false, $adapterResult['message'], [], 400);
            }
            $qrData = $adapterResult['qr_data'];
            break;

        case 'MOMO':
        case 'VNPAY':
        case 'ZALOPAY':
            // Sẽ implement ở phần sau (Tuần 8 mở rộng)
            jsonResponse(false, "Cổng $loaiCong đang được phát triển. Hiện tại vui lòng dùng VietQR.", [], 501);
            break;

        default:
            jsonResponse(false, 'Cổng thanh toán không được hỗ trợ', [], 400);
    }

    // 3.4. Lưu giao dịch vào database với trạng thái PENDING
    $giaoDichId = generateUUID();

    $stmt = $pdo->prepare("
        INSERT INTO giaodich (
            ID, CuaHang_id, CongThanhToan_id, MaDonHang, 
            SoTien, MoTa, LoaiCongThanhToan, TrangThai, 
            DuLieuQR, DuongDanThanhToan
        ) VALUES (
            ?, ?, ?, ?, 
            ?, ?, ?, 'PENDING', 
            ?, ?
        )
    ");
    $stmt->execute([
        $giaoDichId,
        $shopId,
        $gateway['ID'],
        $maDonHang,
        $soTien,
        $moTa,
        $loaiCong,
        $qrData,
        $paymentUrl
    ]);

    // 3.5. Trả về kết quả cho frontend
    jsonResponse(true, 'Tạo yêu cầu thanh toán thành công', [
        'giao_dich_id'   => $giaoDichId,
        'ma_don_hang'    => $maDonHang,
        'so_tien'        => $soTien,
        'loai_cong'      => $loaiCong,
        'trang_thai'     => 'PENDING',
        'qr_data'        => $qrData,          // Chuỗi để vẽ QR code
        'payment_url'    => $paymentUrl,      // Link thanh toán (nếu có)
        'adapter_info'   => $adapterResult    // Thông tin thêm từ adapter (debug)
    ]);

} catch (Exception $e) {
    // error_log($e->getMessage());
    jsonResponse(false, 'Lỗi hệ thống khi tạo thanh toán: ' . $e->getMessage(), [], 500);
}
