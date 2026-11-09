# Hướng dẫn Tuần 8 – Adapter VietQR + Tạo yêu cầu thanh toán

## Cấu trúc file mới

```
DoAn/
├── includes/
│   ├── security.php                 ← Mã hóa / giải mã API Key (AES-256)
│   └── adapters/
│       └── VietQRAdapter.php        ← Tạo chuỗi VietQR chuẩn NAPAS
├── api/
│   ├── gateways.php                 ← GET/POST cấu hình cổng thanh toán
│   └── create_payment.php           ← Tạo giao dịch + sinh QR
├── admin/
│   └── gateways.php                 ← Trang quản trị (chỉ Owner)
└── README_TUAN8.md
```

## Luồng hoạt động chính

### 1. Cấu hình cổng VietQR (Owner)
1. Đăng nhập → vào **Cổng thanh toán**
2. Nhập số tài khoản + chọn ngân hàng + tên chủ TK
3. Bấm Lưu → dữ liệu được mã hóa (nếu có key) và lưu vào bảng `CongThanhToan`

### 2. Tạo yêu cầu thanh toán
1. Gọi API `POST /api/create_payment.php` với:
   - so_tien
   - ma_don_hang
   - loai_cong = "VIETQR"
2. Hệ thống:
   - Kiểm tra đăng nhập + quyền
   - Lấy cấu hình cổng VietQR của shop
   - Gọi `VietQRAdapter->createPayment()`
   - Lưu bản ghi vào bảng `GiaoDich` (TrangThai = PENDING)
   - Trả về `qr_data` (chuỗi EMVCo)
3. Frontend dùng thư viện qrcode.js để vẽ mã QR từ `qr_data`

## Cách test nhanh

1. Đăng nhập bằng tài khoản Owner
2. Vào trang `/admin/gateways.php`
3. Điền số tài khoản ngân hàng thật (hoặc test) → Lưu
4. Ở form bên dưới: nhập số tiền + mã đơn → bấm **Tạo mã VietQR**
5. Mã QR hiện ra → dùng app ngân hàng quét thử (sẽ ra đúng số tiền + nội dung)

## Giải thích các khái niệm quan trọng

| Thành phần              | Vai trò |
|-------------------------|--------|
| **Adapter Pattern**     | Mỗi cổng thanh toán có 1 class riêng (VietQRAdapter, MoMoAdapter...). Dễ mở rộng thêm cổng mới mà không sửa code cũ. |
| **encryptData()**       | Mã hóa API Key/Secret trước khi lưu DB bằng AES-256-CBC |
| **VietQR raw data**     | Chuỗi theo chuẩn EMVCo + NAPAS. App ngân hàng đọc chuỗi này để tự điền thông tin chuyển khoản. |
| **Trạng thái PENDING**  | Giao dịch vừa tạo, chờ khách thanh toán. Webhook (Tuần 9) sẽ đổi thành SUCCESS/FAILED. |

## Việc còn lại (có thể làm tiếp)

- Adapter MoMo / VNPay / ZaloPay (gọi sandbox API)
- Kết nối trang checkout.html (nếu có) gọi create_payment.php
- Trang danh sách giao dịch

## Lưu ý bảo mật

- File `includes/security.php` chứa `ENCRYPTION_KEY` → **đổi ngay** thành chuỗi ngẫu nhiên dài của bạn.
- Không bao giờ trả API Key / Secret Key ra frontend.
- Chỉ Owner mới được sửa cấu hình cổng.
