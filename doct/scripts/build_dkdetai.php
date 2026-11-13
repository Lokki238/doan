<?php
/**
 * ====================================================================
 * SCRIPT TẠO VÀ CẬP NHẬT PHIẾU ĐĂNG KÝ ĐỀ TÀI (DKdetai.docx / .doc / .md)
 * Trường Cao đẳng Lý Tự Trọng TP.HCM - Khoa Công nghệ Thông tin
 * ====================================================================
 */

set_time_limit(300);

$outDir = __DIR__ . '/..';
$downloadsDir = 'C:/Users/ddung/Downloads';
$templateDocx = 'C:/Users/ddung/.gemini/antigravity/brain/20fc47bf-ad27-4927-8c13-9bc8082e4065/.user_uploaded/media_1794568080940_b9c371a7.docx';
if (!file_exists($templateDocx)) {
    $templateDocx = 'C:/Users/ddung/.gemini/antigravity/brain/20fc47bf-ad27-4927-8c13-9bc8082e4065/.user_uploaded/media_1794294054593_b87c77b5.docx';
}

echo "Bắt đầu tạo tệp Phiếu đăng ký đề tài (DKdetai)...\n";

// 1. TẠO FILE DOCX CHUẨN TỪ TEMPLATE GỐC CỦA TRƯỜNG
$destDocx = $outDir . '/DKdetai.docx';
copy($templateDocx, $destDocx);

