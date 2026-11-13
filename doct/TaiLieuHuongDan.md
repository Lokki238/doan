# ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH
## TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH
### KHOA CÔNG NGHỆ THÔNG TIN

---

# TÀI LIỆU HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG HỆ THỐNG
## HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ DÀNH CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ (FRESHMART & FRESHMART PAY)

**Sinh viên thực hiện:**
1. **Đinh Thế Chí Dũng** - MSSV: **25002710** (Trưởng nhóm / Backend)
2. **Nguyễn Tuấn Hưng** - MSSV: **25002711** (Frontend / Testing)

**Chuyên ngành:** Lập trình máy tính | **Khóa học:** 2025
**Lớp:** 25C2-LMT1
**Giảng viên hướng dẫn:** **Th.S. Mai Chiếm Tuấn**

Thành phố Hồ Chí Minh, tháng 11 năm 2026

---

# PHẦN 1: HƯỚNG DẪN CÀI ĐẶT VÀ TRIỂN KHAI

## 1.1. Phương pháp 1: Cài đặt trên máy chủ cục bộ (XAMPP)

### Bước 1: Tải và sao chép mã nguồn
1. Tải toàn bộ mã nguồn đồ án từ GitHub hoặc giải nén thư mục **DoAn**.
2. Sao chép thư mục dự án vào thư mục gốc của web server:
- Đối với **XAMPP**: **C:\xampp\htdocs\DoAn**

### Bước 2: Nạp cơ sở dữ liệu mẫu (Database Import)
1. Khởi động dịch vụ **Apache** và **MySQL** trong bảng điều khiển XAMPP Control Panel.
2. Mở trình duyệt web và truy cập công cụ **phpMyAdmin** tại địa chỉ: **http://localhost/phpmyadmin** (nếu Apache đã đổi sang cổng 8080 thì nhập: **http://localhost:8080/phpmyadmin**).
3. Tạo mới một cơ sở dữ liệu với tên: **freshmart_db**, chọn bảng mã (Collation) là **utf8mb4_unicode_ci**.
4. Chọn tab **Import (Nhập)** -> Nhấn **Choose File (Chọn tệp)** -> Chọn tệp: **csdl/database.sql** nằm trong thư mục đồ án -> Nhấn nút **Import (Thực hiện)** ở cuối trang.

**Kết quả:** Hệ thống sẽ tự động tạo đủ 5 bảng: **NguoiDung**, **CuaHang**, **CongThanhToan**, **GiaoDich**, **NhatKyWebhook** và chèn sẵn các tài khoản demo (Admin, Staff, User) cùng tài khoản ngân hàng MB Bank thụ hưởng.

### Bước 3: Cấu hình kết nối Cơ sở dữ liệu (csdl/db.php)
Mở tệp tin **csdl/db.php** bằng phần mềm soạn thảo (VS Code, Notepad++) và kiểm tra cấu hình kết nối local:

Cấu hình chuẩn trong tệp csdl/db.php:
$host = 'localhost';
$port = '3306';
$dbname = 'freshmart_db';
$username = 'root';
$password = ''; // Mặc định trên XAMPP mật khẩu để trống
$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

### Bước 4: Kiểm tra và chạy kiểm thử tự động (Smoke Test)
Để đảm bảo toàn bộ các hàm mã hóa bảo mật, phân quyền và kết nối CSDL hoạt động chính xác trước khi mở trình duyệt, mở Terminal (PowerShell hoặc Command Prompt) tại thư mục dự án và chạy lệnh:

**php php/tests/run_smoke_tests.php**

Màn hình sẽ hiển thị thông báo: **100% CÁC BÀI TEST CHỨC NĂNG VÀ BẢO MẬT ĐÃ ĐẠT!** (Bao gồm UUID RFC 4122, mã hóa AES-256, mật khẩu Bcrypt, phân quyền isPrivileged và chữ ký HMAC-SHA256).

### Bước 5: Khởi chạy và trải nghiệm trên trình duyệt
Mở trình duyệt web (Google Chrome, Cốc Cốc hoặc Edge) và truy cập đường dẫn:
**http://localhost/DoAn/**
hoặc: **http://localhost/DoAn/html/index.html**

---

