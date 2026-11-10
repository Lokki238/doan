# KỊCH BẢN THUYẾT TRÌNH BẢO VỆ ĐỒ ÁN CHUYÊN NGÀNH (15 PHÚT)
## ĐỀ TÀI: THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ
### Trường Cao Đẳng Lý Tự Trọng TP.HCM - Khoa Công nghệ Thông tin - Khóa 2025

- **Giảng viên hướng dẫn:** ThS. Mai Chiếm Tuấn
- **Sinh viên 1:** Đinh Thế Chí Dũng (MSSV: 25002710) - Lớp: 25C2-LMT1 (Trưởng nhóm / Backend)
- **Sinh viên 2:** Nguyễn Tuấn Hưng (MSSV: 25002711) - Lớp: 25C2-LMT1 (Frontend / Testing)
- **File trình chiếu:** `doct/SLIDE_THUYET_TRINH.html` (Mở bằng trình duyệt Cốc Cốc / Chrome và nhấn F11)

---

### PHÂN VAI VÀ LỜI THOẠI CHI TIẾT TỪNG SLIDE (18 SLIDES)

#### SLIDE 1: MÀN HÌNH BÌA (1 Phút)
- **Người trình bày:** Đinh Thế Chí Dũng
- **Lời thoại gợi ý:**
  "Kính thưa quý Thầy, Cô trong Hội đồng chấm Đồ án chuyên ngành, em tên là Đinh Thế Chí Dũng, cùng bạn cùng nhóm là Nguyễn Tuấn Hưng, đại diện lớp 25C2-LMT1, ngành Lập trình máy tính, Khoa Công nghệ Thông tin - Trường Cao đẳng Lý Tự Trọng TP.HCM.
  Hôm nay, nhóm chúng em rất vinh dự được trình bày báo cáo đề tài: 'Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam', dưới sự hướng dẫn khoa học của ThS. Mai Chiếm Tuấn. Sau đây em xin phép bắt đầu phần báo cáo."

#### SLIDE 2: ĐẶT VẤN ĐỀ & TÍNH CẤP THIẾT (1.5 Phút)
- **Người trình bày:** Nguyễn Tuấn Hưng
- **Lời thoại gợi ý:**
  "Thưa quý Thầy Cô, khảo sát thực tế tại các cửa hàng trực tuyến nhỏ cho thấy 85% đơn hàng gặp khó khăn trong khâu thu tiền:
  1. Kẻ gian dùng thủ đoạn tạo hóa đơn chuyển tiền giả (Fake Bill) để lừa đảo chiếm đoạt hàng hóa.
  2. Việc đối soát thủ công bằng mắt thường rất chậm trễ, mất từ 15-30 phút/đơn.
  3. Các cổng thanh toán truyền thống như VNPay hay OnePay đòi hỏi thủ tục doanh nghiệp phức tạp và thu phí 1.5% - 2.5%/đơn.
  Do đó, nhóm chúng em đã nghiên cứu và phát triển giải pháp VietQR kết hợp Webhook tự động: Chi phí 0đ, tự động nhảy số sau 2 giây!"

#### SLIDE 3: MỤC TIÊU & PHẠM VI NGHIÊN CỨU (1 Phút)
- **Người trình bày:** Nguyễn Tuấn Hưng
- **Lời thoại gợi ý:**
  "Đồ án hướng đến 2 mục tiêu chính:
  Thứ nhất, xây dựng website bán hàng thực phẩm sạch FreshMart với giỏ hàng tương tác, bảo vệ bằng tài khoản đăng nhập.
  Thứ hai, xây dựng trung tâm thanh toán FreshMart Pay: tự động tạo mã VietQR động theo cấu hình ngân hàng trong CSDL và đón nhận Webhook tự động cập nhật đơn hàng."

#### SLIDE 4: KIẾN TRÚC HỆ THỐNG VÀ 6 THƯ MỤC CHUẨN (1.5 Phút)
- **Người trình bày:** Đinh Thế Chí Dũng
- **Lời thoại gợi ý:**
  "Hệ thống áp dụng kiến trúc phân tầng 4 lớp: Presentation (HTML/CSS/JS), REST API (PHP 8.2), Adapter Layer (VietQRAdapter) và Database Cloud (TiDB SSL). Toàn bộ mã nguồn dự án được tổ chức khoa học thành đúng 6 thư mục nghiệp vụ: html, php, css, js, csdl, doct, giúp việc bảo trì và nâng cấp trong tương lai vô cùng thuận tiện."

#### SLIDE 5: THIẾT KẾ CƠ SỞ DỮ LIỆU CHUẨN 3NF (1 Phút)
- **Người trình bày:** Đinh Thế Chí Dũng
- **Lời thoại gợi ý:**
  "Cơ sở dữ liệu gồm 5 bảng chuẩn hóa: NguoiDung, CuaHang, CongThanhToan, GiaoDich và NhatKyWebhook. Khóa chính sử dụng UUID 36 ký tự chống lộ số thứ tự, mật khẩu mã hóa Bcrypt và khóa API cổng thanh toán được mã hóa đối xứng AES-256."