$zip = new ZipArchive();
if ($zip->open($destDocx) === true) {
    $xml = $zip->getFromName('word/document.xml');

    // 1.1 Cập nhật tên đề tài
    $oldTitle = "Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR &amp; ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam";
    $newTitle = "Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR &amp; ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam (FreshMart &amp; FreshMart Pay)";
    $xml = str_replace($oldTitle, $newTitle, $xml);

    // 1.2 Cập nhật bảng công nghệ thực tế (Mục V)
    $xml = str_replace('ReactJS / Next.js + Tailwind CSS / Ant Design', 'HTML5, CSS3, JavaScript (Vanilla ES6+), Bootstrap 5, Font Awesome, Chart.js', $xml);
    $xml = str_replace('Node.js (NestJS hoặc Express) hoặc html/css/js/php', 'PHP 8.2 Apache, Clean Layering (Presentation, API, Adapter, Database), PDO Prepared Statements', $xml);
    $xml = str_replace('PostgreSQL / MySQL', 'MySQL 8.0 (Localhost) &amp; TiDB Cloud Serverless (Production với chứng chỉ SSL/TLS)', $xml);
    $xml = str_replace('RESTful API, Webhook (callback từ VietQR, MoMo, ZaloPay, VNPay)', 'RESTful API (JSON), VietQR Open API, Webhook SePay (HMAC-SHA256), Polling 2s', $xml);
    $xml = str_replace('Next.js (full-stack) hoặc NestJS + React', 'PHP 8.2 Native + Bootstrap 5 + Adapter Pattern (Dễ dàng mở rộng đa cổng)', $xml);
    $xml = str_replace('TypeScript / JavaScript, Python (nếu dùng FastAPI)', 'PHP 8.2, JavaScript ES6+, SQL, HTML5/CSS3', $xml);
    $xml = str_replace('VS Code, Git, GitHub, Postman, Docker', 'VS Code, Git, GitHub (CI/CD), Docker Desktop, Postman, phpMyAdmin', $xml);
    $xml = str_replace('Figma', 'Figma (Prototype FreshMart Storefront &amp; Admin Dashboard)', $xml);
    $xml = str_replace('Jest, Postman, Playwright / Cypress', 'PHP CLI Smoke Tests (run_smoke_tests.php tự động kiểm tra UUID, AES-256, Bcrypt, HMAC, isPrivileged), Simulate Webhook tool, Postman', $xml);
    $xml = str_replace('ChatGPT, GitHub Copilot, Claude', 'Google Gemini (Antigravity), ChatGPT, Claude, GitHub Copilot', $xml);
    $xml = str_replace('Redis (cache/queue), Nginx, Vercel/Railway cho deploy', 'Docker Container, Apache Web Server, Render Cloud Web Service, TiDB Cloud Serverless', $xml);

    // 1.3 Cập nhật phạm vi triển khai (Mục IV.3)
    $xml = str_replace('&#x25A1; Máy tính cá nhân', '&#x2611; Máy tính cá nhân (XAMPP / Localhost)', $xml);
    $xml = str_replace('☐ Máy tính cá nhân', '☑ Máy tính cá nhân (XAMPP / Localhost)', $xml);
    $xml = str_replace('&#x2611; Web Server', '&#x2611; Web Server (Apache 2.4, Docker Engine)', $xml);
    $xml = str_replace('☑ Web Server', '☑ Web Server (Apache 2.4, Docker Engine)', $xml);
    $xml = str_replace('&#x2611; Cloud', '&#x2611; Cloud (Render Cloud Web Service + TiDB Cloud Serverless SSL/TLS)', $xml);
    $xml = str_replace('☑ Cloud', '☑ Cloud (Render Cloud Web Service + TiDB Cloud Serverless SSL/TLS)', $xml);

    // 1.4 Cập nhật nội dung ngoài phạm vi (Mục IV.4)
    $xml = str_replace('- Xây dựng toàn bộ nền tảng bán hàng (catalog sản phẩm, giỏ hàng, quản lý kho, vận chuyển)', '- Xây dựng toàn bộ nền tảng thương mại điện tử chuyên sâu (nhóm chỉ xây dựng cửa hàng mẫu FreshMart phục vụ quy trình kiểm thử thanh toán khép kín)', $xml);
    $xml = str_replace('- Ứng dụng mobile native cho khách hàng hoặc chủ shop', '- Ứng dụng mobile native cho khách hàng hoặc chủ shop (sử dụng giao diện Web Responsive đa nền tảng tối ưu trên mọi thiết bị)', $xml);
    $xml = str_replace('- Tích hợp cổng thanh toán quốc tế (PayPal, Stripe…)', '- Tích hợp cổng thanh toán quốc tế (PayPal, Stripe… - tập trung 100% cổng thanh toán nội địa Việt Nam và đồng tiền VNĐ)', $xml);

    // 1.5 Cập nhật sản phẩm dự kiến (Mục VI)
    $xml = str_replace('&#x25A1; File cài đặt/Deploy', '&#x2611; File cài đặt/Deploy (Dockerfile, script SQL TiDB Cloud, URL https://doan-yteb.onrender.com)', $xml);
    $xml = str_replace('☐ File cài đặt/Deploy', '☑ File cài đặt/Deploy (Dockerfile, script SQL TiDB Cloud, URL https://doan-yteb.onrender.com)', $xml);

    // 1.6 Cập nhật phân công đóng góp (Mục VIII)
    // Thay thế 100% tỷ lệ đóng góp thành 50% (Mức độ hoàn thành: 100%)
    // Cần tìm vị trí chính xác trong bảng
    $xml = preg_replace('/(<w:tc>.*?100%<\/w:t>.*?<\/w:tc>)/u', '<w:tc><w:tcPr><w:tcW w:w="1200" w:type="dxa"/></w:tcPr><w:p><w:pPr><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman"/><w:sz w:val="24"/></w:rPr><w:t>50% (Đạt 100%)</w:t></w:r></w:p></w:tc>', $xml, 2);

    // 1.7 Cập nhật ứng dụng AI (Mục IX)
    $xml = str_replace('&#x25A1; Gemini', '&#x2611; Gemini (Antigravity)', $xml);
    $xml = str_replace('☐ Gemini', '☑ Gemini (Antigravity)', $xml);
    $xml = str_replace('&#x25A1; Viết Test Case', '&#x2611; Viết Test Case', $xml);
    $xml = str_replace('☐ Viết Test Case', '☑ Viết Test Case', $xml);
    $xml = str_replace('&#x25A1; Sinh dữ liệu kiểm thử', '&#x2611; Sinh dữ liệu kiểm thử', $xml);
    $xml = str_replace('☐ Sinh dữ liệu kiểm thử', '☑ Sinh dữ liệu kiểm thử', $xml);

    $zip->addFromString('word/document.xml', $xml);
    $zip->close();
    echo "-> Đã cập nhật thành công tệp Word chuẩn: $destDocx\n";
} else {
    echo "Không thể mở tệp docx template!\n";
}