## 1.2. Phương pháp 2: Triển khai nhanh bằng Docker Container
Đồ án đã được đóng gói sẵn file cấu hình **Dockerfile** dựa trên nền tảng PHP 8.2 Apache chính thống.
1. Mở cửa sổ dòng lệnh tại thư mục gốc của dự án.
2. Thực hiện lệnh build Docker image:
**docker build -t freshmart-pay .**
3. Khởi chạy Docker container với cổng chuyển tiếp:
**docker run -d -p 8080:10000 --name freshmart-container freshmart-pay**
4. Mở trình duyệt và truy cập: **http://localhost:8080/**. Container sẽ tự động thiết lập quyền hạn thư mục, cấu hình Apache Rewrite và kích hoạt toàn bộ hệ thống.

---

## 1.3. Phương pháp 3: Triển khai tự động trên Render Cloud + TiDB Cloud
Hệ thống được thiết kế theo tiêu chuẩn Cloud-Native, sẵn sàng cho việc triển khai trên hạ tầng đám mây không máy chủ (Serverless):

- **Tạo cơ sở dữ liệu trên TiDB Cloud:**
  - Đăng ký tài khoản miễn phí tại **tidbcloud.com** và khởi tạo cụm Serverless Cluster.
  - Tải chứng chỉ **ca-certificates.crt** và lấy thông tin Host, Port (4000), Username, Password.
  - Mở SQL Editor trên TiDB Cloud và thực thi toàn bộ nội dung tệp **csdl/database.sql**.

- **Triển khai Web Service trên Render:**
  - Đăng nhập **render.com**, chọn **New +** -> **Web Service** -> Kết nối với kho GitHub **Lokki238/doan**.
  - Runtime: Chọn **Docker**.
  - Instance Type: Chọn **Free**.
  - Cấu hình Environment Variables (Biến môi trường) kết nối CSDL:
    - **TIDB_HOST**: Hostname của cụm TiDB Cloud.
    - **TIDB_PORT**: **4000**
    - **TIDB_USER**: Tên tài khoản TiDB.
    - **TIDB_PASSWORD**: Mật khẩu kết nối.
    - **TIDB_DB_NAME**: **freshmart_db**
  - Nhấn nút **Create Web Service**. Render sẽ tự động kéo code, build Docker container và cấp phát tên miền SSL Let's Encrypt công khai: **https://doan-yteb.onrender.com**.

---

# PHẦN 2: HƯỚNG DẪN SỬ DỤNG DÀNH CHO KHÁCH HÀNG (CUSTOMER GUIDE)

## 2.1. Đăng ký tài khoản và Đăng nhập hệ thống

- **Đăng ký tài khoản mới:**
  - Truy cập menu đầu trang (Header) -> Nhấp vào nút **Đăng ký** hoặc truy cập trực tiếp **html/register.html**.
  - Điền đầy đủ các thông tin: Họ và tên, Email, Số điện thoại và Mật khẩu (tối thiểu 6 ký tự).
  - Nhấn nút **Đăng ký ngay**. Hệ thống sẽ mã hóa mật khẩu an toàn bằng thuật toán Bcrypt và tạo mới người dùng với vai trò mặc định là **Khách hàng (USER)**.

- **Đăng nhập hệ thống:**
  - Truy cập **html/login.html**, nhập Email và Mật khẩu đã đăng ký.
  - Sau khi xác thực thành công, thanh điều hướng Header sẽ tự động hiển thị tên người dùng và vai trò tài khoản (Khách hàng hoặc Quản trị viên).

---

## 2.2. Tìm kiếm, lọc sản phẩm và thêm vào giỏ hàng
1. **Duyệt xem sản phẩm:** Nhấp vào menu **Sản phẩm** (**html/products.html**).
2. **Bộ lọc thông minh:** Ở thanh bên trái, khách hàng có thể chọn lọc theo danh mục (Rau củ quả, Thịt & Hải sản, Sữa & Trứng, Gạo & Ngũ cốc, Đồ uống) hoặc lọc theo khoảng giá mong muốn.
3. **Thêm sản phẩm vào giỏ:** Trên từng thẻ sản phẩm, nhấp nút **Thêm vào giỏ**. Ngay lập tức:
- Nút bấm sẽ chuyển sang trạng thái xanh lá: **Đã thêm**.
- Hệ thống lưu trữ thông tin sản phẩm (ID, tên, đơn giá, ảnh, đơn vị) vào **localStorage** của trình duyệt.
- Huy hiệu số lượng giỏ hàng trên Header lập tức tự động tăng thêm 1 sản phẩm (+1).

---

