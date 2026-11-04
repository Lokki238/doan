<?php
/**
 * =====================================================
 * FILE: api/webhook_vietqr.php
 * Mục đích: Nhận thông báo (webhook/callback) từ hệ thống VietQR / ngân hàng
 * Tuần 9 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * Cách hoạt động thực tế của VietQR:
 * - VietQR chủ yếu là chuyển khoản ngân hàng (A2A)
 * - Một số ngân hàng / dịch vụ trung gian (vietqr.io, Casso, SePay...) 
 *   sẽ gửi webhook khi phát hiện có tiền vào tài khoản
 * - Endpoint này nhận dữ liệu đó, xác thực, rồi cập nhật trạng thái giao dịch
 *
 * Luồng xử lý:
 * 1. Nhận raw data từ webhook
 * 2. Ghi log vào bảng NhatKyWebhook (dù thành công hay thất bại)
 * 3. Kiểm tra chữ ký / tính hợp lệ (nếu có)
 * 4. Tìm giao dịch tương ứng (qua mã đơn hàng hoặc số tiền + nội dung)
 * 5. Cập nhật trạng thái GiaoDich → SUCCESS hoặc FAILED
 * 6. Trả về HTTP 200 để bên gửi biết đã nhận thành công
 *
 * Lưu ý quan trọng:
 * - Webhook phải trả về nhanh (< 5 giây) và luôn trả 200 nếu xử lý xong
 * - Phải chống trùng lặp (idempotent): nhận cùng 1 webhook nhiều lần vẫn an toàn
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/security.php';

// -----------------------------------------------------
// 1. NHẬN DỮ LIỆU WEBHOOK VÀ HEADERS
// -----------------------------------------------------
$rawBody = file_get_contents('php://input');
$headers = function_exists('getallheaders') ? getallheaders() : [];

$signatureHeader = '';
$timestampHeader = 0;
foreach ($headers as $k => $v) {
    if (strcasecmp($k, 'X-SePay-Signature') === 0) {
        $signatureHeader = $v;
    }
    if (strcasecmp($k, 'X-SePay-Timestamp') === 0) {
        $timestampHeader = (int)$v;
    }
}
if (empty($signatureHeader) && isset($_SERVER['HTTP_X_SEPAY_SIGNATURE'])) {
    $signatureHeader = $_SERVER['HTTP_X_SEPAY_SIGNATURE'];
}
if (empty($timestampHeader) && isset($_SERVER['HTTP_X_SEPAY_TIMESTAMP'])) {
    $timestampHeader = (int)$_SERVER['HTTP_X_SEPAY_TIMESTAMP'];
}

// -----------------------------------------------------
// 2. XÁC THỰC CHỮ KÝ HMAC-SHA256 CỦA SEPAY (NẾU CÓ CHỮ KÝ)
// -----------------------------------------------------
$sepaySecret = getenv('SEPAY_WEBHOOK_SECRET') ?: 'whsec_3SGl9TsZs2I0aeisxrRyS8Cb26isRk2u';
$daXacThuc = 0;

if (!empty($signatureHeader) && !empty($sepaySecret)) {
    // Chống Replay Attack (lệch thời gian tối đa 300 giây = 5 phút)
    if ($timestampHeader > 0 && abs(time() - $timestampHeader) > 300) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Yêu cầu hết hạn (Timestamp expired)']);
        exit;
    }

    $signedPayload = $timestampHeader . '.' . $rawBody;
    $rawHash = hash_hmac('sha256', $signedPayload, $sepaySecret);
    $prefixedHash = 'sha256=' . $rawHash;

    if (!hash_equals($prefixedHash, $signatureHeader) && !hash_equals($rawHash, $signatureHeader)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Chữ ký HMAC-SHA256 không hợp lệ']);
        exit;
    }

    $daXacThuc = 1;
}

$input = json_decode($rawBody, true);
if (!$input) {
    $input = $_POST;
}

// Ghi nhận thời gian nhận
$receivedAt = date('Y-m-d H:i:s');

try {
    $pdo = getDB();

    // -----------------------------------------------------
    // 3. GHI LOG WEBHOOK NGAY LẬP TỨC (dù xử lý thành công hay không)
    // Đây là best practice: luôn lưu lại để đối soát sau này
    // -----------------------------------------------------
    $logId = generateUUID();
    
    $stmt = $pdo->prepare("
        INSERT INTO NhatKyWebhook (
            ID, GiaoDich_id, LoaiCongThanhToan, 
            DuLieuGuiDen, ThongTinHeader, 
            DaXacThuc, DaXuLy
        ) VALUES (?, NULL, 'VIETQR', ?, ?, ?, 0)
    ");
    $stmt->execute([
        $logId,
        json_encode($input ?: ['raw' => $rawBody], JSON_UNESCAPED_UNICODE),
        json_encode($headers ?: [], JSON_UNESCAPED_UNICODE),
        $daXacThuc
    ]);

    // -----------------------------------------------------
    // 3. PARSE DỮ LIỆU (tùy theo đối tác gửi webhook)
    // Ví dụ cấu trúc phổ biến từ Casso / SePay / tự mô phỏng:
    // {
    //   "tid": "...",
    //   "description": "DH20261021123456",
    //   "amount": 50000,
    //   "when": "2026-10-21 10:30:00"
    // }
    // -----------------------------------------------------
    // Bỏ qua nếu là giao dịch tiền ra (tiền chi) từ SePay
    if (isset($input['transferType']) && strtolower($input['transferType']) === 'out') {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Bỏ qua giao dịch tiền ra']);
        exit;
    }

    $orderId   = $input['code'] ?? $input['content'] ?? $input['description'] ?? $input['orderId'] ?? $input['addInfo'] ?? '';
    $amount    = (int)($input['transferAmount'] ?? $input['amount'] ?? $input['so_tien'] ?? 0);
    $transId   = $input['referenceCode'] ?? (string)($input['id'] ?? '') ?: ($input['tid'] ?? $input['transaction_id'] ?? $input['refNo'] ?? '');
    $status    = strtoupper($input['status'] ?? 'SUCCESS'); // SUCCESS / FAILED

    // Làm sạch mã đơn hàng (chỉ lấy phần mã đơn)
    $orderId = trim($orderId);
    // Nếu nội dung dài, cố gắng lấy mã đơn (thường đứng đầu)
    if (preg_match('/(DH\d{10,}|ORD\d+)/i', $orderId, $m)) {
        $orderId = $m[1];
    }

    if (empty($orderId) && $amount <= 0) {
        // Cập nhật log: không đủ dữ liệu
        $pdo->prepare("UPDATE NhatKyWebhook SET DaXuLy = 1 WHERE ID = ?")->execute([$logId]);
        
        http_response_code(200); // Vẫn trả 200 để không bị gửi lại liên tục
        echo json_encode(['success' => false, 'message' => 'Thiếu orderId hoặc amount']);
        exit;
    }

    // -----------------------------------------------------
    // 4. TÌM GIAO DỊCH TƯƠNG ỨNG
    // Ưu tiên tìm theo MaDonHang + trạng thái PENDING
    // -----------------------------------------------------
    $stmt = $pdo->prepare("
        SELECT ID, SoTien, TrangThai, CuaHang_id
        FROM GiaoDich
        WHERE MaDonHang = ?
          AND LoaiCongThanhToan = 'VIETQR'
          AND TrangThai = 'PENDING'
        ORDER BY ThoiGianTao DESC
        LIMIT 1
    ");
    $stmt->execute([$orderId]);
    $giaoDich = $stmt->fetch();

    // Nếu không tìm thấy theo mã đơn, thử tìm theo số tiền (kém chính xác hơn)
    if (!$giaoDich && $amount > 0) {
        $stmt = $pdo->prepare("
            SELECT ID, SoTien, TrangThai, CuaHang_id, MaDonHang
            FROM GiaoDich
            WHERE SoTien = ?
              AND LoaiCongThanhToan = 'VIETQR'
              AND TrangThai = 'PENDING'
              AND ThoiGianTao >= DATE_SUB(NOW(), INTERVAL 1 DAY)
            ORDER BY ThoiGianTao DESC
            LIMIT 1
        ");
        $stmt->execute([$amount]);
        $giaoDich = $stmt->fetch();
    }

    if (!$giaoDich) {
        // Không tìm thấy giao dịch phù hợp
        $pdo->prepare("UPDATE NhatKyWebhook SET DaXuLy = 1 WHERE ID = ?")->execute([$logId]);
        
        http_response_code(200);
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy giao dịch PENDING phù hợp']);
        exit;
    }

    // -----------------------------------------------------
    // 5. CẬP NHẬT TRẠNG THÁI GIAO DỊCH
    // Chống xử lý trùng: chỉ cập nhật nếu đang là PENDING
    // -----------------------------------------------------
    $newStatus = ($status === 'FAILED' || $status === 'CANCELLED') ? 'FAILED' : 'SUCCESS';

    $stmt = $pdo->prepare("
        UPDATE GiaoDich SET
            TrangThai = ?,
            MaGiaoDichCongThanhToan = ?,
            ThoiGianThanhToan = NOW(),
            ThoiGianCapNhat = NOW()
        WHERE ID = ?
          AND TrangThai = 'PENDING'
    ");
    $stmt->execute([
        $newStatus,
        $transId ?: null,
        $giaoDich['ID']
    ]);

    // -----------------------------------------------------
    // 6. CẬP NHẬT LOG WEBHOOK
    // -----------------------------------------------------
    $pdo->prepare("
        UPDATE NhatKyWebhook SET
            GiaoDich_id = ?,
            DaXacThuc = 1,
            DaXuLy = 1
        WHERE ID = ?
    ")->execute([$giaoDich['ID'], $logId]);

    // -----------------------------------------------------
    // 7. TRẢ VỀ THÀNH CÔNG
    // -----------------------------------------------------
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => "Đã cập nhật giao dịch {$giaoDich['ID']} → $newStatus",
        'giao_dich_id' => $giaoDich['ID']
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Ngay cả khi lỗi vẫn cố gắng trả 200 để bên gửi không retry liên tục
    // (và đã ghi log ở trên)
    http_response_code(200);
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi xử lý webhook: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
