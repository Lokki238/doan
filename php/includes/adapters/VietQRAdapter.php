<?php
/**
 * =====================================================
 * FILE: includes/adapters/VietQRAdapter.php
 * Mục đích: Adapter tạo mã VietQR động theo chuẩn NAPAS
 * Tuần 8 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 *
 * VietQR là chuẩn QR thanh toán quốc gia của Việt Nam (NAPAS).
 * Khi quét mã, app ngân hàng sẽ tự điền số tiền + nội dung chuyển khoản.
 *
 * Cách hoạt động của class này:
 * 1. Nhận thông tin: số tài khoản, tên chủ TK, mã ngân hàng (BIN), số tiền, nội dung
 * 2. Xây dựng chuỗi QR theo chuẩn EMVCo (ISO 18004) + quy định của NAPAS
 * 3. Trả về chuỗi dữ liệu QR (raw data) để frontend vẽ thành hình ảnh QR
 *
 * Lưu ý quan trọng:
 * - Đây là bản implement đơn giản, đủ dùng cho đồ án
 * - Trong production nên dùng thư viện chính thức hoặc gọi API vietqr.io
 */

class VietQRAdapter {

    // Danh sách mã BIN một số ngân hàng phổ biến (dùng để tạo QR)
    // Bạn có thể bổ sung thêm nếu cần
    private static $bankBins = [
        'VCB'   => '970436', // Vietcombank
        'TCB'   => '970407', // Techcombank
        'MB'    => '970422', // MB Bank
        'ACB'   => '970416', // ACB
        'VPB'   => '970432', // VPBank
        'TPB'   => '970423', // TPBank
        'STB'   => '970403', // Sacombank
        'BIDV'  => '970418', // BIDV
        'VIB'   => '970441', // VIB
        'SHB'   => '970443', // SHB
        'OCB'   => '970448', // OCB
        'MSB'   => '970426', // MSB
        'SCB'   => '970429', // SCB
        'EIB'   => '970431', // Eximbank
        'LPB'   => '970449', // LienVietPostBank
        'SEAB'  => '970440', // SeABank
        'HDB'   => '970437', // HDBank
        'VBA'   => '970405', // Agribank
    ];

    /**
     * Tạo chuỗi dữ liệu VietQR (raw QR content)
     *
     * @param array $config   Cấu hình cổng (từ bảng CongThanhToan)
     *                        - MaNguoiBan : số tài khoản nhận tiền
     *                        - CauHinhThem: JSON chứa bank_code, account_name...
     * @param int    $amount  Số tiền (VND)
     * @param string $orderId Mã đơn hàng (dùng làm nội dung chuyển khoản)
     * @param string $description Mô tả thêm (tùy chọn)
     * @return array {
     *   success: bool,
     *   qr_data: string,     // Chuỗi để vẽ QR
     *   message: string
     * }
     */
    public function createPayment(array $config, int $amount, string $orderId, string $description = ''): array {
        try {
            // ----- Lấy thông tin từ cấu hình cổng -----
            $accountNo   = $config['MaNguoiBan'] ?? '';
            $extraConfig = [];
            
            if (!empty($config['CauHinhThem'])) {
                $extraConfig = is_string($config['CauHinhThem']) 
                    ? json_decode($config['CauHinhThem'], true) 
                    : $config['CauHinhThem'];
            }

            $bankCode    = strtoupper($extraConfig['bank_code'] ?? 'VCB');
            $accountName = $extraConfig['account_name'] ?? 'CHU CUA HANG';
            $bankBin     = self::$bankBins[$bankCode] ?? ($extraConfig['bank_bin'] ?? '970436');

            // Validate cơ bản
            if (empty($accountNo)) {
                return [
                    'success' => false,
                    'qr_data' => '',
                    'message' => 'Chưa cấu hình số tài khoản nhận tiền (MaNguoiBan)'
                ];
            }

            if ($amount <= 0) {
                return [
                    'success' => false,
                    'qr_data' => '',
                    'message' => 'Số tiền phải lớn hơn 0'
                ];
            }

            // Nội dung chuyển khoản: ưu tiên mã đơn hàng (dễ đối soát)
            $addInfo = $orderId;
            if (!empty($description)) {
                $addInfo = $orderId . ' ' . $description;
            }
            // Giới hạn độ dài nội dung theo quy định
            $addInfo = mb_substr($addInfo, 0, 25);

            // ----- Xây dựng chuỗi QR theo chuẩn VietQR / NAPAS -----
            $qrData = $this->buildVietQRString(
                $bankBin,
                $accountNo,
                $amount,
                $addInfo,
                $accountName
            );

            return [
                'success'      => true,
                'qr_data'      => $qrData,
                'bank_bin'     => $bankBin,
                'account_no'   => $accountNo,
                'account_name' => $accountName,
                'amount'       => $amount,
                'add_info'     => $addInfo,
                'message'      => 'Tạo mã VietQR thành công'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'qr_data' => '',
                'message' => 'Lỗi tạo VietQR: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Xây dựng chuỗi dữ liệu QR theo chuẩn EMVCo + NAPAS VietQR
     * 
     * Cấu trúc chính:
     * - Payload Format Indicator (00)
     * - Point of Initiation Method (01) = 12 (dynamic)
     * - Merchant Account Information (38) chứa thông tin NAPAS
     * - Transaction Currency (53) = 704 (VND)
     * - Transaction Amount (54)
     * - Country Code (58) = VN
     * - Additional Data Field (62) chứa nội dung chuyển khoản
     * - CRC (63)
     */
    private function buildVietQRString(
        string $bankBin,
        string $accountNo,
        int $amount,
        string $addInfo,
        string $accountName
    ): string {
        // Helper: tạo field theo format TLV (Tag + Length + Value)
        $tlv = function(string $tag, string $value): string {
            $len = str_pad(strlen($value), 2, '0', STR_PAD_LEFT);
            return $tag . $len . $value;
        };

        // --- Merchant Account Information (ID 38) ---
        // Theo quy định NAPAS VietQR
        $guid = $tlv('00', 'A000000727'); // GUID của NAPAS
        
        // Thông tin thụ hưởng
        $consumer = $tlv('00', $bankBin) . $tlv('01', $accountNo);
        $consumerInfo = $tlv('01', $consumer);
        
        $merchantAccInfo = $tlv('38', $guid . $consumerInfo);

        // --- Các trường bắt buộc khác ---
        $payload = '';
        $payload .= $tlv('00', '01');                    // Payload Format Indicator
        $payload .= $tlv('01', '12');                    // Point of Initiation = Dynamic QR
        $payload .= $merchantAccInfo;                    // Merchant Account Info
        $payload .= $tlv('53', '704');                   // Currency = VND
        $payload .= $tlv('54', (string)$amount);         // Amount
        $payload .= $tlv('58', 'VN');                    // Country Code

        // Additional Data Field Template (ID 62) - chứa nội dung CK
        if (!empty($addInfo)) {
            $addField = $tlv('08', $addInfo);            // Purpose of Transaction
            $payload .= $tlv('62', $addField);
        }

        // CRC (ID 63) - tính trên toàn bộ chuỗi + "6304"
        $payload .= '6304';
        $crc = $this->crc16($payload);
        $payload .= strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));

        return $payload;
    }

    /**
     * Tính CRC-16/CCITT-FALSE (dùng cho QR EMVCo)
     */
    private function crc16(string $data): int {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }
                $crc &= 0xFFFF;
            }
        }
        return $crc;
    }
}
