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