## 2.3. Quản lý giỏ hàng và áp dụng mã voucher giảm giá
Nhấp vào biểu tượng Giỏ hàng trên thanh điều hướng để mở trang **html/cart.html**:
- **Điều chỉnh số lượng:** Nhấp nút **[+]** hoặc **[-]** để tăng/giảm số lượng sản phẩm. Tổng tiền của từng dòng và tổng đơn hàng sẽ tự động tính lại ngay lập tức mà không cần tải lại trang.
- **Xóa sản phẩm:** Nhấp vào biểu tượng thùng rác màu đỏ để loại bỏ sản phẩm khỏi giỏ hàng.
- **Áp dụng mã giảm giá (Voucher):** Nhập mã voucher ưu đãi vào ô khuyến mãi:
  - Nhập mã: **FRESHMART20** -> Nhấn nút **Áp dụng** -> Hệ thống tự động giảm ngay **20.000đ** vào tổng thanh toán của đơn hàng!

---

## 2.4. Đặt hàng và quy trình thanh toán quét mã VietQR tự động
1. Tại trang giỏ hàng, nhấp nút **Tiến hành thanh toán**. Hệ thống tự động kiểm tra phiên đăng nhập:
- Nếu khách hàng chưa đăng nhập: Hệ thống tự động chuyển hướng sang trang **html/login.html** để đăng nhập.
- Nếu đã đăng nhập: Chuyển thẳng đến trang xác nhận thanh toán **html/checkout.html**.
2. Điền địa chỉ nhận hàng, chọn phương thức vận chuyển (Giao hàng tiêu chuẩn 30.000đ hoặc Giao hàng nhanh 50.000đ).
3. Tại mục **Phương thức thanh toán**, chọn **Chuyển khoản qua mã VietQR (Tự động kích hoạt)** -> Nhấn nút **Đặt hàng ngay**.
4. Hệ thống tự động tạo mã hóa đơn duy nhất (ví dụ: **FM903964**) và chuyển khách hàng sang trang hiển thị mã QR: **html/payment_qr.html**:
- **Mã QR động NAPAS 247:** Hiển thị sắc nét, chứa sẵn thông tin Ngân hàng thụ hưởng, Số tài khoản, Tên chủ shop và **Số tiền chính xác đến từng đồng**.
- **Nội dung chuyển khoản:** Hiển thị mã đơn hàng duy nhất kèm nút **Sao chép (Copy)** tiện lợi để khách sao chép nhanh nếu chuyển khoản thủ công.
- **Đồng hồ đếm ngược 15 phút:** Nhắc nhở thời gian hiệu lực giữ đơn hàng.
- **Cơ chế tự động nhảy trạng thái (Webhook & Polling 2 giây):** Khách hàng mở app ngân hàng bất kỳ (Vietcombank, MB Bank, Techcombank, TPBank, MoMo...) quét mã QR và xác nhận chuyển tiền. Ngay khi tiền về tài khoản shop (chỉ mất 2-3 giây):
  - **Webhook kích hoạt:** Màn hình thanh toán tự động chuyển sang thông báo **"Thanh toán thành công!"** kèm hiệu ứng pháo hoa chúc mừng, giỏ hàng tự động được làm sạch và chuyển về trang quản lý đơn hàng mà khách hàng **không cần nhấn bất kỳ phím F5 hay tải lại trang nào**!

---

# PHẦN 3: HƯỚNG DẪN DÀNH CHO QUẢN TRỊ VIÊN VÀ NHÂN VIÊN (ADMIN & STAFF GUIDE)

## 3.1. Truy cập Bảng điều khiển quản trị (Dashboard)
1. Đăng nhập với tài khoản Chủ cửa hàng (**admin@freshmart.vn**) hoặc Nhân viên (**staff@freshmart.vn**).
2. Trên thanh menu Header, nhấp vào nút **Admin** hoặc truy cập đường dẫn: **/php/dashboard.php**.
3. Bảng điều khiển tổng quan cung cấp các số liệu kinh doanh thời gian thực:
- **Tổng doanh thu:** Tổng số tiền thực thu từ các đơn hàng có trạng thái **PAID (Đã thanh toán)**.
- **Số đơn hàng thành công:** Tổng số giao dịch đã thanh toán thành công qua VietQR.
- **Tỉ lệ thanh toán thành công:** Phần trăm đơn thanh toán tự động so với tổng đơn phát sinh.
- **Biểu đồ doanh thu 7 ngày gần nhất:** Được trực quan hóa bằng biểu đồ cột sinh động thông qua thư viện Chart.js.
- **Hệ thống thông báo Realtime:** Khi có khách tạo mã thanh toán, chuông trên thanh tiêu đề sẽ rung lắc, phát âm thanh "Ting 🔔" và hiển thị pop-up thông báo đơn hàng đang chờ thanh toán.

