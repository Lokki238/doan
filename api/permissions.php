<?php
/**
 * =====================================================
 * FILE: api/permissions.php
 * Mục đích: API Tìm kiếm tài khoản và Cập nhật phân quyền (VaiTro)
 * =====================================================
 * 
 * - GET: Tìm kiếm người dùng theo email, họ tên, hoặc lấy danh sách
 *   Query: ?q=tu_khoa
 * 
 * - POST: Cập nhật quyền của tài khoản
 *   Body: { "user_id": "...", "vai_tro": "OWNER" | "STAFF" | "USER" }
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../includes/auth.php';

// Bắt buộc phải đăng nhập và có quyền quản trị
if (!isLoggedIn()) {
    jsonResponse(false, 'Vui lòng đăng nhập để thực hiện chức năng này', [], 401);
}

$currentUser = getCurrentUser();
$role = strtoupper($currentUser['vai_tro'] ?? '');
if (!in_array($role, ['OWNER', 'ADMIN', 'STAFF', 'QUAN_TRI'])) {
    jsonResponse(false, 'Bạn không có quyền thực hiện chức năng này. Chỉ Quản trị viên mới được phép.', [], 403);
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
                FROM NguoiDung u
                LEFT JOIN CuaHang c ON c.NguoiDung_id = u.ID
                WHERE u.Email LIKE ? OR u.HoTen LIKE ? OR u.ID = ?
                ORDER BY u.ThoiGianTao DESC
                LIMIT 50
            ");
            $searchTerm = '%' . $q . '%';
            $stmt->execute([$searchTerm, $searchTerm, $q]);
        } else {
            // Nếu không có từ khóa: lấy danh sách 30 người dùng mới nhất
            $stmt = $pdo->prepare("
                SELECT 
                    u.ID, 
                    u.Email, 
                    u.HoTen, 
                    u.VaiTro, 
                    u.ThoiGianTao, 
                    u.ThoiGianCapNhat,
                    c.TenCH
                FROM NguoiDung u
                LEFT JOIN CuaHang c ON c.NguoiDung_id = u.ID
                ORDER BY u.ThoiGianTao DESC
                LIMIT 30
            ");
            $stmt->execute();
        }

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        jsonResponse(true, 'Lấy danh sách người dùng thành công', [
            'total' => count($users),
            'query' => $q,
            'users' => $users
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

        $allowedRoles = ['OWNER', 'STAFF', 'USER'];
        if (!in_array($newRole, $allowedRoles)) {
            jsonResponse(false, 'Vai trò không hợp lệ. Chỉ chấp nhận: OWNER, STAFF, USER', [], 400);
        }

        // Kiểm tra xem user có tồn tại không
        $checkStmt = $pdo->prepare("SELECT ID, Email, HoTen, VaiTro FROM NguoiDung WHERE ID = ? LIMIT 1");
        $checkStmt->execute([$targetUserId]);
        $targetUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if (!$targetUser) {
            jsonResponse(false, 'Không tìm thấy tài khoản người dùng trong hệ thống', [], 404);
        }

        // Cập nhật database
        $updateStmt = $pdo->prepare("
            UPDATE NguoiDung 
            SET VaiTro = ?, ThoiGianCapNhat = NOW() 
            WHERE ID = ?
        ");
        $updateStmt->execute([$newRole, $targetUserId]);

        // Nếu cập nhật quyền cho chính mình đang đăng nhập thì cập nhật luôn Session
        if ($targetUserId === $currentUser['id']) {
            $_SESSION['vai_tro'] = $newRole;
        }

        $roleNames = [
            'OWNER' => 'Chủ cửa hàng / Quản trị viên tối cao (OWNER)',
            'STAFF' => 'Nhân viên quản lý (STAFF)',
            'USER'  => 'Khách hàng cá nhân (USER)'
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
