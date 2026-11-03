<?php
/**
 * =====================================================
 * FILE: config/db.php
 * Mục đích: Kết nối cơ sở dữ liệu MySQL + hàm hỗ trợ
 * Tuần 7 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 */

// =====================================================
// 1. CẤU HÌNH KẾT NỐI DATABASE
// Bạn PHẢI sửa 4 dòng dưới đây cho đúng với máy của bạn
// =====================================================
define('DB_HOST', 'localhost');          // Địa chỉ máy chủ MySQL (thường là localhost)
define('DB_NAME', 'freshmart_payment');  // Tên database bạn đã tạo trong phpMyAdmin
define('DB_USER', 'root');               // Tên người dùng MySQL (XAMPP/Laragon mặc định là root)
define('DB_PASS', '');                   // Mật khẩu MySQL (XAMPP mặc định để trống)
define('DB_CHARSET', 'utf8mb4');         // Bộ mã ký tự hỗ trợ tiếng Việt tốt nhất

/**
 * Hàm getDB() - Lấy đối tượng kết nối PDO
 * 
 * Cách hoạt động:
 * - Dùng static $pdo để chỉ tạo kết nối 1 lần duy nhất trong suốt request
 *   (nếu gọi nhiều lần thì vẫn trả về cùng 1 kết nối → tiết kiệm tài nguyên)
 * - PDO (PHP Data Objects) là cách kết nối database an toàn, hiện đại
 * - ATTR_ERRMODE = EXCEPTION: Khi lỗi sẽ ném Exception thay vì chỉ cảnh báo
 * - ATTR_DEFAULT_FETCH_MODE = FETCH_ASSOC: Kết quả trả về dạng mảng kết hợp (key là tên cột)
 * - ATTR_EMULATE_PREPARES = false: Dùng prepared statement thật của MySQL (chống SQL Injection tốt hơn)
 *
 * @return PDO Đối tượng kết nối database
 */
function getDB() {
    // Biến static chỉ được khởi tạo 1 lần, lần sau gọi hàm sẽ dùng lại giá trị cũ
    static $pdo = null;

    if ($pdo === null) {
        try {
            // Chuỗi DSN (Data Source Name) để PDO biết kết nối tới đâu
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            // Các tùy chọn bảo mật và hành vi của PDO
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Bắt lỗi bằng Exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Trả về mảng ['cột' => 'giá trị']
                PDO::ATTR_EMULATE_PREPARES   => false,                   // Chống SQL Injection tốt hơn
            ];
            
            // Tạo kết nối
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            // Nếu kết nối thất bại → trả về JSON lỗi (dùng cho API)
            // Trong thực tế nên ghi log lỗi thay vì hiện chi tiết cho người dùng
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            die(json_encode([
                'success' => false,
                'message' => 'Không thể kết nối cơ sở dữ liệu. Vui lòng kiểm tra file config/db.php'
            ], JSON_UNESCAPED_UNICODE));
        }
    }

    return $pdo;
}

/**
 * Hàm generateUUID() - Tạo chuỗi UUID phiên bản 4 (ngẫu nhiên)
 * 
 * Tại sao dùng UUID thay vì ID số tự tăng?
 * - An toàn hơn (khó đoán ID của bản ghi khác)
 * - Phù hợp với hệ thống phân tán sau này
 * - Schema của chúng ta dùng CHAR(36) nên cần UUID
 *
 * Cách hoạt động:
 * - random_bytes(16) tạo 16 byte ngẫu nhiên an toàn
 * - Sửa bit để tuân thủ chuẩn UUID v4 (version = 4, variant = RFC 4122)
 * - Định dạng thành chuỗi dạng: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
 *
 * @return string UUID dạng chuẩn (36 ký tự)
 */
function generateUUID() {
    // Tạo 16 byte ngẫu nhiên an toàn về mặt mật mã
    $data = random_bytes(16);
    
    // Đặt version = 4 (bit 12-15 của byte thứ 7)
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    
    // Đặt variant = 10xx (bit 6-7 của byte thứ 9)
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
    // Chuyển thành chuỗi hex và chèn dấu gạch ngang theo chuẩn UUID
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