---

## 3.2. Quản lý danh sách giao dịch & đối soát hóa đơn
Truy cập menu **Giao dịch** (**/php/transactions.php**):
- Xem danh sách toàn bộ các hóa đơn trong hệ thống: Mã giao dịch, Tên khách hàng, Số tiền, Phương thức thanh toán, Thời gian tạo.
- **Bộ lọc trạng thái:** Lọc nhanh các đơn hàng theo: **Tất cả**, **Đã thanh toán (PAID)**, **Chờ thanh toán (PENDING)**, **Đã hủy / Hết hạn (EXPIRED)**.
- **Tra cứu đối soát:** Nhập mã hóa đơn hoặc số tài khoản khách hàng vào ô tìm kiếm để tra cứu thông tin mã tham chiếu ngân hàng (Bank Transaction ID).

---

## 3.3. Báo cáo & phân tích doanh thu qua biểu đồ trực quan
Truy cập menu **Báo cáo** (**/php/reports.php**):
- Theo dõi cơ cấu doanh thu theo từng kênh thanh toán (VietQR, Tiền mặt COD, Ví điện tử).
- Thống kê top sản phẩm tươi sạch bán chạy nhất trong tuần và trong tháng.
- Xuất dữ liệu báo cáo đối soát phục vụ quyết toán thuế và kế toán cửa hàng.

---

## 3.4. Cấu hình cổng thanh toán ngân hàng thụ hưởng (gateways.php)
**Lưu ý quan trọng:** Trang cấu hình cổng thanh toán (**/php/admin/gateways.php**) được bảo vệ nghiêm ngặt bằng hàm phân quyền **requireOwner()**. Chỉ tài khoản có vai trò **OWNER (Chủ cửa hàng)** mới có quyền truy cập và thay đổi.
Khi cửa hàng muốn đổi sang tài khoản ngân hàng khác để nhận tiền, thực hiện các bước sau:
1. Truy cập menu **Cổng thanh toán** trên thanh Sidebar.
2. Tại phần **Cấu hình VietQR (Ưu tiên)**:
- **Số tài khoản nhận tiền:** Nhập số tài khoản ngân hàng mới (Ví dụ: **0389336829**).
- **Mã ngân hàng (BIN / Code):** Chọn ngân hàng thụ hưởng từ danh sách chọn (Ví dụ: **MB Bank - Mã 970422**, **Vietcombank - Mã 970436**, **Techcombank - Mã 970407**, **ACB - Mã 970416**...).
- **Tên chủ tài khoản:** Nhập họ tên in hoa không dấu của chủ tài khoản (Ví dụ: **FRESHMART STORE** hoặc **DINH THE CHI DUNG**).
- **Trạng thái:** Chọn **Đang hoạt động**.
3. Nhấn nút **Lưu cấu hình VietQR**.

**Cơ chế cập nhật thời gian thực:** Ngay khi bấm Lưu, thông tin mới được ghi đè trực tiếp vào bảng **CongThanhToan** trong cơ sở dữ liệu TiDB Cloud. Mọi khách hàng đặt đơn ngay sau thời điểm đó sẽ quét mã VietQR trỏ thẳng về số tài khoản mới này mà **không cần phải sửa đổi bất kỳ dòng mã nguồn PHP hay khởi động lại máy chủ**!

---

## 3.5. Quản lý & Phân quyền người dùng trực tiếp (permissions.php)
Truy cập menu **Cấp quyền** (**/php/permissions.php**):
1. Giao diện hiển thị danh sách toàn bộ người dùng đã đăng ký tài khoản trong hệ thống.
2. **Tìm kiếm:** Nhập tên hoặc email người dùng vào ô tìm kiếm nhanh.
3. **Thay đổi vai trò (Role):** Tại cột "Vai trò", chọn cấp quyền mong muốn:
- **OWNER**: Chủ cửa hàng (toàn quyền hệ thống).
- **STAFF**: Nhân viên quản lý đơn hàng và đối soát.
- **USER**: Khách hàng mua sắm tiêu chuẩn.
4. Nhấn nút **Lưu quyền** tương ứng với người dùng đó. Hệ thống sẽ phát lệnh SQL cập nhật ngay lập tức vào CSDL.

---