#### SLIDE 6 & 7: GIẢI PHÁP CỐT LÕI - VIETQR VÀ WEBHOOK IDEMPOTENCY (2 Phút)
- **Người trình bày:** Đinh Thế Chí Dũng
- **Lời thoại gợi ý:**
  "Điểm đột phá kỹ thuật là Module VietQRAdapter tự động sinh chuỗi EMVCo có số tiền và mã hóa đơn theo đúng chuẩn NAPAS 247. Về phía Webhook, hệ thống áp dụng cơ chế Idempotency: dù ngân hàng gửi lặp lại thông báo 10 lần thì hệ thống vẫn chỉ cập nhật trạng thái đơn hàng thành công đúng 1 lần duy nhất, ngăn chặn tuyệt đối rủi ro trùng lặp dữ liệu."

#### SLIDE 8 & 9: GIAO DIỆN STOREFRONT & TRANG THANH TOÁN QR (1.5 Phút)
- **Người trình bày:** Nguyễn Tuấn Hưng
- **Lời thoại gợi ý:**
  "Giao diện FreshMart mang phong cách xanh sạch, giỏ hàng lưu LocalStorage có tính năng áp voucher giảm giá. Tại trang thanh toán payment_qr.html, mã QR hiện ra kèm đồng hồ đếm ngược 15 phút và tiến trình thăm dò (Polling) 2 giây tự động phát hiện tiền về mà khách không cần bấm tải lại trang."

#### SLIDE 10 & 11: DASHBOARD QUẢN TRỊ & PHÂN QUYỀN TRỰC TIẾP (1.5 Phút)
- **Người trình bày:** Đinh Thế Chí Dũng
- **Lời thoại gợi ý:**
  "Chủ cửa hàng có bảng điều khiển Dashboard hiển thị biểu đồ cột 7 ngày qua Chart.js. Tại trang gateways.php, chủ shop có thể thay đổi số tài khoản ngân hàng nhận tiền bất cứ lúc nào. Ngoài ra, trang permissions.php cho phép tìm kiếm người dùng và cấp quyền Owner/Staff/User cập nhật trực tiếp vào cơ sở dữ liệu."

#### SLIDE 12 & 13: BẢO MẬT & TRIỂN KHAI CLOUD THỰC TẾ (1 Phút)
- **Người trình bày:** Đinh Thế Chí Dũng
- **Lời thoại gợi ý:**
  "Đồ án đã được đóng gói Docker và deploy chính thức trên Render Cloud tại địa chỉ https://doan-yteb.onrender.com kết nối với CSDL TiDB Cloud. Mọi truy vấn đều sử dụng PDO Prepared Statements loại bỏ 100% lỗ hổng SQL Injection."

#### SLIDE 14 & 15: KIỂM THỬ VÀ ĐÁNH GIÁ TIẾN ĐỘ (1 Phút)
- **Người trình bày:** Nguyễn Tuấn Hưng
- **Lời thoại gợi ý:**
  "Nhóm đã xây dựng bộ tài liệu kiểm thử Word chi tiết và script kiểm thử tự động run_smoke_tests.php đạt tỉ lệ 100% Pass. Đồ án đã hoàn thành vượt mức toàn bộ 11 giai đoạn trong bảng tiến độ 15 tuần."

#### SLIDE 16: KỊCH BẢN DEMO THỰC TẾ TRƯỚC HỘI ĐỒNG (2 Phút)
- **Người phối hợp demo:** Cả 2 sinh viên
- **Thao tác:**
  1. Tuấn Hưng thao tác trên trang web: Chọn món -> Vào giỏ -> Bấm Thanh toán VietQR.
  2. Màn hình hiện mã VietQR -> Chí Dũng quét app ngân hàng thật (hoặc bấm nút 'Giả lập Webhook' trên trang QR).
  3. Màn hình tự động chúc mừng 'Thanh toán thành công!'.
  4. Mở Dashboard quản trị: Doanh thu nhảy số 297.000đ và đơn hàng chuyển sang SUCCESS ngay lập tức.

#### SLIDE 17 & 18: KẾT LUẬN & LỜI CẢM ƠN (1 Phút)
- **Người trình bày:** Đinh Thế Chí Dũng & Nguyễn Tuấn Hưng
- **Lời thoại gợi ý:**
  "Đồ án đã chứng minh giải pháp thanh toán tự động hoàn toàn khả thi và mang lại giá trị thực tế to lớn cho các cửa hàng nhỏ tại Việt Nam. Chúng em xin chân thành cảm ơn quý Thầy Cô trong Hội đồng đã lắng nghe và rất mong nhận được những góp ý quý báu ạ!"