# KHOA CÔNG NGHỆ THÔNG TIN
## TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH

---

# PHIẾU ĐĂNG KÝ ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH
**Học kỳ:** II | **Năm học:** 2025 – 2026  
**Lớp:** 25C2-LMT1 | **Ngành:** Lập trình máy tính | **Khóa:** 2025  

---

## I. THÔNG TIN NHÓM / SINH VIÊN
1. Họ và tên: **Đinh Thế Chí Dũng** - MSSV: **25002710** - Lớp: **25C2-LMT1** (Trưởng nhóm / Backend)  
2. Họ và tên: **Nguyễn Tuấn Hưng** - MSSV: **25002711** - Lớp: **25C2-LMT1** (Thành viên / Frontend & Testing)  

---

## II. THÔNG TIN ĐỀ TÀI
**1. Tên đề tài:**  
**Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam (FreshMart & FreshMart Pay)**  

**2. Lĩnh vực của đề tài:**  
☑ Web Application  
☐ Mobile Application  
☐ Desktop Application  
☐ Game Development  
☑ E-Commerce  
☑ Management Information System  
☐ AI Application  
☐ IoT / Application  
☐ Khác  

---

## III. MÔ TẢ ĐỀ TÀI
**1. Lý do chọn đề tài:**  
Cửa hàng trực tuyến quy mô nhỏ tại Việt Nam ngày càng phổ biến nhưng chủ shop thường gặp khó khăn khi phải tự tích hợp và quản lý nhiều phương thức thanh toán khác nhau (VietQR, MoMo, ZaloPay, VNPay...). Việc này dẫn đến chi phí kỹ thuật cao, quy trình đối soát thủ công dễ nhầm lẫn và tiềm ẩn rủi ro lừa đảo bằng hóa đơn chuyển tiền giả (Fake Bill). Đề tài hướng đến xây dựng một hệ thống thanh toán đa cổng thông minh tích hợp VietQR và Webhook tự động với chi phí 0đ, giúp cửa hàng nhỏ tự động kích hoạt đơn hàng trong 1 đến 3 giây, tự động đối soát và nâng cao trải nghiệm mua sắm không tiền mặt.

**2. Vấn đề cần giải quyết:**  
(1) Tích hợp cổng thanh toán VietQR động chuẩn NAPAS 247 và cơ chế mở rộng đa cổng trên cùng một nền tảng;  
(2) Tự động sinh mã VietQR gắn mã đơn hàng và số tiền chính xác, đồng bộ trạng thái thanh toán theo thời gian thực (Real-time) qua Webhook và Polling ngầm;  
(3) Thông báo đơn hàng chờ thanh toán ngay lập tức (Real-time Notification) với chuông lắc, âm thanh và pop-up cho Nhân viên và Quản trị viên;  
(4) Cung cấp bảng điều khiển Dashboard và Báo cáo trực quan bằng biểu đồ để chủ shop theo dõi doanh thu và xuất file đối soát;  
(5) Đảm bảo an toàn bảo mật (chuẩn hóa dữ liệu 3NF, PDO Prepared Statements chống SQL Injection, mã hóa mật khẩu Bcrypt, bảo vệ khóa bằng AES-256 và chữ ký số HMAC-SHA256).

**3. Đối tượng sử dụng:**  
☑ Quản trị viên (Chủ cửa hàng - OWNER)  
☑ Nhân viên (STAFF)  
☑ Khách hàng (USER)  
☑ Doanh nghiệp / Hộ kinh doanh cá thể nhỏ  

**4. Mục tiêu của đề tài:**  
- **Mục tiêu tổng quát:** Thiết kế và triển khai hoàn chỉnh hệ thống thanh toán thông minh tích hợp VietQR và Webhook tự động cho cửa hàng trực tuyến, triển khai chạy thực tế trên máy chủ đám mây.  
- **Mục tiêu cụ thể:**  
  1. Xây dựng website bán lẻ mẫu FreshMart cho phép khách hàng duyệt sản phẩm, quản lý giỏ hàng, áp mã giảm giá và thanh toán quét mã VietQR tự động.  
  2. Xây dựng hệ thống cổng thanh toán FreshMart Pay: tự động nhận diện thanh toán qua Webhook trong 1-3 giây, cơ chế Idempotency chống cộng trùng tiền.  
  3. Xây dựng trang quản trị Dashboard, phân quyền 3 cấp độ (Owner, Staff, User), cho phép đổi số tài khoản ngân hàng thụ hưởng nhận tiền ngay lập tức mà không cần sửa code.  
  4. Đóng gói ứng dụng vào Docker Container và triển khai online 100% trên Render Cloud kết nối Cơ sở dữ liệu phân tán TiDB Cloud Serverless có mã hóa SSL/TLS.  

---

