<?php
/**
 * =====================================================
 * FILE: api/gateways.php
 * Mục đích: Quản lý cấu hình cổng thanh toán (CRUD cơ bản)
 * Tuần 8 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * Method:
 *   GET  → Lấy danh sách cổng đã cấu hình của shop
 *   POST → Thêm / cập nhật cấu hình 1 cổng
 *
 * Chỉ Owner mới được thêm/sửa. Staff chỉ xem được danh sách.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/security.php';

if (!isLoggedIn()) {
    jsonResponse(false, 'Bạn cần đăng nhập', [], 401);
}

$user = getCurrentUser();
$isOwner = ($user['vai_tro'] === 'OWNER');
$pdo = getDB();

$shopId = $user['shop_id'] ?? null;
if (empty($shopId)) {
    $stmtShop = $pdo->query("SELECT ID FROM cuahang ORDER BY ThoiGianTao ASC LIMIT 1");
    $shopId = $stmtShop->fetchColumn();
}

// =====================================================
// GET: Lấy danh sách cổng thanh toán của shop
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("
        SELECT 
            ID, LoaiCongThanhToan, MaNguoiBan, 
            CauHinhThem, TrangThaiHoatDong, ThoiGianTao
            -- Không trả KhoaAPI_MaHoa và KhoaBiMat_MaHoa ra ngoài vì bảo mật
        FROM congthanhtoan
        WHERE CuaHang_id = ?
        ORDER BY LoaiCongThanhToan
    ");
    $stmt->execute([$shopId]);
    $list = $stmt->fetchAll();

    // Decode JSON CauHinhThem cho dễ dùng
    foreach ($list as &$item) {
        if (!empty($item['CauHinhThem'])) {
            $item['CauHinhThem'] = json_decode($item['CauHinhThem'], true);
        }
        // Đánh dấu đã cấu hình key hay chưa (không trả key thật)
        $item['da_cau_hinh_key'] = true; // Vì ta không select key ra
    }

    jsonResponse(true, 'Lấy danh sách cổng thành công', ['gateways' => $list]);
}

// =====================================================
// POST: Thêm hoặc cập nhật cấu hình cổng
// =====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Chỉ Owner được sửa cấu hình
    if (!$isOwner) {
        jsonResponse(false, 'Chỉ Chủ cửa hàng (Owner) mới được cấu hình cổng thanh toán', [], 403);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        $input = $_POST;
    }

    $loaiCong     = strtoupper(trim($input['loai_cong'] ?? ''));
    $maNguoiBan   = trim($input['ma_nguoi_ban'] ?? '');       // Số TK hoặc Merchant ID
    $khoaAPI      = trim($input['khoa_api'] ?? '');            // API Key (sẽ mã hóa)
    $khoaBiMat    = trim($input['khoa_bi_mat'] ?? '');         // Secret Key (sẽ mã hóa)
    $cauHinhThem  = $input['cau_hinh_them'] ?? [];            // JSON: bank_code, account_name...
    $trangThai    = isset($input['trang_thai']) ? (int)$input['trang_thai'] : 1;

    // Validate
    if (!in_array($loaiCong, ['VIETQR', 'MOMO', 'VNPAY', 'ZALOPAY'])) {
        jsonResponse(false, 'Loại cổng không hợp lệ', [], 400);
    }
    if (empty($maNguoiBan)) {
        jsonResponse(false, 'Vui lòng nhập Mã người bán / Số tài khoản', [], 400);
    }

    // Chuẩn hóa CauHinhThem thành JSON string
    if (is_array($cauHinhThem)) {
        $cauHinhThemJson = json_encode($cauHinhThem, JSON_UNESCAPED_UNICODE);
    } else {
        $cauHinhThemJson = $cauHinhThem;
    }

    // Mã hóa key trước khi lưu
    $khoaAPI_MaHoa   = !empty($khoaAPI)   ? encryptData($khoaAPI)   : null;
    $khoaBiMat_MaHoa = !empty($khoaBiMat) ? encryptData($khoaBiMat) : null;

    try {
        // Kiểm tra đã có cấu hình cổng này chưa
        $stmt = $pdo->prepare("
            SELECT ID FROM congthanhtoan 
            WHERE CuaHang_id = ? AND LoaiCongThanhToan = ?
        ");
        $stmt->execute([$shopId, $loaiCong]);
        $existing = $stmt->fetch();

        if ($existing) {
            // ----- CẬP NHẬT -----
            // Chỉ update key nếu người dùng có nhập key mới
            $sql = "UPDATE congthanhtoan SET 
                        MaNguoiBan = ?, 
                        CauHinhThem = ?, 
                        TrangThaiHoatDong = ?";
            $params = [$maNguoiBan, $cauHinhThemJson, $trangThai];

            if ($khoaAPI_MaHoa !== null) {
                $sql .= ", KhoaAPI_MaHoa = ?";
                $params[] = $khoaAPI_MaHoa;
            }
            if ($khoaBiMat_MaHoa !== null) {
                $sql .= ", KhoaBiMat_MaHoa = ?";
                $params[] = $khoaBiMat_MaHoa;
            }

            $sql .= " WHERE ID = ?";
            $params[] = $existing['ID'];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            jsonResponse(true, "Cập nhật cấu hình cổng $loaiCong thành công", [
                'id' => $existing['ID']
            ]);
        } else {
            // ----- THÊM MỚI -----
            $id = generateUUID();
            $stmt = $pdo->prepare("
                INSERT INTO congthanhtoan (
                    ID, CuaHang_id, LoaiCongThanhToan, MaNguoiBan,
                    KhoaAPI_MaHoa, KhoaBiMat_MaHoa, CauHinhThem, TrangThaiHoatDong
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $id,
                $shopId,
                $loaiCong,
                $maNguoiBan,
                $khoaAPI_MaHoa,
                $khoaBiMat_MaHoa,
                $cauHinhThemJson,
                $trangThai
            ]);

            jsonResponse(true, "Thêm cấu hình cổng $loaiCong thành công", [
                'id' => $id
            ]);
        }

    } catch (Exception $e) {
        jsonResponse(false, 'Lỗi khi lưu cấu hình: ' . $e->getMessage(), [], 500);
    }
}

// Method khác không hỗ trợ
jsonResponse(false, 'Method không được hỗ trợ', [], 405);