## 3.6. Công cụ mô phỏng Webhook kiểm thử (simulate_webhook.php)
Nhằm phục vụ công tác chấm thi, nghiệm thu đồ án và kiểm thử chức năng thanh toán tự động mà **không cần phải chuyển tiền thật từ app ngân hàng**, nhóm tác giả đã xây dựng sẵn công cụ mô phỏng Webhook tại địa chỉ:
**https://doan-yteb.onrender.com/php/api/simulate_webhook.php**
(Hoặc trên máy local: **http://localhost/DoAn/php/api/simulate_webhook.php**)

**Quy trình thực hiện mô phỏng chuyển tiền:**
1. Khách hàng đặt một đơn hàng mới và đang đứng tại màn hình quét mã QR có mã đơn (Ví dụ: **FM903964**, số tiền **297000**).
2. Người kiểm thử mở tab mới truy cập công cụ **simulate_webhook.php**.
3. Nhập mã đơn hàng **FM903964** và số tiền **297000** -> Nhấn nút **Bắn tín hiệu Webhook giả lập**.
4. Hệ thống gửi gói tin JSON chuẩn xác đến **php/api/webhook_vietqr.php**. Ngay lập tức, quay lại tab màn hình quét mã QR của khách hàng, màn hình sẽ tự động nhảy sang **Thanh toán thành công** chỉ sau 1 giây!

---

# PHẦN 4: XỬ LÝ SỰ CỐ VÀ CÂU HỎI THƯỜNG GẶP (TROUBLESHOOTING & FAQ)

| Hiện tượng sự cố | Nguyên nhân chính | Cách khắc phục triệt để |
| :--- | :--- | :--- |
| **1. Lỗi "Database connection failed"** | Sai thông tin kết nối máy chủ MySQL/TiDB hoặc cổng 3306 bị ứng dụng khác chiếm dụng. | Mở tệp **csdl/db.php**, kiểm tra lại host, port, username, password. Đảm bảo dịch vụ MySQL trong XAMPP đã chuyển sang màu xanh (Running). Nếu dùng TiDB Cloud, kiểm tra kết nối mạng Internet. |
| **2. Lỗi 404 Not Found khi chuyển trang** | Web server Apache chưa bật module **mod_rewrite** hoặc tệp **.htaccess** bị thiếu. | Mở file **httpd.conf** của Apache, tìm dòng **#LoadModule rewrite_module modules/mod_rewrite.so** và bỏ dấu **#** ở đầu dòng. Đảm bảo file **.htaccess** nằm ở thư mục gốc của dự án. |
| **3. Lỗi "Headers already sent"** | Tệp tin PHP bị dính ký tự UTF-8 BOM ẩn hoặc khoảng trắng trước thẻ **&lt;?php**. | Hệ thống đã được xử lý triệt để: toàn bộ file đã xóa sạch BOM và có sẵn hàm **ob_start()** trong **php/includes/auth.php**. Nếu tự tạo file mới, hãy lưu tệp ở định dạng **UTF-8 without BOM**. |
| **4. Quét mã QR rồi nhưng web không tự nhảy trạng thái** | Khách hàng nhập sai nội dung chuyển khoản hoặc chuyển sai số tiền lẻ. | Yêu cầu khách hàng giữ nguyên nội dung chuyển khoản là mã đơn hàng (Ví dụ: **FM903964**) do mã VietQR tự động sinh ra. Có thể vào công cụ **simulate_webhook.php** để kích hoạt lại đơn nếu cần đối soát gấp. |
| **5. Bị chuyển hướng về trang login khi vào Dashboard** | Người dùng chưa đăng nhập hoặc tài khoản chỉ có quyền **USER** thông thường. | Đăng nhập bằng tài khoản Quản trị viên (**admin@freshmart.vn** / **Admin@123456**). Nếu dùng tài khoản khác, nhờ tài khoản Admin vào trang **permissions.php** để cấp quyền **STAFF** hoặc **OWNER**. |
| **6. Hình ảnh sản phẩm bị vỡ hoặc không hiển thị** | Đường dẫn ảnh mạng bên ngoài bị chặn hoặc chưa lưu ảnh cục bộ. | Hệ thống đã lưu trữ toàn bộ ảnh sản phẩm cốt lõi (Cà chua bi, Táo nhập khẩu...) trực tiếp trong thư mục **html/img/**. Hệ thống có sẵn fallback tự động nạp ảnh cục bộ giúp trang luôn hiển thị sắc nét 100%. |

---
**HẾT TÀI LIỆU HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG HỆ THỐNG**