## IV. PHẠM VI ĐỀ TÀI
**1. Phạm vi chức năng:**  
| STT | Chức năng | Mô tả chi tiết | Mức độ ưu tiên |
| :--- | :--- | :--- | :--- |
| 1 | Quản lý tài khoản & Phân quyền | Đăng ký, đăng nhập, bảo mật Bcrypt, phân quyền 3 cấp (OWNER, STAFF, USER) trực tiếp trên giao diện | Cao |
| 2 | Cấu hình cổng thanh toán VietQR | Đổi số tài khoản ngân hàng thụ hưởng, mã ngân hàng (BIN), tên chủ shop thời gian thực lưu vào CSDL | Cao |
| 3 | Tạo mã QR VietQR động NAPAS 247 | Sinh mã QR chứa sẵn số tài khoản, số tiền và mã đơn hàng duy nhất; đồng hồ đếm ngược 15 phút | Cao |
| 4 | Xử lý Webhook & Đối soát tự động | Nhận tín hiệu chuyển khoản ngân hàng, xác thực chữ ký HMAC-SHA256, tự động nhảy trạng thái thành công trong 1-3s | Cao |
| 5 | Thông báo Real-time cho Nhân viên | Rung chuông, đếm số lượng đơn, phát âm thanh chuông báo và hiển thị pop-up khi có đơn chờ thanh toán | Cao |
| 6 | Bảng điều khiển & Báo cáo doanh thu | Biểu đồ cột 7 ngày gần nhất (Chart.js), thống kê doanh thu hôm nay, lọc trạng thái và xuất file CSV | Trung bình |
| 7 | Cửa hàng bán lẻ mẫu (FreshMart) | Danh mục sản phẩm, bộ lọc giá thông minh, giỏ hàng localStorage, áp mã voucher ưu đãi FRESHMART20 | Trung bình |

**2. Phạm vi dữ liệu:**  
- Cơ sở dữ liệu quan hệ chuẩn hóa 3NF gồm 5 bảng chính: NguoiDung, CuaHang, CongThanhToan, GiaoDich, NhatKyWebhook.  
- Dữ liệu cấu hình cổng thanh toán, đơn hàng, mã giao dịch đối soát ngân hàng, nhật ký payload Webhook.  

**3. Phạm vi triển khai:**  
☑ Máy tính cá nhân (Máy chủ cục bộ XAMPP / Laragon)  
☑ Web Server (Apache 2.4, Docker Engine)  
☑ Cloud (Render Cloud Web Service + TiDB Cloud Serverless SSL/TLS)  

**4. Những nội dung không thuộc phạm vi đề tài:**  
- Ứng dụng di động Native (sử dụng giao diện Web Responsive đa nền tảng tối ưu cho cả điện thoại và máy tính).  
- Tích hợp cổng thanh toán ngoại tệ quốc tế (tập trung 100% vào cổng thanh toán nội địa Việt Nam và đồng tiền VNĐ).  
- Hệ thống nghiệp vụ kế toán thuế chuyên sâu của doanh nghiệp lớn.  

---