// 2. TẠO FILE TỔNG HỢP MARKDOWN (.md) SẠCH HOÀN TOÀN KHÔNG DẤU ` VÀ * ĐƠN LẺ
$mdContent = <<<'MD'
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

MD;

$mdPath = $outDir . '/DKdetai.md';
file_put_contents($mdPath, $mdContent);
echo "-> Đã tạo tệp Markdown (.md): $mdPath\n";

// 3. TẠO FILE WORD HTML (.doc)
$docHtml = <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>PHIẾU ĐĂNG KÝ ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH</title>
<style>
  @page { size: A4; margin: 25mm 20mm 20mm 25mm; }
  body { font-family: 'Times New Roman', Times, serif; font-size: 13pt; line-height: 1.45; text-align: justify; color: #000; }
  h1 { font-size: 15pt; font-weight: bold; text-align: center; text-transform: uppercase; margin: 15px 0 5px 0; }
  h2 { font-size: 13.5pt; font-weight: bold; margin: 16px 0 6px 0; }
  h3 { font-size: 13pt; font-weight: bold; margin: 12px 0 4px 0; }
  table { width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 12pt; }
  th, td { border: 1px solid #000; padding: 6px 8px; }
  th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
  .center { text-align: center; }
  .bold { font-weight: bold; }
  .no-border td { border: none; padding: 4px; }
</style>
</head>
<body>
<div class="center bold" style="font-size: 12pt;">
  KHOA CÔNG NGHỆ THÔNG TIN<br>
  TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH
</div>
<div class="center" style="margin: 4px 0 15px 0;">----------***----------</div>

<h1>PHIẾU ĐĂNG KÝ ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH</h1>
<div class="center" style="font-size: 12.5pt; margin-bottom: 15px;">
  Học kỳ: <b>II</b> &nbsp;&nbsp;&nbsp;&nbsp; Năm học: <b>2025 – 2026</b><br>
  Lớp: <b>25C2-LMT1</b> &nbsp;&nbsp;&nbsp;&nbsp; Ngành: <b>Lập trình máy tính</b> &nbsp;&nbsp;&nbsp;&nbsp; Khóa: <b>2025</b>
</div>

<h2>I. THÔNG TIN NHÓM / SINH VIÊN</h2>
<p>
  (1) Họ và tên: <b>Đinh Thế Chí Dũng</b> &nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002710</b> &nbsp;&nbsp;&nbsp;&nbsp; Lớp: <b>25C2-LMT1</b> (Trưởng nhóm / Backend)<br>
  (2) Họ và tên: <b>Nguyễn Tuấn Hưng</b> &nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002711</b> &nbsp;&nbsp;&nbsp;&nbsp; Lớp: <b>25C2-LMT1</b> (Thành viên / Frontend & Testing)
</p>

<h2>II. THÔNG TIN ĐỀ TÀI</h2>
<p><b>1. Tên đề tài:</b><br>
<b>Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR &amp; ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam (FreshMart &amp; FreshMart Pay)</b></p>

<p><b>2. Lĩnh vực của đề tài:</b><br>
☑ Web Application &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ☐ Mobile Application &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ☐ Desktop Application &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ☐ Game Development<br>
☑ E-Commerce &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ☑ Management Information System &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ☐ AI Application &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ☐ IoT / Application
</p>

<h2>III. MÔ TẢ ĐỀ TÀI</h2>
<p><b>1. Lý do chọn đề tài:</b><br>
Cửa hàng trực tuyến quy mô nhỏ tại Việt Nam ngày càng phổ biến nhưng chủ shop thường gặp khó khăn khi phải tự tích hợp và quản lý nhiều phương thức thanh toán khác nhau (VietQR, MoMo, ZaloPay, VNPay...). Việc này dẫn đến chi phí kỹ thuật cao, quy trình đối soát thủ công dễ nhầm lẫn và tiềm ẩn rủi ro lừa đảo bằng hóa đơn chuyển tiền giả (Fake Bill). Đề tài hướng đến xây dựng một hệ thống thanh toán đa cổng thông minh tích hợp VietQR và Webhook tự động với chi phí 0đ, giúp cửa hàng nhỏ tự động kích hoạt đơn hàng trong 1 đến 3 giây, tự động đối soát và nâng cao trải nghiệm mua sắm không tiền mặt.</p>

<p><b>2. Vấn đề cần giải quyết:</b><br>
(1) Tích hợp cổng thanh toán VietQR động chuẩn NAPAS 247 và cơ chế mở rộng đa cổng trên cùng một nền tảng;<br>
(2) Tự động sinh mã VietQR gắn mã đơn hàng và số tiền chính xác, đồng bộ trạng thái thanh toán theo thời gian thực (Real-time) qua Webhook và Polling ngầm;<br>
(3) Thông báo đơn hàng chờ thanh toán ngay lập tức (Real-time Notification) với chuông lắc, âm thanh và pop-up cho Nhân viên và Quản trị viên;<br>
(4) Cung cấp bảng điều khiển Dashboard và Báo cáo trực quan bằng biểu đồ để chủ shop theo dõi doanh thu và xuất file đối soát;<br>
(5) Đảm bảo an toàn bảo mật (chuẩn hóa dữ liệu 3NF, PDO Prepared Statements chống SQL Injection, mã hóa mật khẩu Bcrypt, bảo vệ khóa bằng AES-256 và chữ ký số HMAC-SHA256).</p>

<p><b>3. Đối tượng sử dụng:</b><br>
☑ Quản trị viên (Chủ cửa hàng - OWNER) &nbsp;&nbsp;&nbsp;&nbsp; ☑ Nhân viên (STAFF) &nbsp;&nbsp;&nbsp;&nbsp; ☑ Khách hàng (USER) &nbsp;&nbsp;&nbsp;&nbsp; ☑ Doanh nghiệp / Hộ kinh doanh nhỏ
</p>

<p><b>4. Mục tiêu của đề tài:</b><br>
- <b>Mục tiêu tổng quát:</b> Thiết kế và triển khai hoàn chỉnh hệ thống thanh toán thông minh tích hợp VietQR và Webhook tự động cho cửa hàng trực tuyến, triển khai chạy thực tế trên máy chủ đám mây.<br>
- <b>Mục tiêu cụ thể:</b><br>
1. Xây dựng website bán lẻ mẫu FreshMart cho phép khách hàng duyệt sản phẩm, quản lý giỏ hàng, áp mã giảm giá và thanh toán quét mã VietQR tự động.<br>
2. Xây dựng hệ thống cổng thanh toán FreshMart Pay: tự động nhận diện thanh toán qua Webhook trong 1-3 giây, cơ chế Idempotency chống cộng trùng tiền.<br>
3. Xây dựng trang quản trị Dashboard, phân quyền 3 cấp độ (Owner, Staff, User), cho phép đổi số tài khoản ngân hàng thụ hưởng nhận tiền ngay lập tức mà không cần sửa code.<br>
4. Đóng gói ứng dụng vào Docker Container và triển khai online 100% trên Render Cloud kết nối Cơ sở dữ liệu phân tán TiDB Cloud Serverless có mã hóa SSL/TLS.
</p>

<h2>IV. PHẠM VI ĐỀ TÀI</h2>
<p><b>1. Phạm vi chức năng:</b></p>
<table>
  <thead>
    <tr>
      <th style="width: 8%;">STT</th>
      <th style="width: 32%;">Chức năng</th>
      <th style="width: 45%;">Mô tả chi tiết</th>
      <th style="width: 15%;">Mức độ ưu tiên</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="center">1</td>
      <td class="bold">Quản lý tài khoản &amp; Phân quyền</td>
      <td>Đăng ký, đăng nhập, bảo mật Bcrypt, phân quyền 3 cấp (OWNER, STAFF, USER) trực tiếp trên giao diện</td>
      <td class="center">Cao</td>
    </tr>
    <tr>
      <td class="center">2</td>
      <td class="bold">Cấu hình cổng thanh toán VietQR</td>
      <td>Đổi số tài khoản ngân hàng thụ hưởng, mã ngân hàng (BIN), tên chủ shop thời gian thực lưu vào CSDL</td>
      <td class="center">Cao</td>
    </tr>
    <tr>
      <td class="center">3</td>
      <td class="bold">Tạo mã QR VietQR động NAPAS 247</td>
      <td>Sinh mã QR chứa sẵn số tài khoản, số tiền và mã đơn hàng duy nhất; đồng hồ đếm ngược 15 phút</td>
      <td class="center">Cao</td>
    </tr>
    <tr>
      <td class="center">4</td>
      <td class="bold">Xử lý Webhook &amp; Đối soát tự động</td>
      <td>Nhận tín hiệu chuyển khoản ngân hàng, xác thực chữ ký HMAC-SHA256, tự động nhảy trạng thái thành công trong 1-3s</td>
      <td class="center">Cao</td>
    </tr>
    <tr>
      <td class="center">5</td>
      <td class="bold">Thông báo Real-time cho Nhân viên</td>
      <td>Rung chuông, đếm số lượng đơn, phát âm thanh chuông báo và hiển thị pop-up khi có đơn chờ thanh toán</td>
      <td class="center">Cao</td>
    </tr>
    <tr>
      <td class="center">6</td>
      <td class="bold">Bảng điều khiển &amp; Báo cáo doanh thu</td>
      <td>Biểu đồ cột 7 ngày gần nhất (Chart.js), thống kê doanh thu hôm nay, lọc trạng thái và xuất file CSV</td>
      <td class="center">Trung bình</td>
    </tr>
    <tr>
      <td class="center">7</td>
      <td class="bold">Cửa hàng bán lẻ mẫu (FreshMart)</td>
      <td>Danh mục sản phẩm, bộ lọc giá thông minh, giỏ hàng localStorage, áp mã voucher ưu đãi FRESHMART20</td>
      <td class="center">Trung bình</td>
    </tr>
  </tbody>
</table>

<p><b>2. Phạm vi dữ liệu:</b><br>
- Cơ sở dữ liệu quan hệ chuẩn hóa 3NF gồm 5 bảng chính: NguoiDung, CuaHang, CongThanhToan, GiaoDich, NhatKyWebhook.<br>
- Dữ liệu cấu hình cổng thanh toán, đơn hàng, mã giao dịch đối soát ngân hàng, nhật ký payload Webhook.</p>

<p><b>3. Phạm vi triển khai:</b><br>
☑ Máy tính cá nhân (Máy chủ cục bộ XAMPP / Laragon)<br>
☑ Web Server (Apache 2.4, Docker Engine)<br>
☑ Cloud (Render Cloud Web Service + TiDB Cloud Serverless SSL/TLS)</p>

<p><b>4. Những nội dung không thuộc phạm vi đề tài:</b><br>
- Ứng dụng di động Native (sử dụng giao diện Web Responsive đa nền tảng tối ưu cho cả điện thoại và máy tính).<br>
- Tích hợp cổng thanh toán ngoại tệ quốc tế (tập trung 100% vào cổng thanh toán nội địa Việt Nam và đồng tiền VNĐ).<br>
- Hệ thống nghiệp vụ kế toán thuế chuyên sâu của doanh nghiệp lớn.</p>

<h2>V. CÔNG NGHỆ THỰC TẾ SỬ DỤNG</h2>
<table>
  <thead>
    <tr>
      <th style="width: 8%;">STT</th>
      <th style="width: 27%;">Thành phần</th>
      <th style="width: 65%;">Công nghệ thực hiện</th>
    </tr>
  </thead>
  <tbody>
    <tr><td class="center">1</td><td class="bold">Frontend</td><td>HTML5, CSS3, JavaScript (Vanilla ES6+), Bootstrap 5, Font Awesome, Chart.js</td></tr>
    <tr><td class="center">2</td><td class="bold">Backend</td><td>PHP 8.2 Apache, kiến trúc phân tầng (Clean Layering), Adapter Pattern, RESTful API</td></tr>
    <tr><td class="center">3</td><td class="bold">Database</td><td>MySQL 8.0 (Localhost) và TiDB Cloud Serverless (Production với chứng chỉ SSL Let's Encrypt)</td></tr>
    <tr><td class="center">4</td><td class="bold">API &amp; Webhook</td><td>VietQR Open API, Webhook SePay, cơ chế Polling 2 giây tự động bắt trạng thái</td></tr>
    <tr><td class="center">5</td><td class="bold">Bảo mật</td><td>PDO Prepared Statements (chống SQL Injection), password_hash (Bcrypt), AES-256, HMAC-SHA256, Idempotency key</td></tr>
    <tr><td class="center">6</td><td class="bold">Công cụ phát triển</td><td>VS Code, Git, GitHub, Docker Desktop, phpMyAdmin</td></tr>
    <tr><td class="center">7</td><td class="bold">Công cụ kiểm thử</td><td>Smoke Test Suite tự động bằng PHP CLI (run_smoke_tests.php), Simulate Webhook tool, Postman</td></tr>
    <tr><td class="center">8</td><td class="bold">Hạ tầng Cloud</td><td>Docker Container, Render Cloud (Web Service tự động CI/CD), TiDB Cloud</td></tr>
    <tr><td class="center">9</td><td class="bold">AI hỗ trợ</td><td>Google Gemini (Antigravity), ChatGPT, Claude, GitHub Copilot</td></tr>
  </tbody>
</table>

<h2>VI. SẢN PHẨM BÀN GIAO</h2>
<p>
  ☑ Source Code đầy đủ trên GitHub (nhánh main)<br>
  ☑ Cơ sở dữ liệu mẫu (database.sql)<br>
  ☑ File đóng gói / Deploy (Dockerfile, cấu hình Render Cloud)<br>
  ☑ Hệ thống chạy thực tế trên Internet: <b>https://doan-yteb.onrender.com</b><br>
  ☑ Tài khoản Demo phân quyền (Chủ shop, Nhân viên, Khách hàng)<br>
  ☑ Tài liệu hướng dẫn cài đặt và sử dụng chi tiết (TaiLieuHuongDan.docx / .doc / .md)<br>
  ☑ Báo cáo đồ án chuyên ngành theo chuẩn trường Cao đẳng Lý Tự Trọng (BAO_CAO_DO_AN_CHUYEN_NGANH.docx)<br>
  ☑ Slide thuyết trình bảo vệ (SLIDE_THUYET_TRINH.html / .docx)<br>
  ☑ Kế hoạch và kết quả kiểm thử tự động (TEST_PLAN_AND_CASES.docx)
</p>

<h2>VII. PHÂN CÔNG VÀ TỶ LỆ ĐÓNG GÓP</h2>
<table>
  <thead>
    <tr>
      <th style="width: 8%;">STT</th>
      <th style="width: 25%;">Họ và tên</th>
      <th style="width: 45%;">Nhiệm vụ chính phụ trách</th>
      <th style="width: 11%;">Hoàn thành</th>
      <th style="width: 11%;">Đóng góp</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="center">1</td>
      <td class="bold">Đinh Thế Chí Dũng</td>
      <td>Trưởng nhóm, Thiết kế CSDL 3NF TiDB Cloud, Xây dựng Backend API PHP 8.2, Tích hợp VietQR &amp; Webhook, Đóng gói Docker &amp; Triển khai Cloud Render</td>
      <td class="center">100%</td>
      <td class="center bold">50%</td>
    </tr>
    <tr>
      <td class="center">2</td>
      <td class="bold">Nguyễn Tuấn Hưng</td>
      <td>Thiết kế giao diện Storefront &amp; Admin Responsive, Xử lý JavaScript giỏ hàng &amp; chuông thông báo Real-time, Xây dựng kịch bản kiểm thử tự động, Biên soạn Báo cáo &amp; Slide</td>
      <td class="center">100%</td>
      <td class="center bold">50%</td>
    </tr>
  </tbody>
</table>

<h2>VIII. ỨNG DỤNG AI TRONG QUÁ TRÌNH THỰC HIỆN</h2>
<p>
  <b>1. Các công cụ AI sử dụng:</b><br>
  ☑ Google Gemini (Antigravity) &nbsp;&nbsp;&nbsp;&nbsp; ☑ ChatGPT &nbsp;&nbsp;&nbsp;&nbsp; ☑ Claude &nbsp;&nbsp;&nbsp;&nbsp; ☑ GitHub Copilot<br><br>
  <b>2. Công việc có ứng dụng AI hỗ trợ:</b><br>
  ☑ Phân tích yêu cầu và thiết kế kiến trúc hệ thống<br>
  ☑ Tối ưu hóa truy vấn CSDL và thuật toán bảo mật (Bcrypt, HMAC-SHA256, Idempotency)<br>
  ☑ Viết bộ kiểm thử tự động (Test Cases &amp; Smoke Test script)<br>
  ☑ Hỗ trợ chuẩn hóa định dạng tài liệu báo cáo và slide thuyết trình<br><br>
  <b>3. Cam kết sử dụng AI:</b><br>
  Nhóm cam kết không sử dụng AI để thay thế hoàn toàn quá trình học tập; có khả năng giải thích và làm chủ toàn bộ các thành phần chính của sản phẩm; đã kiểm tra và kiểm thử nghiêm ngặt toàn bộ mã nguồn.
</p>

<div style="margin-top: 40px;">
  <table class="no-border" style="width: 100%;">
    <tr>
      <td class="center bold" style="width: 50%;">
        Nhóm sinh viên thực hiện<br>
        <span style="font-weight: normal; font-style: italic;">(Ký và ghi rõ họ tên)</span>
        <br><br><br><br>
        Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng
      </td>
      <td class="center bold" style="width: 50%;">
        TP. HCM, ngày … tháng … năm 2026<br>
        Giảng viên hướng dẫn<br>
        <span style="font-weight: normal; font-style: italic;">(Ký và ghi rõ họ tên)</span>
        <br><br><br><br>
        Th.S. Mai Chiếm Tuấn
      </td>
    </tr>
  </table>
</div>

</body>
</html>
HTML;

$docPath = $outDir . '/DKdetai.doc';
file_put_contents($docPath, "\xEF\xBB\xBF" . $docHtml);
echo "-> Đã tạo tệp Word HTML (.doc): $docPath\n";

// 4. SAO CHÉP TOÀN BỘ SANG THƯ MỤC DOWNLOADS
if (is_dir($downloadsDir)) {
    copy($destDocx, $downloadsDir . '/DKdetai.docx');
    copy($docPath, $downloadsDir . '/DKdetai.doc');
    copy($mdPath, $downloadsDir . '/DKdetai.md');
    echo "-> Đã sao chép toàn bộ 3 file DKdetai sang $downloadsDir thành công!\n";
}

// 5. KIỂM TOÁN TÀI LIỆU SẠCH
$backticks = substr_count($mdContent, '`');
preg_match_all('/(?<!\*)\*(?!\*)/', $mdContent, $singleStars);
echo "\n--- KẾT QUẢ KIỂM TOÁN TÀI LIỆU DKdetai ---\n";
echo "Số ký tự backtick (`): " . $backticks . "\n";
echo "Số ký tự asterisk đơn lẻ (*): " . count($singleStars[0]) . "\n";
if ($backticks === 0 && count($singleStars[0]) === 0) {
    echo "XÁC NHẬN: TÀI LIỆU SẠCH 100% KHÔNG CÒN KÝ TỰ ` VÀ * ĐƠN LẺ!\n";
}

echo "\n>>> HOÀN THÀNH 100% TẠO VÀ CẬP NHẬT FILE DKdetai! <<<\n";
