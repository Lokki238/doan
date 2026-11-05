<?php
/**
 * =====================================================
 * FILE: api/register.php
 * Mục đích: API Đăng ký tài khoản mới + tự động tạo Cửa hàng
 * Tuần 7 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * Method: POST
 * Dữ liệu nhận vào:
 *   - ho_ten          (bắt buộc)
 *   - email           (bắt buộc)
 *   - mat_khau        (bắt buộc, tối thiểu 6 ký tự)
 *   - so_dien_thoai   (tùy chọn)
 *   - ten_cua_hang    (tùy chọn, nếu trống sẽ tự tạo)
 *
 * Luồng xử lý:
 * 1. Nhận dữ liệu từ form hoặc JSON
 * 2. Validate dữ liệu
 * 3. Kiểm tra email đã tồn tại chưa
 * 4. Tạo UUID cho User và Shop
 * 5. Mã hóa mật khẩu bằng password_hash()
 * 6. Dùng Transaction để thêm cả 2 bảng (NguoiDung + CuaHang)
 * 7. Tự động đăng nhập (lưu Session)
 * 8. Trả về JSON thành công
 */

// Cho phép gọi API từ frontend (CORS đơn giản)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Nạp các hàm hỗ trợ (auth + db)
require_once __DIR__ . '/../includes/auth.php';

// Chỉ cho phép phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Chỉ hỗ trợ phương thức POST', [], 405);
}

// -----------------------------------------------------
// 1. NHẬN DỮ LIỆU
// Hỗ trợ cả 2 cách gửi:
//   - FormData (từ thẻ <form>) → nằm trong $_POST
//   - JSON (từ fetch với Content-Type: application/json)
// -----------------------------------------------------
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST; // Fallback về form thông thường
}

$hoTen       = trim($input['ho_ten'] ?? '');
$email       = trim($input['email'] ?? '');
$matKhau     = $input['mat_khau'] ?? '';
$soDienThoai = trim($input['so_dien_thoai'] ?? '');
$tenCuaHang  = trim($input['ten_cua_hang'] ?? '');
$vaiTroInput = strtoupper(trim($input['vai_tro'] ?? ''));

// Mặc định mọi tài khoản đăng ký mới luôn là USER (Khách hàng bình thường)
$vaiTro = 'USER';
if ($vaiTroInput === 'OWNER' || $vaiTroInput === 'ADMIN') {
    $vaiTro = 'OWNER';
}

// Nếu không nhập tên cửa hàng / hồ sơ → tự tạo tên mặc định
if (empty($tenCuaHang)) {
    $tenCuaHang = 'Tài khoản của ' . $hoTen;
}

// -----------------------------------------------------
// 2. VALIDATE DỮ LIỆU
// -----------------------------------------------------
$errors = [];

if (empty($hoTen) || mb_strlen($hoTen) < 2) {
    $errors[] = 'Họ tên phải có ít nhất 2 ký tự';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email không hợp lệ';
}

if (strlen($matKhau) < 6) {
    $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
}

if (!empty($errors)) {
    jsonResponse(false, implode('. ', $errors), [], 400);
}

// -----------------------------------------------------
// 3. XỬ LÝ ĐĂNG KÝ
// -----------------------------------------------------
try {
    $pdo = getDB(); // Lấy kết nối database

    // 3.1. Kiểm tra email đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT ID FROM NguoiDung WHERE Email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        // 409 Conflict = dữ liệu bị trùng
        jsonResponse(false, 'Email này đã được đăng ký. Vui lòng dùng email khác.', [], 409);
    }

    // 3.2. Tạo ID duy nhất cho User và Shop
    $userId = generateUUID();
    $shopId = generateUUID();

    // 3.3. Mã hóa mật khẩu
    // PASSWORD_DEFAULT hiện tại dùng thuật toán bcrypt (rất an toàn)
    // Không bao giờ lưu mật khẩu dạng plain text!
    $matKhauMaHoa = password_hash($matKhau, PASSWORD_DEFAULT);

    // 3.4. Bắt đầu Transaction
    // Transaction đảm bảo: hoặc cả 2 bảng đều thành công, hoặc cả 2 đều thất bại
    // (tránh trường hợp tạo được User nhưng không tạo được Shop)
    $pdo->beginTransaction();

    // 3.5. Thêm vào bảng NguoiDung (USER hoặc OWNER)
    $stmt = $pdo->prepare("
        INSERT INTO NguoiDung (ID, Email, MatKhauMaHoa, HoTen, VaiTro)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $email, $matKhauMaHoa, $hoTen, $vaiTro]);

    // 3.6. Thêm vào bảng CuaHang (quan hệ 1-1 với User)
    $stmt = $pdo->prepare("
        INSERT INTO CuaHang (ID, NguoiDung_id, TenCH, MoTaCH)
        VALUES (?, ?, ?, ?)
    ");
    $moTa = ($vaiTro === 'OWNER') ? 'Cửa hàng tạo tự động' : 'Hồ sơ người dùng cá nhân';
    $stmt->execute([$shopId, $userId, $tenCuaHang, $moTa]);

    // 3.7. Commit Transaction → lưu thật vào database
    $pdo->commit();

    // 3.8. Tự động đăng nhập ngay sau khi đăng ký thành công
    // Lưu thông tin vào Session để các trang sau biết đã login
    $_SESSION['user_id']  = $userId;
    $_SESSION['email']    = $email;
    $_SESSION['ho_ten']   = $hoTen;
    $_SESSION['vai_tro']  = $vaiTro;
    $_SESSION['shop_id']  = $shopId;
    $_SESSION['ten_ch']   = $tenCuaHang;

    // 3.9. Trả về kết quả thành công
    jsonResponse(true, 'Đăng ký thành công! Chào mừng bạn đến với FreshMart.', [
        'user_id' => $userId,
        'shop_id' => $shopId,
        'ho_ten'  => $hoTen,
        'email'   => $email,
        'vai_tro' => $vaiTro
    ]);

} catch (Exception $e) {
    // Nếu có lỗi → rollback (hủy hết thay đổi trong transaction)
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Trong môi trường thật nên ghi log: error_log($e->getMessage());
    jsonResponse(false, 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.', [], 500);
}