## V. CÔNG NGHỆ THỰC TẾ SỬ DỤNG
| STT | Thành phần | Công nghệ thực hiện |
| :--- | :--- | :--- |
| 1 | **Frontend** | HTML5, CSS3, JavaScript (Vanilla ES6+), Bootstrap 5, Font Awesome, Chart.js |
| 2 | **Backend** | PHP 8.2 Apache, kiến trúc phân tầng (Clean Layering), Adapter Pattern, RESTful API |
| 3 | **Database** | MySQL 8.0 (Localhost) và TiDB Cloud Serverless (Production với chứng chỉ SSL Let's Encrypt) |
| 4 | **API & Webhook** | VietQR Open API, Webhook SePay, cơ chế Polling 2 giây tự động bắt trạng thái |
| 5 | **Bảo mật** | PDO Prepared Statements (chống SQL Injection), password_hash (Bcrypt), AES-256, HMAC-SHA256, Idempotency key |
| 6 | **Công cụ phát triển** | VS Code, Git, GitHub, Docker Desktop, phpMyAdmin |
| 7 | **Công cụ kiểm thử** | Smoke Test Suite tự động bằng PHP CLI (run_smoke_tests.php), Simulate Webhook tool, Postman |
| 8 | **Hạ tầng Cloud** | Docker Container, Render Cloud (Web Service tự động CI/CD), TiDB Cloud |
| 9 | **AI hỗ trợ** | Google Gemini (Antigravity), ChatGPT, Claude |

---

## VI. SẢN PHẨM BÀN GIAO
☑ Source Code đầy đủ trên GitHub (nhánh main)  
☑ Cơ sở dữ liệu mẫu (database.sql)  
☑ File đóng gói / Deploy (Dockerfile, cấu hình Render Cloud)  
☑ Hệ thống chạy thực tế trên Internet: **https://doan-yteb.onrender.com**  
☑ Tài khoản Demo phân quyền (Chủ shop, Nhân viên, Khách hàng)  
☑ Tài liệu hướng dẫn cài đặt và sử dụng chi tiết (TaiLieuHuongDan.docx / .doc / .md)  
☑ Báo cáo đồ án chuyên ngành theo chuẩn trường Cao đẳng Lý Tự Trọng (BAO_CAO_DO_AN_CHUYEN_NGANH.docx)  
☑ Slide thuyết trình bảo vệ (SLIDE_THUYET_TRINH.html / .docx)  
☑ Kế hoạch và kết quả kiểm thử tự động (TEST_PLAN_AND_CASES.docx)  

---

## VII. KẾ HOẠCH THỰC HIỆN SƠ BỘ (15 TUẦN)
| STT | Giai đoạn | Thời gian dự kiến | Kết quả thực hiện |
| :--- | :--- | :--- | :--- |
| 1 | Khảo sát & xác định bài toán | Tuần 1–2 | Báo cáo khảo sát, mô tả bài toán, phạm vi, yêu cầu sơ bộ |
| 2 | Phân tích yêu cầu | Tuần 3 | FR/NFR, Actor, Use Case, Use Case Diagram, User Story |
| 3 | Thiết kế kiến trúc hệ thống | Tuần 4 | Kiến trúc HT 4 tầng, Clean Layering, CSDL, API, FE/BE, Auth |
| 4 | Thiết kế cơ sở dữ liệu | Tuần 5 | ERD 8 thực thể, chuẩn hóa 3NF, Script CSDL TiDB, Data Dictionary |
| 5 | Thiết kế UI/UX | Tuần 6 | User Flow, Prototype giao diện FreshMart Storefront & Admin Portal |
| 6 | Phát triển 1–3 (nền tảng + nghiệp vụ) | Tuần 7–9 | Auth, CRUD, sinh mã VietQR, cấu hình đa cổng, hoàn thành 70-80% |
| 7 | Tích hợp hệ thống | Tuần 10 | Tích hợp Webhook tự động, thông báo Realtime, hoàn thiện 100% |
| 8 | Kiểm thử | Tuần 11 | Test Plan, 14 Test Cases, Smoke Test tự động đạt 100% |
| 9 | Hoàn thiện | Tuần 12 | Tối ưu UI/UX, phân quyền Owner/Staff/User, Responsive, Validation |
| 10 | Viết báo cáo & chuẩn bị bảo vệ | Tuần 13–14 | Báo cáo chuẩn mẫu trường, Slide thuyết trình, Tài liệu hướng dẫn |
| 11 | Bảo vệ đồ án | Tuần 15 | Sẵn sàng báo cáo, demo hệ thống trực tiếp và phản biện |

---

## VIII. PHÂN CÔNG VÀ TỶ LỆ ĐÓNG GÓP
| STT | Họ và tên | Nhiệm vụ chính phụ trách | Mức độ hoàn thành | Tỷ lệ đóng góp |
| :--- | :--- | :--- | :--- | :--- |
| 1 | **Đinh Thế Chí Dũng** | Trưởng nhóm, Thiết kế CSDL 3NF TiDB Cloud, Xây dựng Backend API PHP 8.2, Tích hợp VietQR & Webhook, Đóng gói Docker & Triển khai Cloud Render | 100% | 50% |
| 2 | **Nguyễn Tuấn Hưng** | Thiết kế giao diện Storefront & Admin Responsive, Xử lý JavaScript giỏ hàng & chuông thông báo Real-time, Xây dựng kịch bản kiểm thử tự động, Biên soạn Báo cáo & Slide | 100% | 50% |

---

## IX. ỨNG DỤNG AI TRONG QUÁ TRÌNH THỰC HIỆN
**1. Các công cụ AI sử dụng:**  
☑ Google Gemini (Antigravity)  
☑ ChatGPT  
☑ Claude  
☑ GitHub Copilot  

**2. Công việc có ứng dụng AI hỗ trợ:**  
☑ Phân tích yêu cầu và thiết kế kiến trúc hệ thống  
☑ Tối ưu hóa truy vấn CSDL và thuật toán bảo mật (Bcrypt, HMAC-SHA256, Idempotency)  
☑ Viết bộ kiểm thử tự động (Test Cases & Smoke Test script)  
☑ Hỗ trợ chuẩn hóa định dạng tài liệu báo cáo và slide thuyết trình  

**3. Cam kết sử dụng AI:**  
Nhóm cam kết:
- Không sử dụng AI để thay thế hoàn toàn quá trình học tập và thực hiện đồ án.
- Có khả năng giải thích và làm chủ toàn bộ các thành phần chính của sản phẩm.
- Đã kiểm tra, xác minh và kiểm thử nghiêm ngặt toàn bộ mã nguồn do AI gợi ý.
- Chịu trách nhiệm hoàn toàn về tính đúng đắn, an toàn và chất lượng của sản phẩm.

---

## X. KẾT QUẢ VÀ CHỮ KÝ XÁC NHẬN

| Nhóm sinh viên thực hiện (Ký và ghi rõ họ tên) | TP. HCM, ngày … tháng … năm 2026<br>Giảng viên hướng dẫn (Ký và ghi rõ họ tên) |
| :---: | :---: |
| <br><br><br>**Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng** | <br><br><br>**Th.S. Mai Chiếm Tuấn** |
