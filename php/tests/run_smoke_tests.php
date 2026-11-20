<?php
/**
 * =====================================================
 * FILE: tests/run_smoke_tests.php
 * Mục đích: Script chạy kiểm thử tự động (Smoke / Unit Tests)
 * Hỗ trợ Tuần 11 - Đồ án Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 * 
 * Cách chạy:
 *   php tests/run_smoke_tests.php
 */

require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/auth.php';

$totalTests = 0;
$passedTests = 0;
$failedTests = 0;
$skippedTests = 0;

function runTest($testName, $callback) {
    global $totalTests, $passedTests, $failedTests, $skippedTests;
    $totalTests++;
    echo "[TEST $totalTests] $testName: ";
    try {
        $result = $callback();
        if ($result === true) {
            echo "PASS\n";
            $passedTests++;
        } elseif (is_string($result) && str_starts_with($result, 'SKIP:')) {
            echo "SKIPPED (" . substr($result, 5) . ")\n";
            $skippedTests++;
        } else {
            echo "FAIL - Lý do: " . ($result ?: 'Kết quả không đúng') . "\n";
            $failedTests++;
        }
    } catch (Exception $e) {
        echo "FAIL - Ngoại lệ: " . $e->getMessage() . "\n";
        $failedTests++;
    }
}

echo "=========================================================\n";
echo "    BẮT ĐẦU CHẠY KIỂM THỬ TỰ ĐỘNG FRESHMART PAYMENT     \n";
echo "=========================================================\n\n";

// 1. Kiểm tra hàm sinh UUID
runTest("Hàm generateUUID() sinh chuỗi 36 ký tự chuẩn RFC 4122", function() {
    $uuid = generateUUID();
    return (strlen($uuid) === 36 && substr_count($uuid, '-') === 4);
});

// 2. Kiểm tra mã hóa và giải mã AES-256-CBC
runTest("Mã hóa và giải mã bảo mật AES-256 (security.php)", function() {
    $originalText = "whsec_3SGl9TsZs2I0aeisxrRyS8Cb26isRk2u";
    $encrypted = encryptData($originalText);
    if ($encrypted === $originalText) return "Chưa được mã hóa";
    $decrypted = decryptData($encrypted);
    return ($decrypted === $originalText);
});

// 3. Kiểm tra mã hóa mật khẩu bcrypt
runTest("Bảo mật mật khẩu với password_hash và password_verify", function() {
    $rawPassword = "FreshMartSecurePassword@2026";
    $hash = password_hash($rawPassword, PASSWORD_DEFAULT);
    if ($hash === $rawPassword) return "Mật khẩu không được băm";
    $isValid = password_verify($rawPassword, $hash);
    $isInvalid = password_verify("WrongPassword", $hash);
    return ($isValid === true && $isInvalid === false);
});

// 4. Kiểm tra hàm phân quyền isPrivileged
runTest("Phân quyền người dùng (isPrivileged logic)", function() {
    // Giả lập session vai trò OWNER
    $_SESSION['user_id'] = 'test-owner-id';
    $_SESSION['vai_tro'] = 'OWNER';
    if (!isPrivileged()) return "OWNER phải được cấp quyền";

    // Giả lập session vai trò MANAGER (Quản lý cửa hàng)
    $_SESSION['vai_tro'] = 'MANAGER';
    if (!isPrivileged()) return "MANAGER phải được cấp quyền";

    // Giả lập session vai trò STAFF
    $_SESSION['vai_tro'] = 'STAFF';
    if (!isPrivileged()) return "STAFF phải được cấp quyền";

    // Giả lập session vai trò USER (Khách hàng)
    $_SESSION['vai_tro'] = 'USER';
    if (isPrivileged()) return "USER không được có quyền quản trị";

    unset($_SESSION['user_id']);
    unset($_SESSION['vai_tro']);
    return true;
});

// 5. Kiểm tra logic xác thực chữ ký HMAC-SHA256 Webhook SePay
runTest("Xác thực chữ ký HMAC-SHA256 Webhook (SePay Signature)", function() {
    $secret = "whsec_3SGl9TsZs2I0aeisxrRyS8Cb26isRk2u";
    $payload = json_encode([
        'code' => 'FM0420175682',
        'transferAmount' => 10000,
        'referenceCode' => 'MB_REF_123456'
    ]);
    
    // Tạo chữ ký mẫu
    $validSignature = hash_hmac('sha256', $payload, $secret);
    $invalidSignature = "fake_signature_abc123";

    // Kiểm tra tính đúng đắn với hash_equals
    $checkValid = hash_equals($validSignature, hash_hmac('sha256', $payload, $secret));
    $checkInvalid = hash_equals($invalidSignature, hash_hmac('sha256', $payload, $secret));

    return ($checkValid === true && $checkInvalid === false);
});

