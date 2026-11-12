<?php
/**
 * =====================================================
 * FILE: includes/auth.php
 * Mục đích: Các hàm hỗ trợ xác thực (Authentication) và phân quyền (Authorization)
 * Tuần 7 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * File này được require vào hầu hết các trang cần bảo vệ.
 * Nó quản lý Session của PHP để biết người dùng đã đăng nhập hay chưa.
 */

// -----------------------------------------------------
// Khởi tạo Output Buffering & Session nếu chưa có
// Output Buffering ngăn chặn triệt để lỗi "Headers already sent"
// -----------------------------------------------------
if (ob_get_level() === 0) {
    ob_start();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nạp file kết nối database (vì một số hàm có thể cần dùng)
if (file_exists(__DIR__ . '/../../csdl/db.php')) {
    require_once __DIR__ . '/../../csdl/db.php';
} elseif (file_exists(__DIR__ . '/../config/db.php')) {
    require_once __DIR__ . '/../config/db.php';
}

/**
 * Kiểm tra xem người dùng đã đăng nhập chưa
 * 
 * Cách hoạt động:
 * - Chỉ cần kiểm tra biến $_SESSION['user_id'] có tồn tại và không rỗng
 * - Nếu có → đã đăng nhập, trả về true
 * - Nếu không → chưa đăng nhập, trả về false
 *
 * @return bool
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Lấy thông tin người dùng hiện tại từ Session
 * 
 * Cách hoạt động:
 * - Nếu chưa đăng nhập → trả về null
 * - Nếu đã đăng nhập → trả về mảng chứa các thông tin đã lưu khi login/register
 *
 * @return array|null
 */
function getCurrentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id'       => $_SESSION['user_id'],
        'email'    => $_SESSION['email'] ?? '',
        'ho_ten'   => $_SESSION['ho_ten'] ?? '',
        'vai_tro'  => $_SESSION['vai_tro'] ?? 'USER',
        'shop_id'  => $_SESSION['shop_id'] ?? null,
        'ten_ch'   => $_SESSION['ten_ch'] ?? '',
    ];
}

/**
 * Kiểm tra xem người dùng hiện tại có vai trò quản trị không
 * (OWNER, STAFF, ADMIN, QUAN_TRI)
 *
 * @return bool
 */
function isPrivileged(): bool {
    if (!isLoggedIn()) {
        return false;
    }
    $role = strtoupper($_SESSION['vai_tro'] ?? '');
    return in_array($role, ['OWNER', 'STAFF', 'ADMIN', 'QUAN_TRI']);
}

/**
 * Bắt buộc người dùng phải có quyền quản trị
 * Nếu không có quyền → chuyển hướng về trang tài khoản người dùng cá nhân
 */
function requirePrivileged(string $redirect = '/html/account.html'): void {
    requireLogin('/html/login.html');
    if (!isPrivileged()) {
        header('Location: ' . $redirect);
        exit;
    }
}

/**
 * Bắt buộc phải đăng nhập mới được vào trang
 * 
 * Cách hoạt động:
 * - Gọi isLoggedIn()
 * - Nếu chưa đăng nhập → chuyển hướng (redirect) về trang login
 * - Dùng header('Location: ...') + exit để dừng script ngay lập tức
 *
 * @param string $redirect Đường dẫn trang login (mặc định về /html/login.html)
 */
function requireLogin(string $redirect = '/html/login.html'): void {
    if (!isLoggedIn()) {
        header('Location: ' . $redirect);
        exit; // Rất quan trọng: dừng toàn bộ code phía sau
    }
}

/**
 * Bắt buộc phải là Owner (Chủ cửa hàng) mới được dùng chức năng
 * 
 * Cách hoạt động:
 * - Trước tiên gọi requireLogin() để đảm bảo đã đăng nhập
 * - Sau đó kiểm tra $_SESSION['vai_tro'] có phải 'OWNER' không
 * - Nếu không phải → trả về lỗi 403 Forbidden
 *
 * Dùng cho các trang nhạy cảm: cấu hình cổng thanh toán, quản lý nhân viên...
 *
 * @param string $redirect
 */
function requireOwner(string $redirect = '/html/login.html'): void {
    requireLogin($redirect);
    
    if (($_SESSION['vai_tro'] ?? '') !== 'OWNER') {
        http_response_code(403); // Forbidden
        die('Bạn không có quyền truy cập chức năng này. Chỉ Chủ cửa hàng (Owner) mới được phép.');
    }
}

/**
 * Đăng xuất người dùng
 * 
 * Cách hoạt động chi tiết:
 * 1. Xóa toàn bộ dữ liệu trong $_SESSION
 * 2. Nếu session dùng cookie → xóa cookie session trên trình duyệt
 * 3. Hủy session trên server bằng session_destroy()
 *
 * Sau khi gọi hàm này, isLoggedIn() sẽ trả về false
 */
function logout(): void {
    // 1. Xóa hết dữ liệu session
    $_SESSION = [];
    
    // 2. Xóa cookie session (nếu có)
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),           // Tên cookie session (thường là PHPSESSID)
            '',                      // Giá trị rỗng
            time() - 42000,          // Thời gian hết hạn trong quá khứ → trình duyệt xóa
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    // 3. Hủy session trên server
    session_destroy();
}

/**
 * Hàm trả về phản hồi JSON chuẩn cho API
 * 
 * Cách hoạt động:
 * - Đặt HTTP status code (200, 400, 401, 500...)
 * - Đặt header Content-Type là JSON
 * - In ra chuỗi JSON và dừng script (exit)
 *
 * Cấu trúc JSON thống nhất:
 * {
 *   "success": true/false,
 *   "message": "Thông báo cho người dùng",
 *   "data": { ... dữ liệu bổ sung ... }
 * }
 *
 * @param bool   $success  Thành công hay thất bại
 * @param string $message  Thông báo
 * @param array  $data     Dữ liệu kèm theo (mặc định rỗng)
 * @param int    $code     HTTP status code (mặc định 200)
 */
function jsonResponse(bool $success, string $message, array $data = [], int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data
    ], JSON_UNESCAPED_UNICODE); // JSON_UNESCAPED_UNICODE để tiếng Việt không bị mã hóa \uXXXX
    
    exit; // Dừng script ngay sau khi trả về JSON
}
