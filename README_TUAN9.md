# Hướng dẫn Tuần 9 – Webhook + Dashboard đầy đủ

## Cấu trúc file mới / cập nhật

```
DoAn/
├── api/
│   ├── webhook_vietqr.php      ← Nhận webhook thật từ ngân hàng / Casso / SePay
│   ├── simulate_webhook.php    ← Giả lập webhook để test nhanh (rất hữu ích khi demo)
│   └── payments.php            ← API lấy danh sách + thống kê + dữ liệu biểu đồ
├── dashboard.php               ← Dashboard đầy đủ (thống kê, Chart.js, bộ lọc, giả lập)
└── README_TUAN9.md
```

## Luồng Webhook hoạt động như thế nào?

```
Khách quét QR → Chuyển khoản thành công
        ↓
Ngân hàng / dịch vụ trung gian (Casso, SePay, vietqr.io...) 
gửi POST đến:  https://your-domain.com/api/webhook_vietqr.php
        ↓
webhook_vietqr.php:
  1. Ghi log vào bảng NhatKyWebhook (luôn luôn)
  2. Parse mã đơn hàng + số tiền
  3. Tìm giao dịch PENDING tương ứng
  4. Cập nhật TrangThai → SUCCESS (hoặc FAILED)
  5. Trả HTTP 200
```

## Cách test Webhook khi chưa có public URL

1. Vào Dashboard
2. Tạo 1 giao dịch VietQR (qua trang Cổng thanh toán)
3. Ở phần **Giả lập Webhook** phía dưới Dashboard:
   - Điền mã đơn hàng vừa tạo
   - Chọn SUCCESS
   - Bấm **Gửi Webhook**
4. Trạng thái giao dịch sẽ đổi thành SUCCESS ngay lập tức
5. Doanh thu hôm nay + biểu đồ tự cập nhật

## Các tính năng Dashboard đã có

| Tính năng                    | Mô tả |
|-----------------------------|-------|
| Doanh thu hôm nay           | Tổng tiền các giao dịch SUCCESS trong ngày |
| Số giao dịch Thành công / Chờ / Thất bại | Thống kê real-time |
| Biểu đồ 7 ngày              | Chart.js cột doanh thu |
| Bộ lọc                      | Theo trạng thái + loại cổng |
| Danh sách giao dịch         | Có nút "Xác nhận" nhanh cho PENDING |
| Giả lập Webhook             | Test không cần public URL |

## Giải thích kỹ thuật quan trọng

### 1. Idempotent (chống xử lý trùng)
Webhook có thể bị gửi nhiều lần. Code chỉ `UPDATE ... WHERE TrangThai = 'PENDING'`  
→ Dù nhận 10 lần vẫn chỉ cập nhật 1 lần, an toàn.

### 2. Luôn ghi log trước khi xử lý
Dù dữ liệu sai hay không tìm thấy giao dịch, vẫn lưu vào `NhatKyWebhook`.  
→ Phục vụ đối soát và debug sau này (FR-09).

### 3. Trả HTTP 200 ngay cả khi lỗi nghiệp vụ
Bên gửi webhook (ngân hàng) sẽ retry nếu nhận 4xx/5xx.  
Trả 200 giúp tránh bị spam retry.

## Việc có thể mở rộng thêm (nếu còn thời gian)

- Webhook cho MoMo / VNPay / ZaloPay (cấu trúc tương tự)
- Trang chi tiết 1 giao dịch
- Xuất Excel danh sách giao dịch
- Thông báo realtime (WebSocket / polling)

## Checklist hoàn thành Tuần 7–9

- [x] Database + Auth (Tuần 7)
- [x] Cấu hình cổng + VietQR Adapter + create_payment (Tuần 8)
- [x] Webhook VietQR + Dashboard thống kê + Chart.js (Tuần 9)
