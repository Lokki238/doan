<?php
/**
 * =====================================================
 * FILE: api/simulate_webhook.php
 * Mục đích: Giả lập webhook VietQR / Ngân hàng để test realtime
 * =====================================================
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../csdl/db.php';
require_once __DIR__ . '/../includes/auth.php';

// Chỉ cho phép người đã đăng nhập gọi
if (!isLoggedIn()) {
    jsonResponse(false, 'Cần đăng nhập để giả lập webhook', [], 401);
}

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

$maDonHang = trim($input['ma_don_hang'] ?? '');
$soTien    = (int)($input['so_tien'] ?? 0);
$status    = strtoupper($input['status'] ?? 'SUCCESS');

if (empty($maDonHang)) {
    jsonResponse(false, 'Thiếu ma_don_hang', [], 400);
}

// Giả lập dữ liệu webhook gửi sang webhook_vietqr.php
$fakeWebhookData = [
    'code'        => $maDonHang,
    'description' => $maDonHang,
    'content'     => "Thanh toan don $maDonHang",
    'amount'      => $soTien,
    'transferAmount' => $soTien,
    'tid'         => 'SIM' . time(),
    'referenceCode' => 'WH' . time() . mt_rand(100, 999),
    'status'      => $status,
    'when'        => date('Y-m-d H:i:s'),
    'source'      => 'simulate'
];

$curlSuccess = false;
$webhookResponse = null;

// Thử gọi qua cURL trước
if (function_exists('curl_init') && !empty($_SERVER['HTTP_HOST'])) {
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $webhookUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/webhook_vietqr.php';

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $webhookUrl,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($fakeWebhookData),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response && $httpCode === 200) {
        $webhookResponse = json_decode($response, true);
        if (!empty($webhookResponse['success'])) {
            $curlSuccess = true;
        }
    }
}

// Nếu cURL chưa cập nhật thành công (ví dụ do loopback mạng trên container), trực tiếp cập nhật DB
if (!$curlSuccess) {
    try {
        $pdo = getDB();
        $logId = generateUUID();

        // 1. Tìm giao dịch
        $stmt = $pdo->prepare("SELECT ID, SoTien FROM giaodich WHERE MaDonHang = ? AND TrangThai = 'PENDING' ORDER BY ThoiGianTao DESC LIMIT 1");
        $stmt->execute([$maDonHang]);
        $gd = $stmt->fetch();

        $gdId = $gd ? $gd['ID'] : null;

        // 2. Ghi nhật ký webhook
        $pdo->prepare("
            INSERT INTO nhatkywebhook (
                ID, GiaoDich_id, LoaiCongThanhToan, DuLieuGuiDen, ThongTinHeader, DaXacThuc, DaXuLy, ThoiGianTao
            ) VALUES (?, ?, 'VIETQR', ?, ?, 1, 1, NOW())
        ")->execute([
            $logId,
            $gdId,
            json_encode($fakeWebhookData, JSON_UNESCAPED_UNICODE),
            json_encode(['User-Agent' => 'FreshMart-Simulator'], JSON_UNESCAPED_UNICODE)
        ]);

        // 3. Cập nhật giao dịch thành SUCCESS
        if ($gdId) {
            $pdo->prepare("
                UPDATE giaodich SET
                    TrangThai = 'SUCCESS',
                    MaGiaoDichCongThanhToan = ?,
                    ThoiGianThanhToan = NOW(),
                    ThoiGianCapNhat = NOW()
                WHERE ID = ?
            ")->execute([$fakeWebhookData['referenceCode'], $gdId]);
        }

        $curlSuccess = true;
        $webhookResponse = [
            'success' => true,
            'message' => "Đã mô phỏng webhook thành công cho đơn $maDonHang qua direct DB fallback",
            'giao_dich_id' => $gdId
        ];
    } catch (Exception $e) {
        jsonResponse(false, 'Lỗi mô phỏng webhook: ' . $e->getMessage(), [], 500);
    }
}

jsonResponse(true, "Mô phỏng Webhook thành công! Đơn hàng $maDonHang đã được xác nhận thanh toán.", [
    'ma_don_hang'      => $maDonHang,
    'status'           => 'SUCCESS',
    'webhook_response' => $webhookResponse
]);
