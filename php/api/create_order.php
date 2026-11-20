<?php
/**
 * =====================================================
 * FILE: api/create_order.php
 * Mục đích: Tạo đơn hàng từ trang Checkout (lưu đầy đủ donhang, chitietdonhang, giaodich)
 * Tuần 8-11 - Hệ thống thanh toán FreshMart
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
$userId = $user['id'] ?? ($_SESSION['user_id'] ?? null);

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$soTien      = (float)($input['so_tien'] ?? 297000);
$phuongThuc  = strtoupper(trim($input['phuong_thuc'] ?? 'VIETQR'));
$hoTen       = trim($input['ho_ten'] ?? ($user['ho_ten'] ?? 'Khách hàng'));
$sdt         = trim($input['so_dien_thoai'] ?? '');
$diaChi      = trim($input['dia_chi'] ?? '');
$ghiChu      = trim($input['ghi_chu'] ?? '');
$items       = $input['items'] ?? [];

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
    $pdo->beginTransaction();

    // 2. Xác thực tài khoản người dùng trong CSDL
    $stmtUserCheck = $pdo->prepare("SELECT ID FROM nguoidung WHERE ID = ?");
    $stmtUserCheck->execute([$userId]);
    if (!$stmtUserCheck->fetch()) {
        // Fallback: Lấy user đầu tiên trong CSDL hoặc tạo bản ghi
        $firstUserId = $pdo->query("SELECT ID FROM nguoidung ORDER BY ThoiGianTao ASC LIMIT 1")->fetchColumn();
        if ($firstUserId) {
            $userId = $firstUserId;
        } else {
            $userId = generateUUID();
            $pdo->prepare("INSERT INTO nguoidung (ID, HoTen, Email, MatKhau, VaiTro) VALUES (?, ?, ?, ?, 'USER')")
                ->execute([$userId, $hoTen, 'guest_' . time() . '@freshmart.vn', password_hash('123456', PASSWORD_DEFAULT)]);
        }
    }

    // 3. Liên kết đơn hàng với Cửa hàng chính của hệ thống (FreshMart)
    $stmtShop = $pdo->query("SELECT ID FROM cuahang ORDER BY ThoiGianTao ASC LIMIT 1");
    $shopRow = $stmtShop->fetch();
    if ($shopRow) {
        $shopId = $shopRow['ID'];
    } else {
        $shopId = $user['shop_id'] ?? generateUUID();
        $pdo->prepare("INSERT INTO cuahang (ID, NguoiDung_id, TenCH, MoTaCH) VALUES (?, ?, 'FreshMart Official', 'Cửa hàng thực phẩm tươi sạch')")
            ->execute([$shopId, $userId]);
    }

    // 4. Tính toán tiền hàng, tiền ship, tiền giảm
    $tienHang = 0;
    if (is_array($items) && count($items) > 0) {
        foreach ($items as $it) {
            $qty = max(1, (int)($it['qty'] ?? 1));
            $price = (float)($it['price'] ?? 0);
            $tienHang += ($price * $qty);
        }
    }
    if ($tienHang <= 0) {
        $tienHang = $soTien;
    }
    $tienShip = 30000.00;
    $tienGiam = max(0, ($tienHang + $tienShip) - $soTien);
    $tongTien = $soTien;

    $diaChiGiaoHang = $diaChi;
    if (!empty($ghiChu)) {
        $diaChiGiaoHang .= " (Ghi chú: {$ghiChu})";
    }

    // BƯỚC 1: LƯU VÀO BẢNG donhang
    $donHangId = generateUUID();
    $stmtDonHang = $pdo->prepare("
        INSERT INTO donhang (
            ID, MaDonHang, NguoiDung_id, CuaHang_id,
            HoTenNguoiNhan, SoDienThoaiNhan, DiaChiGiaoHang,
            TienHang, TienPhiVanChuyen, TienGiamGia, TongTien,
            PhuongThucThanhToan, TrangThaiDonHang, TrangThaiThanhToan, NgayDat
        ) VALUES (
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, 'CHO_XAC_NHAN', 'CHUA_THANH_TOAN', NOW()
        )
    ");
    $stmtDonHang->execute([
        $donHangId,
        $maDonHang,
        $userId,
        $shopId,
        $hoTen,
        $sdt ?: '0985670805',
        $diaChiGiaoHang ?: 'FreshMart Store, Hà Nội',
        $tienHang,
        $tienShip,
        $tienGiam,
        $tongTien,
        $phuongThuc
    ]);

    // BƯỚC 2: DUYỆT SẢN PHẨM VÀ LƯU VÀO BẢNG chitietdonhang
    if (empty($items) || !is_array($items)) {
        $items = [
            [
                'name'  => 'Giỏ hàng thực phẩm FreshMart',
                'price' => $tongTien,
                'qty'   => 1,
                'image' => 'img/ca-chua-bi.jpg'
            ]
        ];
    }

    foreach ($items as $it) {
        $itemName  = trim($it['name'] ?? 'Sản phẩm FreshMart');
        $itemPrice = (float)($it['price'] ?? 0);
        $itemQty   = max(1, (int)($it['qty'] ?? 1));
        $thanhTien = $itemPrice * $itemQty;
        $sanPhamId = null;

        // Tìm sản phẩm trong DB theo ID
        if (!empty($it['id'])) {
            $stmtFind = $pdo->prepare("SELECT ID FROM sanpham WHERE ID = ? LIMIT 1");
            $stmtFind->execute([$it['id']]);
            $sanPhamId = $stmtFind->fetchColumn();
        }

        // Nếu chưa thấy, tìm theo tên sản phẩm
        if (!$sanPhamId) {
            $stmtFindName = $pdo->prepare("SELECT ID FROM sanpham WHERE TenSanPham = ? LIMIT 1");
            $stmtFindName->execute([$itemName]);
            $sanPhamId = $stmtFindName->fetchColumn();
        }

        // Nếu sản phẩm chưa có trong DB, tự động thêm vào sanpham để đảm bảo toàn vẹn khóa ngoại
        if (!$sanPhamId) {
            $catId = $pdo->query("SELECT ID FROM danhmuc LIMIT 1")->fetchColumn();
            if (!$catId) {
                $catId = generateUUID();
                $pdo->prepare("INSERT INTO danhmuc (ID, TenDanhMuc, Slug) VALUES (?, 'Rau củ quả', 'rau-cu-qua')")
                    ->execute([$catId]);
            }

            $sanPhamId = (!empty($it['id']) && strlen($it['id']) === 36) ? $it['id'] : generateUUID();
            $slug = 'sp-' . time() . '-' . mt_rand(100, 999);
            $img = $it['image'] ?? 'img/ca-chua-bi.jpg';

            $pdo->prepare("
                INSERT INTO sanpham (ID, DanhMuc_id, CuaHang_id, TenSanPham, Slug, GiaGoc, GiaBan, DonViTinh, SoLuongTon, HinhAnh, TieuChuan)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'kg', 100, ?, 'VietGAP')
            ")->execute([
                $sanPhamId,
                $catId,
                $shopId,
                $itemName,
                $slug,
                $itemPrice,
                $itemPrice,
                $img
            ]);
        }

        // INSERT vào chitietdonhang
        $chiTietId = generateUUID();
        $stmtDetail = $pdo->prepare("
            INSERT INTO chitietdonhang (ID, DonHang_id, SanPham_id, TenSanPham, DonGia, SoLuong, ThanhTien)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtDetail->execute([
            $chiTietId,
            $donHangId,
            $sanPhamId,
            $itemName,
            $itemPrice,
            $itemQty,
            $thanhTien
        ]);
    }

    // 5. Cấu hình cổng VietQR
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
        ], $tongTien, $maDonHang, "Thanh toan don $maDonHang");

        if ($adapterResult['success']) {
            $qrData = $adapterResult['qr_data'];
        }

        // Link ảnh VietQR chuẩn NAPAS
        $qrImage = "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.png?amount={$tongTien}&addInfo={$maDonHang}&accountName=" . urlencode($accountName);
    }

    // BƯỚC 3: LƯU GIAO DỊCH VÀO BẢNG giaodich (KÈM DonHang_id)
    $giaoDichId = generateUUID();
    $moTa = "Đơn {$maDonHang} - KH: {$hoTen}";
    if (!empty($sdt)) $moTa .= " ({$sdt})";
    if (!empty($diaChi)) $moTa .= " - ĐC: {$diaChi}";
    $moTa = mb_substr($moTa, 0, 250);

    $loaiCongDB = ($phuongThuc === 'BANK') ? 'VIETQR' : $phuongThuc;

    $stmtGiaoDich = $pdo->prepare("
        INSERT INTO giaodich (
            ID, CuaHang_id, CongThanhToan_id, DonHang_id, MaDonHang,
            SoTien, MoTa, LoaiCongThanhToan, TrangThai,
            DuLieuQR, DuongDanThanhToan, ThoiGianTao
        ) VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, 'PENDING',
            ?, ?, NOW()
        )
    ");
    $stmtGiaoDich->execute([
        $giaoDichId,
        $shopId,
        $gatewayId,
        $donHangId,
        $maDonHang,
        $tongTien,
        $moTa,
        $loaiCongDB,
        $qrData,
        $qrImage
    ]);

    // Hoàn tất giao dịch cơ sở dữ liệu
    $pdo->commit();

    jsonResponse(true, 'Tạo đơn hàng thành công', [
        'giao_dich_id'    => $giaoDichId,
        'don_hang_id'     => $donHangId,
        'ma_don_hang'     => $maDonHang,
        'so_tien'         => $tongTien,
        'phuong_thuc'     => $phuongThuc,
        'trang_thai'      => 'PENDING',
        'bank_code'       => $bankCode,
        'account_no'      => $accountNo,
        'account_name'    => $accountName,
        'qr_image'        => $qrImage,
        'qr_data'         => $qrData,
        'redirect_url'    => ($phuongThuc === 'VIETQR' || $phuongThuc === 'BANK')
                              ? "payment_qr.html?order_id={$maDonHang}&amount={$tongTien}"
                              : "index.html"
    ]);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(false, 'Lỗi hệ thống khi tạo đơn hàng: ' . $e->getMessage(), [], 500);
}
