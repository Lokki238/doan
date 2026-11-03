# Hướng dẫn Tuần 7 – Backend + Auth + CSDL (có chú thích chi tiết)

## Cấu trúc thư mục

```
DoAn/
├── config/
│   └── db.php              ← Kết nối MySQL + hàm generateUUID()
├── includes/
│   └── auth.php            ← isLoggedIn(), requireLogin(), requireOwner(), logout(), jsonResponse()
├── api/
│   ├── register.php        ← Đăng ký (tạo User + Shop)
│   ├── login.php           ← Đăng nhập
│   └── logout.php          ← Đăng xuất
├── dashboard.php           ← Trang tổng quan tạm sau khi login
├── html/
│   ├── login.html          ← Đã nối với api/login.php (có JS fetch)
│   └── register.html       ← Đã nối với api/register.php
├── css/ + js/              ← Giữ nguyên
└── README_TUAN7.md
```

## Giải thích cách các file hoạt động với nhau

### 1. Khi người dùng mở trang Đăng ký (register.html)
1. Người dùng điền form → bấm "Tạo tài khoản"
2. JavaScript bắt sự kiện submit → gửi FormData đến `api/register.php`
3. `register.php` nhận dữ liệu → validate → kiểm tra email trùng
4. Tạo UUID → mã hóa mật khẩu bằng `password_hash()`
5. Dùng **Transaction** thêm vào 2 bảng: `NguoiDung` + `CuaHang`
6. Lưu thông tin vào `$_SESSION` (tự động đăng nhập)
7. Trả JSON `{success: true}` → JavaScript chuyển hướng sang `dashboard.php`

### 2. Khi người dùng Đăng nhập (login.html)
1. Gửi email + mật khẩu đến `api/login.php`
2. Tìm user theo email trong database
3. Dùng `password_verify()` so sánh mật khẩu
4. Nếu đúng → lưu Session → trả JSON thành công
5. JavaScript chuyển hướng sang dashboard

### 3. Khi vào dashboard.php
- File này gọi `requireLogin()` ngay đầu file
- Nếu chưa có Session → tự động đẩy về trang login
- Nếu đã login → hiển thị tên + vai trò của người dùng

### 4. Khi bấm Đăng xuất
- Gọi `api/logout.php`
- Hàm `logout()` xóa sạch Session + cookie
- Chuyển về trang login

## Các bước triển khai

### Bước 1: Tạo Database
1. Mở phpMyAdmin
2. Tạo database tên `freshmart_payment` (collation: utf8mb4_unicode_ci)
3. Chạy script SQL 5 bảng (copy từ báo cáo Tuần 5)

### Bước 2: Cấu hình kết nối
Mở `config/db.php` và sửa:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'freshmart_payment');
define('DB_USER', 'root');      // đổi nếu khác
define('DB_PASS', '');          // đổi nếu có mật khẩu
```

### Bước 3: Chạy thử
- Đặt thư mục `DoAn` vào htdocs (XAMPP) hoặc www (Laragon)
- Mở: `http://localhost/DoAn/html/register.html`
- Đăng ký → sẽ tự nhảy vào dashboard

## Các khái niệm quan trọng cần nhớ khi bảo vệ

| Khái niệm              | Giải thích ngắn gọn                                      | File liên quan      |
|------------------------|----------------------------------------------------------|---------------------|
| Session                | Lưu trạng thái đăng nhập trên server                     | auth.php            |
| password_hash()        | Mã hóa mật khẩu 1 chiều (không giải mã được)             | register.php        |
| password_verify()      | So sánh mật khẩu nhập vào với hash trong DB              | login.php           |
| Transaction            | Đảm bảo nhiều câu SQL cùng thành công hoặc cùng thất bại | register.php        |
| UUID                   | ID dạng chuỗi ngẫu nhiên 36 ký tự                        | db.php              |
| Prepared Statement     | Chống SQL Injection                                      | Tất cả file API     |
| requireLogin()         | Bảo vệ trang, bắt buộc phải đăng nhập                    | dashboard.php       |

## Việc tiếp theo (Tuần 8)
- Trang cấu hình cổng thanh toán (chỉ Owner được vào)
- Viết các Adapter (VietQR, MoMo, VNPay, ZaloPay)
- API create_payment.php