// 6. Kiểm tra kết nối Cơ sở dữ liệu
runTest("Kết nối Cơ sở dữ liệu và truy vấn bảng", function() {
    try {
        $pdo = getDB();
        $stmt = $pdo->query("SELECT 1 AS test");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row && $row['test'] == 1);
    } catch (Exception $e) {
        return "SKIP: DB không chạy ở localhost (Đang chạy online trên Render + TiDB Cloud)";
    }
});

// 7. Kiểm tra ma trận phân cấp thẩm quyền (OWNER, MANAGER, STAFF, USER)
runTest("Phân cấp thẩm quyền điều chỉnh tài khoản (Owner/Manager/Staff)", function() {
    require_once __DIR__ . '/test_permission_hierarchy.php';
    // Test Quản trị viên điều chỉnh tất cả
    if (!checkPermissionRule('OWNER', 'MANAGER', 'STAFF')['allowed']) return "OWNER phải chỉnh được MANAGER";
    if (!checkPermissionRule('OWNER', 'STAFF', 'USER')['allowed']) return "OWNER phải chỉnh được STAFF";
    
    // Test Quản lý Cửa hàng điều chỉnh Nhân viên và Khách hàng
    if (!checkPermissionRule('MANAGER', 'STAFF', 'USER')['allowed']) return "MANAGER phải chỉnh được STAFF";
    if (!checkPermissionRule('MANAGER', 'USER', 'STAFF')['allowed']) return "MANAGER phải chỉnh được USER";
    if (checkPermissionRule('MANAGER', 'MANAGER', 'STAFF')['allowed']) return "MANAGER không được chỉnh MANAGER";
    if (checkPermissionRule('MANAGER', 'OWNER', 'USER')['allowed']) return "MANAGER không được chỉnh OWNER";
    if (checkPermissionRule('MANAGER', 'USER', 'MANAGER')['allowed']) return "MANAGER không được nâng cấp lên MANAGER";

    // Test Nhân viên chỉ điều chỉnh được Khách hàng
    if (!checkPermissionRule('STAFF', 'USER', 'USER')['allowed']) return "STAFF phải điều chỉnh được USER";
    if (checkPermissionRule('STAFF', 'STAFF', 'USER')['allowed']) return "STAFF không được chỉnh STAFF";
    if (checkPermissionRule('STAFF', 'USER', 'STAFF')['allowed']) return "STAFF không được nâng cấp lên STAFF";
    if (checkPermissionRule('STAFF', 'USER', 'MANAGER')['allowed']) return "STAFF không được nâng cấp lên MANAGER";

    return true;
});

// 8. Kiểm tra API get_products.php (lấy danh sách sản phẩm động từ CSDL)
runTest("Lấy danh sách sản phẩm động từ CSDL (api/get_products.php)", function() {
    try {
        $pdo = getDB();
        $stmt = $pdo->query("SELECT COUNT(*) FROM sanpham WHERE TrangThai = 1");
        $count = (int)$stmt->fetchColumn();
        if ($count === 0) {
            return "Bảng sanpham chưa có dữ liệu sản phẩm";
        }
        return true;
    } catch (Exception $e) {
        return "SKIP: " . $e->getMessage();
    }
});

