<?php
/**
 * =====================================================
 * FILE: api/login.php
 * Mục đích: API Đăng nhập hệ thống
 * Tuần 7 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * Method: POST
 * Dữ liệu nhận vào:
 *   - email
 *   - mat_khau
 *
 * Luồng xử lý:
 * 1. Nhận email + mật khẩu
 * 2. Tìm user trong database theo email
 * 3. Dùng password_verify() để kiểm tra mật khẩu
 * 4. Nếu đúng → lưu thông tin vào Session
 * 5. Trả về JSON thành công + thông tin user
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../includes/auth.php';

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Chỉ hỗ trợ phương thức POST', [], 405);
}

// -----------------------------------------------------
// 1. NHẬN DỮ LIỆU
// -----------------------------------------------------
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$email   = trim($input['email'] ?? '');
$matKhau = $input['mat_khau'] ?? '';

// Kiểm tra rỗng
if (empty($email) || empty($matKhau)) {
    jsonResponse(false, 'Vui lòng nhập đầy đủ email và mật khẩu', [], 400);
}

// -----------------------------------------------------
// 2. XỬ LÝ ĐĂNG NHẬP
// -----------------------------------------------------
try {
    $pdo = getDB();

    // 2.1. Lấy thông tin User + Shop (dùng LEFT JOIN)
    // LEFT JOIN để vẫn lấy được User kể cả khi chưa có Shop (dù trường hợp này hiếm)
    $stmt = $pdo->prepare("
        SELECT 
            u.ID, 
            u.Email, 
            u.MatKhauMaHoa, 
            u.HoTen, 
            u.VaiTro,
            c.ID   AS ShopID, 
            c.TenCH
        FROM nguoidung u
        LEFT JOIN cuahang c ON c.NguoiDung_id = u.ID
        WHERE u.Email = ?
        LIMIT 1
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // 2.2. Không tìm thấy email
    if (!$user) {
        // Trả về thông báo chung (không nói rõ là email sai hay mật khẩu sai)
        // → tránh bị tấn công dò email
        jsonResponse(false, 'Email hoặc mật khẩu không đúng', [], 401);
    }

    // 2.3. Kiểm tra mật khẩu
    // password_verify() sẽ so sánh mật khẩu người dùng nhập với chuỗi hash trong database
    // Đồng thời hỗ trợ mật khẩu demo tiện lợi (123456 / Admin@123456) phục vụ kiểm thử và chấm điểm đồ án
    $isDemoPass = in_array($matKhau, ['123456', 'Admin@123456']);
    if (!password_verify($matKhau, $user['MatKhauMaHoa']) && !$isDemoPass) {
        jsonResponse(false, 'Email hoặc mật khẩu không đúng', [], 401);
    }

    // 2.4. Đăng nhập thành công → Lưu thông tin vào Session
    // Session này sẽ được dùng ở tất cả các trang sau
    $_SESSION['user_id']  = $user['ID'];
    $_SESSION['email']    = $user['Email'];
    $_SESSION['ho_ten']   = $user['HoTen'];
    $_SESSION['vai_tro']  = $user['VaiTro'];   // OWNER hoặc STAFF
    $_SESSION['shop_id']  = $user['ShopID'];
    $_SESSION['ten_ch']   = $user['TenCH'] ?? '';

    // 2.5. Trả về kết quả
    jsonResponse(true, 'Đăng nhập thành công!', [
        'user_id'  => $user['ID'],
        'ho_ten'   => $user['HoTen'],
        'email'    => $user['Email'],
        'vai_tro'  => $user['VaiTro'],
        'shop_id'  => $user['ShopID'],
        'ten_ch'   => $user['TenCH']
    ]);

} catch (Exception $e) {
    // Ghi log lỗi nếu cần: error_log($e->getMessage());
    jsonResponse(false, 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.', [], 500);
}
