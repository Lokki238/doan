<?php
/**
 * =====================================================
 * FILE: api/permissions.php
 * Mục đích: API Tìm kiếm tài khoản và Cập nhật phân cấp quyền (VaiTro)
 * Phân cấp thẩm quyền:
 * - Quản trị viên (OWNER): Điều chỉnh tất cả tài khoản
 * - Quản lý Cửa hàng (MANAGER): Điều chỉnh Nhân viên (STAFF) và Khách hàng (USER)
 * - Nhân viên (STAFF): Chỉ điều chỉnh Khách hàng (USER)
 * =====================================================
 * 
 * - GET: Tìm kiếm người dùng theo email, họ tên, hoặc lấy danh sách
 *   Query: ?q=tu_khoa
 * 
 * - POST: Cập nhật quyền của tài khoản
 *   Body: { "user_id": "...", "vai_tro": "OWNER" | "MANAGER" | "STAFF" | "USER" }
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../includes/auth.php';

// Bắt buộc phải đăng nhập và có quyền từ Nhân viên trở lên
if (!isLoggedIn()) {
    jsonResponse(false, 'Vui lòng đăng nhập để thực hiện chức năng này', [], 401);
}

$currentUser = getCurrentUser();
$currentRole = strtoupper($currentUser['vai_tro'] ?? 'USER');
if (!in_array($currentRole, ['OWNER', 'ADMIN', 'QUAN_TRI', 'MANAGER', 'STAFF'])) {
    jsonResponse(false, 'Bạn không có quyền thực hiện chức năng này. Chỉ Quản trị viên, Quản lý hoặc Nhân viên mới được phép.', [], 403);
}

try {
    $pdo = getDB();

    // -------------------------------------------------
    // 1. GET: TÌM KIẾM NGƯỜI DÙNG HOẶC LẤY DANH SÁCH
    // -------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $q = trim($_GET['q'] ?? '');

        if (!empty($q)) {
            // Tìm theo email hoặc họ tên hoặc ID
            $stmt = $pdo->prepare("
                SELECT 
                    u.ID, 
                    u.Email, 
                    u.HoTen, 
                    u.VaiTro, 
                    u.ThoiGianTao, 
                    u.ThoiGianCapNhat,
                    c.TenCH
                FROM nguoidung u
                LEFT JOIN cuahang c ON c.NguoiDung_id = u.ID
                WHERE u.Email LIKE ? OR u.HoTen LIKE ? OR u.ID = ?
                ORDER BY u.ThoiGianTao DESC
                LIMIT 50
            ");
            $searchTerm = '%' . $q . '%';
            $stmt->execute([$searchTerm, $searchTerm, $q]);
        } else {
            // Nếu không có từ khóa: lấy danh sách 50 người dùng mới nhất
            $stmt = $pdo->prepare("
                SELECT 
                    u.ID, 
                    u.Email, 
                    u.HoTen, 
                    u.VaiTro, 
                    u.ThoiGianTao, 
                    u.ThoiGianCapNhat,
                    c.TenCH
                FROM nguoidung u
                LEFT JOIN cuahang c ON c.NguoiDung_id = u.ID
                ORDER BY u.ThoiGianTao DESC
                LIMIT 50
            ");
            $stmt->execute();
        }

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        jsonResponse(true, 'Lấy danh sách người dùng thành công', [
            'total'        => count($users),
            'query'        => $q,
            'users'        => $users,
            'current_user' => [
                'id'       => $currentUser['id'],
                'email'    => $currentUser['email'],
                'ho_ten'   => $currentUser['ho_ten'],
                'vai_tro'  => $currentRole
            ]
        ]);
    }

    // -------------------------------------------------
    // 2. POST: CẬP NHẬT QUYỀN TÀI KHOẢN (VaiTro)
    // -------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $targetUserId = trim($input['user_id'] ?? '');
        $newRole = strtoupper(trim($input['vai_tro'] ?? ''));

        if (empty($targetUserId)) {
            jsonResponse(false, 'Thiếu thông tin mã tài khoản (user_id)', [], 400);
        }

        $allowedRoles = ['OWNER', 'MANAGER', 'STAFF', 'USER'];
        if (!in_array($newRole, $allowedRoles)) {
            jsonResponse(false, 'Vai trò không hợp lệ. Chỉ chấp nhận: OWNER, MANAGER, STAFF, USER', [], 400);
        }

        // Kiểm tra xem tài khoản mục tiêu có tồn tại không
        $checkStmt = $pdo->prepare("SELECT ID, Email, HoTen, VaiTro FROM nguoidung WHERE ID = ? LIMIT 1");
        $checkStmt->execute([$targetUserId]);
        $targetUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$targetUser) {
            jsonResponse(false, 'Không tìm thấy tài khoản người dùng trong hệ thống', [], 404);
        }

        $targetRole = strtoupper($targetUser['VaiTro'] ?? 'USER');

        // KIỂM TRA PHÂN CẤP THẨM QUYỀN ĐIỀU CHỈNH
        // 1. Quản trị viên (OWNER, ADMIN, QUAN_TRI): Điều chỉnh tất cả mọi người
        if (in_array($currentRole, ['OWNER', 'ADMIN', 'QUAN_TRI'])) {
            // Cho phép điều chỉnh bất kỳ ai và bất kỳ vai trò nào
        }
        // 2. Quản lý Cửa hàng (MANAGER): Điều chỉnh Nhân viên và Khách hàng
        elseif ($currentRole === 'MANAGER') {
            if (!in_array($targetRole, ['STAFF', 'USER'])) {
                jsonResponse(false, 'Quản lý Cửa hàng chỉ được phép điều chỉnh tài khoản Nhân viên và Khách hàng. Không có quyền sửa tài khoản Quản lý hoặc Quản trị viên.', [], 403);
            }
            if (!in_array($newRole, ['STAFF', 'USER'])) {
                jsonResponse(false, 'Quản lý Cửa hàng chỉ có quyền cấp vai trò Nhân viên (STAFF) hoặc Khách hàng (USER). Không thể nâng lên Quản lý hoặc Quản trị viên.', [], 403);
            }
        }
        // 3. Nhân viên (STAFF): Chỉ điều chỉnh được Khách hàng
        elseif ($currentRole === 'STAFF') {
            if ($targetRole !== 'USER') {
                jsonResponse(false, 'Nhân viên chỉ có quyền điều chỉnh tài khoản Khách hàng (USER). Không có quyền điều chỉnh Nhân viên, Quản lý hoặc Quản trị viên.', [], 403);
            }
            if ($newRole !== 'USER') {
                jsonResponse(false, 'Nhân viên chỉ có quyền điều chỉnh tài khoản Khách hàng (USER), không có thẩm quyền nâng lên vai trò Nhân viên, Quản lý hoặc Quản trị viên.', [], 403);
            }
        } else {
            jsonResponse(false, 'Bạn không có quyền phân quyền tài khoản.', [], 403);
        }

        // Cập nhật database
        $updateStmt = $pdo->prepare("
            UPDATE nguoidung 
            SET VaiTro = ?, ThoiGianCapNhat = NOW() 
            WHERE ID = ?
        ");
        $updateStmt->execute([$newRole, $targetUserId]);

        // Nếu cập nhật quyền cho chính mình đang đăng nhập thì cập nhật luôn Session
        if ($targetUserId === $currentUser['id']) {
            $_SESSION['vai_tro'] = $newRole;
        }

        $roleNames = [
            'OWNER'   => 'Quản trị viên tối cao (OWNER)',
            'MANAGER' => 'Quản lý Cửa hàng (MANAGER)',
            'STAFF'   => 'Nhân viên quản lý (STAFF)',
            'USER'    => 'Khách hàng cá nhân (USER)'
        ];

        jsonResponse(true, 'Đã cập nhật quyền cho tài khoản ' . $targetUser['Email'] . ' thành ' . ($roleNames[$newRole] ?? $newRole) . ' thành công!', [
            'user_id'  => $targetUserId,
            'email'    => $targetUser['Email'],
            'ho_ten'   => $targetUser['HoTen'],
            'vai_tro'  => $newRole
        ]);
    }

} catch (Exception $e) {
    jsonResponse(false, 'Lỗi xử lý cơ sở dữ liệu: ' . $e->getMessage(), [], 500);
}