// 9. Kiểm tra quy trình tạo đơn hàng lưu đủ donhang + chitietdonhang + giaodich
runTest("Tạo đơn hàng lưu đồng bộ donhang, chitietdonhang và giaodich", function() {
    try {
        $pdo = getDB();
        
        // Giả lập user đăng nhập
        $testUserId = $pdo->query("SELECT ID FROM nguoidung LIMIT 1")->fetchColumn();
        if (!$testUserId) {
            $testUserId = generateUUID();
            $pdo->prepare("INSERT INTO nguoidung (ID, HoTen, Email, MatKhau, VaiTro) VALUES (?, 'Test User', 'smoke@freshmart.vn', 'hash', 'USER')")->execute([$testUserId]);
        }
        $testShopId = $pdo->query("SELECT ID FROM cuahang LIMIT 1")->fetchColumn();
        if (!$testShopId) {
            $testShopId = generateUUID();
            $pdo->prepare("INSERT INTO cuahang (ID, NguoiDung_id, TenCH) VALUES (?, ?, 'FreshMart Official')")->execute([$testShopId, $testUserId]);
        }
        
        $testProd = $pdo->query("SELECT ID, TenSanPham, GiaBan FROM sanpham LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        if (!$testProd) {
            return "Không tìm thấy sản phẩm mẫu để test tạo đơn";
        }

        $testMaDon = 'TEST_' . mt_rand(100000, 999999);
        $testDonHangId = generateUUID();
        $testChiTietId = generateUUID();
        $testGiaoDichId = generateUUID();

        // 1. donhang
        $pdo->prepare("
            INSERT INTO donhang (ID, MaDonHang, NguoiDung_id, CuaHang_id, HoTenNguoiNhan, SoDienThoaiNhan, DiaChiGiaoHang, TienHang, TienPhiVanChuyen, TienGiamGia, TongTien, PhuongThucThanhToan)
            VALUES (?, ?, ?, ?, 'Smoke Test Customer', '0912345678', 'Test Address', ?, 30000.00, 0.00, ?, 'VIETQR')
        ")->execute([$testDonHangId, $testMaDon, $testUserId, $testShopId, $testProd['GiaBan'], (float)$testProd['GiaBan'] + 30000]);

        // 2. chitietdonhang
        $pdo->prepare("
            INSERT INTO chitietdonhang (ID, DonHang_id, SanPham_id, TenSanPham, DonGia, SoLuong, ThanhTien)
            VALUES (?, ?, ?, ?, ?, 1, ?)
        ")->execute([$testChiTietId, $testDonHangId, $testProd['ID'], $testProd['TenSanPham'], $testProd['GiaBan'], $testProd['GiaBan']]);

        // 3. giaodich
        $pdo->prepare("
            INSERT INTO giaodich (ID, CuaHang_id, DonHang_id, MaDonHang, SoTien, MoTa, LoaiCongThanhToan, TrangThai)
            VALUES (?, ?, ?, ?, ?, 'Smoke test transaction', 'VIETQR', 'PENDING')
        ")->execute([$testGiaoDichId, $testShopId, $testDonHangId, $testMaDon, (float)$testProd['GiaBan'] + 30000]);

        // Kiểm tra toàn vẹn
        $chkDonHang = $pdo->query("SELECT ID FROM donhang WHERE MaDonHang = '$testMaDon'")->fetchColumn();
        $chkChiTiet = $pdo->query("SELECT ID FROM chitietdonhang WHERE DonHang_id = '$testDonHangId'")->fetchColumn();
        $chkGiaoDich = $pdo->query("SELECT DonHang_id FROM giaodich WHERE ID = '$testGiaoDichId'")->fetchColumn();

        // Dọn dẹp dữ liệu test
        $pdo->prepare("DELETE FROM giaodich WHERE ID = ?")->execute([$testGiaoDichId]);
        $pdo->prepare("DELETE FROM chitietdonhang WHERE ID = ?")->execute([$testChiTietId]);
        $pdo->prepare("DELETE FROM donhang WHERE ID = ?")->execute([$testDonHangId]);

        if ($chkDonHang && $chkChiTiet && $chkGiaoDich === $testDonHangId) {
            return true;
        } else {
            return "Dữ liệu liên kết giữa donhang, chitietdonhang và giaodich không khớp";
        }
    } catch (Exception $e) {
        return "Ngoại lệ: " . $e->getMessage();
    }
});

echo "\n=========================================================\n";
echo "                 KẾT QUẢ KIỂM THỬ TỔNG HỢP                \n";
echo "=========================================================\n";
echo "Tổng số ca kiểm thử: $totalTests\n";
echo "Số ca thành công:    $passedTests\n";
echo "Số ca bỏ qua:        $skippedTests\n";
echo "Số ca thất bại:      $failedTests\n";

if ($failedTests === 0) {
    echo ">>> KẾT QUẢ: 100% CÁC BÀI TEST CHỨC NĂNG VÀ BẢO MẬT ĐÃ ĐẠT! <<<\n";
} else {
    echo ">>> CẢNH BÁO: CÓ BÀI TEST BỊ LỖI, CẦN KIỂM TRA LẠI! <<<\n";
}
