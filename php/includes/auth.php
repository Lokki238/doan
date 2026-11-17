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
 * (OWNER, MANAGER, STAFF, ADMIN, QUAN_TRI)
 *
 * @return bool
 */
function isPrivileged(): bool {
    if (!isLoggedIn()) {
        return false;
    }
    $role = strtoupper($_SESSION['vai_tro'] ?? '');
    return in_array($role, ['OWNER', 'MANAGER', 'STAFF', 'ADMIN', 'QUAN_TRI']);
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
/**
 * Hiển thị trang 403 Forbidden đẹp mắt, hiện đại chuẩn giao diện FreshMart Pay
 *
 * @param string $message Thông báo giải thích chi tiết
 * @param array $allowedRoles Danh sách vai trò được phép truy cập
 */
function renderAccessDenied(string $message = 'Bạn không có quyền truy cập chức năng này.', array $allowedRoles = ['OWNER', 'MANAGER']): void {
    http_response_code(403);
    
    $user = getCurrentUser() ?? [
        'ho_ten'  => $_SESSION['ho_ten'] ?? 'Người dùng',
        'email'   => $_SESSION['email'] ?? 'Chưa xác định',
        'vai_tro' => $_SESSION['vai_tro'] ?? 'USER'
    ];
    $currentRole = strtoupper($user['vai_tro'] ?? 'USER');
    
    $roleLabels = [
        'OWNER'   => 'Quản trị viên tối cao (OWNER)',
        'MANAGER' => 'Quản lý Cửa hàng (MANAGER)',
        'STAFF'   => 'Nhân viên quản lý (STAFF)',
        'USER'    => 'Khách hàng cá nhân (USER)'
    ];
    $roleName = $roleLabels[$currentRole] ?? $currentRole;
    
    $roleBadgeClass = 'badge-user';
    $roleBadgeIcon = 'fa-user';
    if ($currentRole === 'OWNER') {
        $roleBadgeClass = 'badge-owner';
        $roleBadgeIcon = 'fa-crown text-warning';
    } elseif ($currentRole === 'MANAGER') {
        $roleBadgeClass = 'badge-manager';
        $roleBadgeIcon = 'fa-user-shield text-purple';
    } elseif ($currentRole === 'STAFF') {
        $roleBadgeClass = 'badge-staff';
        $roleBadgeIcon = 'fa-user-tie text-primary';
    }
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>403 - Từ chối truy cập | FreshMart Pay</title>
      <link href="/css/bootstrap.min.css" rel="stylesheet">
      <link href="../../css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="/css/all.min.css">
      <link rel="stylesheet" href="../../css/all.min.css">
      <style>
        body {
          background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%);
          min-height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
          font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
          margin: 0;
          padding: 20px;
          color: #1e293b;
        }
        .error-card {
          background: #ffffff;
          border-radius: 24px;
          box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.1), 0 0 1px rgba(15, 23, 42, 0.15);
          max-width: 620px;
          width: 100%;
          padding: 45px 40px;
          text-align: center;
          position: relative;
          overflow: hidden;
          border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .error-card::before {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          height: 6px;
          background: linear-gradient(90deg, #ef4444, #f59e0b, #10b981);
        }
        .shield-icon-wrapper {
          width: 100px;
          height: 100px;
          border-radius: 50%;
          background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
          color: #ef4444;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          font-size: 3rem;
          margin-bottom: 24px;
          box-shadow: 0 12px 28px -6px rgba(239, 68, 68, 0.35);
          position: relative;
          animation: pulseLock 2.4s infinite ease-in-out;
        }
        @keyframes pulseLock {
          0%, 100% { transform: scale(1); box-shadow: 0 12px 28px -6px rgba(239, 68, 68, 0.35); }
          50% { transform: scale(1.06); box-shadow: 0 16px 36px -4px rgba(239, 68, 68, 0.5); }
        }
        .error-badge {
          background: #fef2f2;
          color: #dc2626;
          border: 1px solid #fecaca;
          font-weight: 700;
          font-size: 0.82rem;
          letter-spacing: 0.5px;
          padding: 6px 14px;
          border-radius: 9999px;
          display: inline-flex;
          align-items: center;
          gap: 6px;
          margin-bottom: 16px;
        }
        .user-info-box {
          background: #f8fafc;
          border: 1px solid #e2e8f0;
          border-radius: 14px;
          padding: 16px 20px;
          margin: 22px 0;
          text-align: left;
        }
        .badge-owner { background: #fef3c7; color: #92400e; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
        .badge-manager { background: #ede9fe; color: #6b21a8; font-weight: 600; padding: 6px 12px; border-radius: 6px; border: 1px solid #ddd6fe; }
        .badge-staff { background: #e0f2fe; color: #0369a1; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
        .badge-user { background: #dcfce7; color: #166534; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
        .btn-action {
          padding: 11px 22px;
          font-weight: 600;
          border-radius: 10px;
          transition: all 0.2s;
        }
        .btn-action:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }
      </style>
    </head>
    <body>
      <div class="error-card">
        <!-- Logo -->
        <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
          <i class="fas fa-leaf text-success fs-3"></i>
          <span class="fs-4 fw-bold text-dark">FreshMart <span class="text-success">Pay</span></span>
        </div>

        <!-- Animated Icon -->
        <div class="shield-icon-wrapper">
          <i class="fas fa-user-lock"></i>
        </div>

        <!-- 403 Badge -->
        <div>
          <span class="error-badge">
            <i class="fas fa-ban"></i> HTTP 403: TỪ CHỐI TRUY CẬP
          </span>
        </div>

        <!-- Title & Description -->
        <h3 class="fw-bold text-dark mb-2">Bạn không có quyền truy cập chức năng này</h3>
        <p class="text-muted mb-3" style="font-size: 0.96rem; line-height: 1.6;">
          <?= htmlspecialchars($message) ?>
        </p>

        <!-- User Info Card -->
        <div class="user-info-box">
          <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
            <span class="text-muted small"><i class="fas fa-envelope me-1"></i> Tài khoản của bạn:</span>
            <span class="fw-bold text-dark"><?= htmlspecialchars($user['email']) ?></span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted small"><i class="fas fa-id-badge me-1"></i> Vai trò hiện tại:</span>
            <span class="<?= $roleBadgeClass ?>">
              <i class="fas <?= $roleBadgeIcon ?> me-1"></i> <?= htmlspecialchars($roleName) ?>
            </span>
          </div>
        </div>

        <!-- Hint Box -->
        <div class="alert alert-light border small text-muted text-start mb-4 py-2 px-3" style="border-radius: 10px; background: #fafafa;">
          <i class="fas fa-info-circle text-primary me-1"></i>
          <strong>Gợi ý:</strong> Chức năng này yêu cầu quyền <strong>Quản lý Cửa hàng (MANAGER)</strong> hoặc <strong>Quản trị viên (OWNER)</strong>. Nếu cần phân quyền, vui lòng liên hệ tài khoản Quản trị viên để được cấp quyền tại trang <a href="/php/permissions.php" class="text-primary fw-semibold">Cấp quyền tài khoản</a>.
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap gap-2 justify-content-center">
          <a href="/php/dashboard.php" class="btn btn-success btn-action">
            <i class="fas fa-home me-1"></i> Về trang Tổng quan
          </a>
          <a href="/php/transactions.php" class="btn btn-outline-primary btn-action">
            <i class="fas fa-exchange-alt me-1"></i> Xem Giao dịch
          </a>
          <button onclick="window.history.back()" class="btn btn-outline-secondary btn-action">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
          </button>
          <a href="/html/index.html" class="btn btn-light border btn-action">
            <i class="fas fa-shopping-basket me-1 text-success"></i> FreshMart (Trang chủ)
          </a>
        </div>
      </div>
    </body>
    </html>
    <?php
    exit;
}

/**
 * Bắt buộc phải là Owner hoặc Manager mới được dùng chức năng
 * 
 * Cách hoạt động:
 * - Trước tiên gọi requireLogin() để đảm bảo đã đăng nhập
 * - Sau đó kiểm tra $_SESSION['vai_tro'] có trong ['OWNER', 'MANAGER', 'ADMIN', 'QUAN_TRI'] không
 * - Nếu không phải → hiển thị giao diện 403 Forbidden đẹp mắt, chuyên nghiệp
 *
 * Dùng cho các trang nhạy cảm: cấu hình cổng thanh toán, quản lý nhân viên...
 *
 * @param string $redirect
 */
function requireOwner(string $redirect = '/html/login.html'): void {
    requireLogin($redirect);
    
    $role = strtoupper($_SESSION['vai_tro'] ?? '');
    if (!in_array($role, ['OWNER', 'MANAGER', 'ADMIN', 'QUAN_TRI'])) {
        renderAccessDenied(
            'Chức năng Cấu hình Cổng thanh toán (gateways.php) chứa thông tin thiết lập tài chính nhạy cảm. Chỉ Quản lý Cửa hàng hoặc Quản trị viên mới được phép truy cập.',
            ['OWNER', 'MANAGER']
        );
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
