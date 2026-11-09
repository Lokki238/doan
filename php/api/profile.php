<?php
/**
 * =====================================================
 * FILE: api/profile.php
 * Mục đích: Quản lý thông tin tài khoản người dùng
 *   - GET: Lấy thông tin cá nhân + danh sách đơn hàng/giao dịch
 *   - POST (update_profile): Cập nhật họ tên, số điện thoại
 *   - POST (change_password): Đổi mật khẩu
 * =====================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../includes/auth.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Vui lòng đăng nhập để tiếp tục', [], 401);
}

$currentUser = getCurrentUser();
$userId = $currentUser['id'];

try {
    $pdo = getDB();

    // --------------------------------------------------
    // XỬ LÝ GET: Lấy thông tin tài khoản và giao dịch
    // --------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userInfo = null;
        try {
            // Lấy thông tin người dùng từ DB (bảng nguoidung + cuahang)
            $stmt = $pdo->prepare("
                SELECT 
                    u.ID, u.Email, u.HoTen, u.VaiTro, u.ThoiGianTao,
                    c.ID AS ShopID, c.TenCH, c.MoTaCH
                FROM nguoidung u
                LEFT JOIN cuahang c ON c.NguoiDung_id = u.ID
                WHERE u.ID = ?
                LIMIT 1
            ");
            $stmt->execute([$userId]);
            $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Lỗi truy vấn DB thì fallback session
        }

        // Nếu DB chưa có bản ghi (hoặc DB khác môi trường): Dùng dữ liệu Session
        if (!$userInfo) {
            $userInfo = [
                'ID'          => $currentUser['id'],
                'Email'       => $currentUser['email'],
                'HoTen'       => $currentUser['ho_ten'] ?: explode('@', $currentUser['email'])[0],
                'VaiTro'      => $currentUser['vai_tro'] ?? 'USER',
                'ThoiGianTao' => date('Y-m-d H:i:s'),
                'ShopID'      => $currentUser['shop_id'] ?? null,
                'TenCH'       => $currentUser['ten_ch'] ?? ''
            ];
        }

        // Lấy lịch sử giao dịch liên quan
        $shopId = $userInfo['ShopID'] ?? $currentUser['shop_id'] ?? null;
        $transactions = [];

        if ($shopId) {
            try {
                $stmtTx = $pdo->prepare("
                    SELECT 
                        ID, MaDonHang, SoTien, LoaiCongThanhToan, LoaiCongThanhToan AS LoaiCong, TrangThai, 
                        MoTa, ThoiGianTao, ThoiGianThanhToan
                    FROM giaodich
                    WHERE CuaHang_id = ?
                    ORDER BY ThoiGianTao DESC
                    LIMIT 20
                ");
                $stmtTx->execute([$shopId]);
                $transactions = $stmtTx->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $eTx) {
                // Giữ mảng rỗng nếu bảng chưa có dữ liệu
                $transactions = [];
            }
        }

        $role = strtoupper($userInfo['VaiTro'] ?? 'USER');
        $isPrivileged = in_array($role, ['OWNER', 'STAFF', 'ADMIN', 'QUAN_TRI']);

        jsonResponse(true, 'Thành công', [
            'user' => [
                'id'            => $userInfo['ID'],
                'email'         => $userInfo['Email'],
                'ho_ten'        => $userInfo['HoTen'] ?: explode('@', $userInfo['Email'])[0],
                'vai_tro'       => $userInfo['VaiTro'],
                'is_privileged' => $isPrivileged,
                'created_at'    => $userInfo['ThoiGianTao'],
                'shop_id'       => $userInfo['ShopID'],
                'ten_ch'        => $userInfo['TenCH'] ?? ''
            ],
            'transactions' => $transactions
        ]);
    }

    // --------------------------------------------------
    // XỬ LÝ POST: Cập nhật thông tin hoặc đổi mật khẩu
    // --------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $action = $input['action'] ?? 'update_profile';

        // 1. CẬP NHẬT HỌ TÊN / CỬA HÀNG
        if ($action === 'update_profile') {
            $hoTen = trim($input['ho_ten'] ?? '');
            if (empty($hoTen) || mb_strlen($hoTen) < 2) {
                jsonResponse(false, 'Họ tên phải có ít nhất 2 ký tự', [], 400);
            }

            $stmt = $pdo->prepare("UPDATE nguoidung SET HoTen = ? WHERE ID = ?");
            $stmt->execute([$hoTen, $userId]);
            $_SESSION['ho_ten'] = $hoTen;

            // Nếu có tên cửa hàng và có quyền
            if (!empty($input['ten_ch'])) {
                $tenCH = trim($input['ten_ch']);
                $stmtCH = $pdo->prepare("UPDATE cuahang SET TenCH = ? WHERE NguoiDung_id = ?");
                $stmtCH->execute([$tenCH, $userId]);
                $_SESSION['ten_ch'] = $tenCH;
            }

            jsonResponse(true, 'Cập nhật thông tin cá nhân thành công!', [
                'ho_ten' => $hoTen
            ]);
        }

        // 2. ĐỔI MẬT KHẨU
        if ($action === 'change_password') {
            $currentPass = $input['current_password'] ?? '';
            $newPass     = $input['new_password'] ?? '';
            $confirmPass = $input['confirm_password'] ?? '';

            if (empty($currentPass) || empty($newPass)) {
                jsonResponse(false, 'Vui lòng nhập mật khẩu hiện tại và mật khẩu mới', [], 400);
            }

            if (strlen($newPass) < 6) {
                jsonResponse(false, 'Mật khẩu mới phải có ít nhất 6 ký tự', [], 400);
            }

            if ($newPass !== $confirmPass) {
                jsonResponse(false, 'Mật khẩu xác nhận không khớp', [], 400);
            }

            // Lấy hash mật khẩu cũ
            $stmt = $pdo->prepare("SELECT MatKhauMaHoa FROM nguoidung WHERE ID = ? LIMIT 1");
            $stmt->execute([$userId]);
            $row = $stmt->fetch();

            if (!$row || !password_verify($currentPass, $row['MatKhauMaHoa'])) {
                jsonResponse(false, 'Mật khẩu hiện tại không chính xác', [], 400);
            }

            // Mã hóa mật khẩu mới và lưu
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            $stmtUpdate = $pdo->prepare("UPDATE nguoidung SET MatKhauMaHoa = ? WHERE ID = ?");
            $stmtUpdate->execute([$newHash, $userId]);

            jsonResponse(true, 'Đổi mật khẩu thành công!');
        }

        jsonResponse(false, 'Thao tác không hợp lệ', [], 400);
    }

} catch (Exception $e) {
    jsonResponse(false, 'Đã xảy ra lỗi máy chủ: ' . $e->getMessage(), [], 500);
}
