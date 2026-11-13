<?php
/**
 * ====================================================================
 * SCRIPT TẠO TÀI LIỆU HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG (BẢN SẠCH KHÔNG DẤU ` VÀ *)
 * Trường Cao đẳng Lý Tự Trọng TP.HCM - Khoa Công nghệ Thông tin
 * ====================================================================
 */

set_time_limit(300);

$outDir = __DIR__ . '/..';
$downloadsDir = 'C:/Users/ddung/Downloads';

echo "Bắt đầu cập nhật Tài liệu Hướng dẫn sạch (TaiLieuHuongDan)...\n";

$guideHtml = <<<'HTML'
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>TÀI LIỆU HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG HỆ THỐNG</title>
<style>
  @page {
    size: A4;
    margin: 25mm 20mm 20mm 30mm;
  }
  body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 13pt;
    line-height: 1.5;
    color: #000;
    text-align: justify;
  }
  .page-break {
    page-break-after: always;
  }
  .center {
    text-align: center;
  }
  .bold {
    font-weight: bold;
  }
  .cover-box {
    border: 3px double #000;
    padding: 35px 25px;
    min-height: 920px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .header-org {
    font-size: 13pt;
    font-weight: bold;
    text-align: center;
    line-height: 1.35;
    text-transform: uppercase;
  }
  .header-line {
    width: 220px;
    height: 1.5px;
    background-color: #000;
    margin: 8px auto 25px auto;
  }
  h1.guide-title {
    font-size: 20pt;
    font-weight: bold;
    text-align: center;
    margin: 30px 0 15px 0;
    text-transform: uppercase;
    line-height: 1.3;
    color: #047857;
  }
  h2.guide-subtitle {
    font-size: 14.5pt;
    font-weight: bold;
    text-align: center;
    margin-bottom: 25px;
    text-transform: uppercase;
    color: #1e293b;
    line-height: 1.4;
  }
  .info-block {
    font-size: 13.5pt;
    line-height: 1.7;
    margin-top: 35px;
  }
  .footer-date {
    font-size: 13.5pt;
    font-weight: bold;
    text-align: center;
    margin-top: 45px;
  }
  h1.part-title {
    font-size: 15pt;
    font-weight: bold;
    text-transform: uppercase;
    text-align: left;
    margin-top: 25px;
    margin-bottom: 15px;
    color: #065f46;
    border-bottom: 2px solid #065f46;
    padding-bottom: 6px;
  }
  h2.sec-title {
    font-size: 13.5pt;
    font-weight: bold;
    margin-top: 18px;
    margin-bottom: 10px;
    color: #0f172a;
  }
  h3.subsec-title {
    font-size: 13pt;
    font-weight: bold;
    margin-top: 14px;
    margin-bottom: 8px;
    color: #334155;
  }
  table.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
    font-size: 12pt;
  }
  table.data-table th, table.data-table td {
    border: 1px solid #333;
    padding: 8px 10px;
  }
  table.data-table th {
    background-color: #f1f5f9;
    font-weight: bold;
    text-align: center;
  }
  .callout-box {
    background-color: #f0fdf4;
    border-left: 5px solid #10b981;
    padding: 12px 18px;
    margin: 15px 0;
    border-radius: 4px;
  }
  .callout-warn {
    background-color: #fffbeb;
    border-left: 5px solid #f59e0b;
    padding: 12px 18px;
    margin: 15px 0;
    border-radius: 4px;
  }
  .code-block {
    background-color: #f8fafc;
    color: #0f172a;
    border: 1px solid #cbd5e1;
    border-left: 4px solid #0284c7;
    border-radius: 4px;
    padding: 12px 16px;
    font-family: Consolas, 'Courier New', monospace;
    font-size: 11pt;
    line-height: 1.45;
    white-space: pre-wrap;
    margin: 12px 0;
  }
  ul, ol {
    margin-top: 6px;
    margin-bottom: 10px;
    padding-left: 28px;
  }
  li {
    margin-bottom: 6px;
  }
  .text-indent {
    text-indent: 1.27cm;
  }
</style>
</head>
<body>

<!-- TRANG BÌA -->
<div class="cover-box center">
  <div>
    <div class="header-org">
      ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH<br>
      TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH<br>
      KHOA CÔNG NGHỆ THÔNG TIN
    </div>
    <div class="header-line"></div>
  </div>

  <div style="margin: 35px 0;">
    <h1 class="guide-title">
      TÀI LIỆU HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG HỆ THỐNG
    </h1>
    <h2 class="guide-subtitle">
      HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ<br>
      DÀNH CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ (FRESHMART & FRESHMART PAY)
    </h2>
    <div style="width: 120px; height: 3px; background: #10b981; margin: 20px auto;"></div>
  </div>

  <div class="info-block" style="text-align: left; width: 85%; margin: 0 auto;">
    <p><b>Sinh viên thực hiện:</b></p>
    <p style="margin-left: 20px;">1. <b>Đinh Thế Chí Dũng</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002710</b> (Trưởng nhóm / Backend)</p>
    <p style="margin-left: 20px;">2. <b>Nguyễn Tuấn Hưng</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002711</b> (Frontend / Testing)</p>
    <p><b>Chuyên ngành:</b> Lập trình máy tính &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Khóa học:</b> 2025</p>
    <p><b>Lớp:</b> 25C2-LMT1</p>
    <p style="margin-top: 15px;"><b>Giảng viên hướng dẫn:</b> <b>Th.S. Mai Chiếm Tuấn</b></p>
  </div>

  <div class="footer-date">
    Thành phố Hồ Chí Minh, tháng 11 năm 2026
  </div>
</div>

<div class="page-break"></div>

<!-- PHẦN 1 -->
<h1 class="part-title">PHẦN 1: HƯỚNG DẪN CÀI ĐẶT VÀ TRIỂN KHAI</h1>

<h2 class="sec-title">1.1. Phương pháp 1: Cài đặt trên máy chủ cục bộ (XAMPP)</h2>

<h3 class="subsec-title">Bước 1: Tải và sao chép mã nguồn</h3>
<p class="text-indent">
  1. Tải toàn bộ mã nguồn đồ án từ GitHub hoặc giải nén thư mục <b>DoAn</b>.
</p>
<p class="text-indent">
  2. Sao chép thư mục dự án vào thư mục gốc của web server:
</p>
<ul>
  <li>Đối với <b>XAMPP</b>: <b>C:\xampp\htdocs\DoAn</b></li>
</ul>

<h3 class="subsec-title">Bước 2: Nạp cơ sở dữ liệu mẫu (Database Import)</h3>
<p class="text-indent">
  1. Khởi động dịch vụ <b>Apache</b> và <b>MySQL</b> trong bảng điều khiển XAMPP Control Panel.
</p>
<p class="text-indent">
  2. Mở trình duyệt web và truy cập công cụ <b>phpMyAdmin</b> tại địa chỉ: <b>http://localhost/phpmyadmin</b> (nếu Apache đã đổi cổng sang 8080 thì nhập: <b>http://localhost:8080/phpmyadmin</b>).
</p>
<p class="text-indent">
  3. Tạo mới một cơ sở dữ liệu với tên: <b>freshmart_db</b>, chọn bảng mã (Collation) là: <b>utf8mb4_unicode_ci</b>.
</p>
<p class="text-indent">
  4. Chọn tab <b>Import (Nhập)</b> -> Nhấn <b>Choose File (Chọn tệp)</b> -> Chọn tệp: <b>csdl/database.sql</b> nằm trong thư mục đồ án -> Nhấn nút <b>Import (Thực hiện)</b> ở cuối trang.
</p>
<div class="callout-box">
  <b>Kết quả:</b> Hệ thống sẽ tự động tạo đủ 5 bảng: <b>NguoiDung</b>, <b>CuaHang</b>, <b>CongThanhToan</b>, <b>GiaoDich</b>, <b>NhatKyWebhook</b> và chèn sẵn các tài khoản demo (Admin, Staff, User) cùng tài khoản ngân hàng MB Bank thụ hưởng.
</div>

<h3 class="subsec-title">Bước 3: Cấu hình kết nối Cơ sở dữ liệu (csdl/db.php)</h3>
<p class="text-indent">
  Mở tệp tin <b>csdl/db.php</b> bằng phần mềm soạn thảo (VS Code, Notepad++) và kiểm tra cấu hình kết nối local:
</p>
<div class="code-block">
&lt;?php
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
</div>

<h3 class="subsec-title">Bước 4: Kiểm tra và chạy kiểm thử tự động (Smoke Test)</h3>
<p class="text-indent">
  Để đảm bảo toàn bộ các hàm mã hóa bảo mật, phân quyền và kết nối CSDL hoạt động chính xác trước khi mở trình duyệt, mở Terminal (PowerShell hoặc Command Prompt) tại thư mục dự án và chạy lệnh:
</p>
<div class="code-block">
php php/tests/run_smoke_tests.php
</div>
<div class="callout-box">
  Màn hình sẽ hiển thị thông báo: <b>100% CÁC BÀI TEST CHỨC NĂNG VÀ BẢO MẬT ĐÃ ĐẠT!</b> (Bao gồm UUID RFC 4122, mã hóa AES-256, mật khẩu Bcrypt, phân quyền isPrivileged và chữ ký HMAC-SHA256).
</div>

<h3 class="subsec-title">Bước 5: Khởi chạy và trải nghiệm trên trình duyệt</h3>
<p class="text-indent">
  Mở trình duyệt web (Google Chrome, Cốc Cốc hoặc Edge) và truy cập đường dẫn:
</p>
<div class="code-block">
http://localhost/DoAn/
hoặc: http://localhost/DoAn/html/index.html
</div>

<h2 class="sec-title">1.2. Phương pháp 2: Triển khai nhanh bằng Docker Container</h2>
<p class="text-indent">
  Đồ án đã được đóng gói sẵn file cấu hình <b>Dockerfile</b> dựa trên nền tảng PHP 8.2 Apache chính thống.
</p>
<p class="text-indent">
  1. Mở cửa sổ dòng lệnh tại thư mục gốc của dự án.
</p>
<p class="text-indent">
  2. Thực hiện lệnh build Docker image:
</p>
<div class="code-block">
docker build -t freshmart-pay .
</div>
<p class="text-indent">
  3. Khởi chạy Docker container với cổng chuyển tiếp:
</p>
<div class="code-block">
docker run -d -p 8080:10000 --name freshmart-container freshmart-pay
</div>
<p class="text-indent">
  4. Mở trình duyệt và truy cập: <b>http://localhost:8080/</b>. Container sẽ tự động thiết lập quyền hạn thư mục, cấu hình Apache Rewrite và kích hoạt toàn bộ hệ thống.
</p>

<h2 class="sec-title">1.3. Phương pháp 3: Triển khai tự động trên Render Cloud + TiDB Cloud</h2>
<p class="text-indent">
  Hệ thống được thiết kế theo tiêu chuẩn Cloud-Native, sẵn sàng cho việc triển khai trên hạ tầng đám mây không máy chủ (Serverless):
</p>
<ul>
  <li><b>Tạo cơ sở dữ liệu trên TiDB Cloud:</b>
    <ul>
      <li>Đăng ký tài khoản miễn phí tại <b>tidbcloud.com</b> và khởi tạo cụm Serverless Cluster.</li>
      <li>Tải chứng chỉ <b>ca-certificates.crt</b> và lấy thông tin Host, Port (4000), Username, Password.</li>
      <li>Mở SQL Editor trên TiDB Cloud và thực thi toàn bộ nội dung tệp <b>csdl/database.sql</b>.</li>
    </ul>
  </li>
  <li><b>Triển khai Web Service trên Render:</b>
    <ul>
      <li>Đăng nhập <b>render.com</b>, chọn <b>New +</b> -> <b>Web Service</b> -> Kết nối với kho GitHub <b>Lokki238/doan</b>.</li>
      <li>Runtime: Chọn <b>Docker</b>.</li>
      <li>Instance Type: Chọn <b>Free</b>.</li>
      <li>Cấu hình Environment Variables (Biến môi trường) kết nối CSDL:
        <ul>
          <li><b>TIDB_HOST</b>: Hostname của cụm TiDB Cloud.</li>
          <li><b>TIDB_PORT</b>: <b>4000</b></li>
          <li><b>TIDB_USER</b>: Tên tài khoản TiDB.</li>
          <li><b>TIDB_PASSWORD</b>: Mật khẩu kết nối.</li>
          <li><b>TIDB_DB_NAME</b>: <b>freshmart_db</b></li>
        </ul>
      </li>
      <li>Nhấn <b>Create Web Service</b>. Render sẽ tự động kéo code, build Docker container và cấp phát tên miền SSL Let's Encrypt công khai: <b>https://doan-yteb.onrender.com</b>.</li>
    </ul>
  </li>
</ul>

<div class="page-break"></div>

<!-- PHẦN 2 -->
<h1 class="part-title">PHẦN 2: HƯỚNG DẪN SỬ DỤNG DÀNH CHO KHÁCH HÀNG (CUSTOMER GUIDE)</h1>

<h2 class="sec-title">2.1. Đăng ký tài khoản và Đăng nhập hệ thống</h2>
<ul>
  <li><b>Đăng ký tài khoản mới:</b>
    <ul>
      <li>Truy cập menu đầu trang (Header) -> Nhấp vào nút <b>Đăng ký</b> hoặc truy cập trực tiếp <b>html/register.html</b>.</li>
      <li>Điền đầy đủ các thông tin: Họ và tên, Email, Số điện thoại và Mật khẩu (tối thiểu 6 ký tự).</li>
      <li>Nhấn <b>Đăng ký ngay</b>. Hệ thống sẽ mã hóa mật khẩu an toàn bằng thuật toán Bcrypt và tạo mới người dùng với vai trò mặc định là <b>Khách hàng (USER)</b>.</li>
    </ul>
  </li>
  <li><b>Đăng nhập hệ thống:</b>
    <ul>
      <li>Truy cập <b>html/login.html</b>, nhập Email và Mật khẩu đã đăng ký.</li>
      <li>Sau khi xác thực thành công, thanh điều hướng Header sẽ tự động hiển thị tên người dùng và vai trò tài khoản (Khách hàng hoặc Quản trị viên).</li>
    </ul>
  </li>
</ul>

<h2 class="sec-title">2.2. Tìm kiếm, lọc sản phẩm và thêm vào giỏ hàng</h2>
<p class="text-indent">
  1. <b>Duyệt xem sản phẩm:</b> Nhấp vào menu <b>Sản phẩm</b> (<b>html/products.html</b>).
</p>
<p class="text-indent">
  2. <b>Bộ lọc thông minh:</b> Ở thanh bên trái, khách hàng có thể chọn lọc theo danh mục (Rau củ quả, Thịt & Hải sản, Sữa & Trứng, Gạo & Ngũ cốc, Đồ uống) hoặc lọc theo khoảng giá mong muốn.
</p>
<p class="text-indent">
  3. <b>Thêm sản phẩm vào giỏ:</b> Trên từng thẻ sản phẩm, nhấp nút <b>Thêm vào giỏ</b>. Ngay lập tức:
</p>
<ul>
  <li>Nút bấm sẽ chuyển sang trạng thái xanh lá: <b>Đã thêm</b>.</li>
  <li>Hệ thống lưu trữ thông tin sản phẩm (ID, tên, đơn giá, ảnh, đơn vị) vào <b>localStorage</b> của trình duyệt.</li>
  <li>Huy hiệu số lượng giỏ hàng trên Header lập tức tự động tăng thêm 1 sản phẩm (+1).</li>
</ul>

<h2 class="sec-title">2.3. Quản lý giỏ hàng và áp dụng mã voucher giảm giá</h2>
<p class="text-indent">
  Nhấp vào biểu tượng Giỏ hàng trên thanh điều hướng để mở trang <b>html/cart.html</b>:
</p>
<ul>
  <li><b>Điều chỉnh số lượng:</b> Nhấp nút <b>[+]</b> hoặc <b>[-]</b> để tăng/giảm số lượng sản phẩm. Tổng tiền của từng dòng và tổng đơn hàng sẽ tự động tính lại ngay lập tức mà không cần tải lại trang.</li>
  <li><b>Xóa sản phẩm:</b> Nhấp vào biểu tượng thùng rác màu đỏ để loại bỏ sản phẩm khỏi giỏ hàng.</li>
  <li><b>Áp dụng mã giảm giá (Voucher):</b> Nhập mã voucher ưu đãi vào ô khuyến mãi:
    <div class="callout-box">
      Nhập mã: <b>FRESHMART20</b> -> Nhấn nút <b>Áp dụng</b> -> Hệ thống tự động giảm ngay <b>20.000đ</b> vào tổng thanh toán của đơn hàng!
    </div>
  </li>
</ul>

<h2 class="sec-title">2.4. Đặt hàng và quy trình thanh toán quét mã VietQR tự động</h2>
<p class="text-indent">
  1. Tại trang giỏ hàng, nhấp nút <b>Tiến hành thanh toán</b>. Hệ thống tự động kiểm tra phiên:
</p>
<ul>
  <li>Nếu khách hàng chưa đăng nhập: Hệ thống tự động chuyển hướng sang trang <b>html/login.html</b> để đăng nhập.</li>
  <li>Nếu đã đăng nhập: Chuyển thẳng đến trang xác nhận thanh toán <b>html/checkout.html</b>.</li>
</ul>
<p class="text-indent">
  2. Điền địa chỉ nhận hàng, chọn phương thức vận chuyển (Giao hàng tiêu chuẩn 30.000đ hoặc Giao hàng nhanh 50.000đ).
</p>
<p class="text-indent">
  3. Tại mục <b>Phương thức thanh toán</b>, chọn <b>Chuyển khoản qua mã VietQR (Tự động kích hoạt)</b> -> Nhấn nút <b>Đặt hàng ngay</b>.
</p>
<p class="text-indent">
  4. Hệ thống tự động tạo mã hóa đơn duy nhất (ví dụ: <b>FM903964</b>) và chuyển khách hàng sang trang hiển thị mã QR: <b>html/payment_qr.html</b>:
</p>
<ul>
  <li><b>Mã QR động NAPAS 247:</b> Hiển thị sắc nét, chứa sẵn thông tin Ngân hàng thụ hưởng, Số tài khoản, Tên chủ shop và <b>Số tiền chính xác đến từng đồng</b>.</li>
  <li><b>Nội dung chuyển khoản:</b> Hiển thị mã đơn hàng duy nhất kèm nút <b>Sao chép (Copy)</b> 1-click để khách sao chép nhanh nếu chuyển khoản thủ công.</li>
  <li><b>Đồng hồ đếm ngược 15 phút:</b> Nhắc nhở thời gian giữ đơn hàng.</li>
  <li><b>Cơ chế tự động nhảy trạng thái (Webhook & Polling 2 giây):</b> Khách hàng mở app ngân hàng bất kỳ (Vietcombank, MB Bank, Techcombank, TPBank, MoMo...) quét mã QR và xác nhận chuyển tiền. Ngay khi tiền về tài khoản shop (chỉ mất 2-3 giây):
    <div class="callout-box">
      <b>Webhook kích hoạt:</b> Màn hình thanh toán tự động chuyển sang thông báo <b>"Thanh toán thành công!"</b> kèm hiệu ứng pháo hoa chúc mừng, giỏ hàng tự động được làm sạch và chuyển về trang quản lý đơn hàng mà khách hàng <b>không cần nhấn bất kỳ phím F5 hay tải lại trang nào</b>!
    </div>
  </li>
</ul>

<div class="page-break"></div>

<!-- PHẦN 3 -->
<h1 class="part-title">PHẦN 3: HƯỚNG DẪN DÀNH CHO QUẢN TRỊ VIÊN VÀ NHÂN VIÊN (ADMIN & STAFF GUIDE)</h1>

<h2 class="sec-title">3.1. Truy cập Bảng điều khiển quản trị (Dashboard)</h2>
<p class="text-indent">
  1. Đăng nhập với tài khoản Chủ cửa hàng (<b>admin@freshmart.vn</b>) hoặc Nhân viên (<b>staff@freshmart.vn</b>).
</p>
<p class="text-indent">
  2. Trên thanh menu Header, nhấp vào nút <b>Admin</b> hoặc truy cập đường dẫn: <b>/php/dashboard.php</b>.
</p>
<p class="text-indent">
  3. Bảng điều khiển tổng quan cung cấp các số liệu kinh doanh thời gian thực:
</p>
<ul>
  <li><b>Tổng doanh thu:</b> Tổng số tiền thực thu từ các đơn hàng có trạng thái <b>PAID (Đã thanh toán)</b>.</li>
  <li><b>Số đơn hàng thành công:</b> Tổng số giao dịch đã thanh toán qua VietQR.</li>
  <li><b>Tỉ lệ thanh toán thành công:</b> Phần trăm đơn thanh toán tự động so với tổng đơn phát sinh.</li>
  <li><b>Biểu đồ doanh thu 7 ngày gần nhất:</b> Được trực quan hóa bằng biểu đồ cột sinh động thông qua thư viện Chart.js.</li>
  <li><b>Hệ thống chuông báo Realtime:</b> Khi khách tạo mã đơn, chuông sẽ rung lắc, phát âm thanh "Ting 🔔" và hiển thị pop-up thông báo đơn hàng đang chờ thanh toán.</li>
</ul>

<h2 class="sec-title">3.2. Quản lý danh sách giao dịch & đối soát hóa đơn</h2>
<p class="text-indent">
  Truy cập menu <b>Giao dịch</b> (<b>/php/transactions.php</b>):
</p>
<ul>
  <li>Xem danh sách toàn bộ các hóa đơn trong hệ thống: Mã giao dịch, Tên khách hàng, Số tiền, Phương thức thanh toán, Thời gian tạo.</li>
  <li><b>Bộ lọc trạng thái:</b> Lọc nhanh các đơn hàng theo: <b>Tất cả</b>, <b>Đã thanh toán (PAID)</b>, <b>Chờ thanh toán (PENDING)</b>, <b>Đã hủy / Hết hạn (EXPIRED)</b>.</li>
  <li><b>Tra cứu đối soát:</b> Nhập mã hóa đơn hoặc số tài khoản khách hàng vào ô tìm kiếm để tra cứu thông tin mã tham chiếu ngân hàng (Bank Transaction ID).</li>
</ul>

<h2 class="sec-title">3.3. Báo cáo & phân tích doanh thu qua biểu đồ trực quan</h2>
<p class="text-indent">
  Truy cập menu <b>Báo cáo</b> (<b>/php/reports.php</b>):
</p>
<ul>
  <li>Theo dõi cơ cấu doanh thu theo từng kênh thanh toán (VietQR, Tiền mặt COD, Ví điện tử).</li>
  <li>Thống kê top sản phẩm tươi sạch bán chạy nhất trong tuần và trong tháng.</li>
  <li>Xuất dữ liệu báo cáo đối soát phục vụ quyết toán thuế và kế toán cửa hàng.</li>
</ul>

<h2 class="sec-title">3.4. Cấu hình cổng thanh toán ngân hàng thụ hưởng (gateways.php)</h2>
<div class="callout-warn">
  <b>Lưu ý quan trọng:</b> Trang cấu hình cổng thanh toán (<b>/php/admin/gateways.php</b>) được bảo vệ nghiêm ngặt bằng hàm phân quyền <b>requireOwner()</b>. Chỉ tài khoản có vai trò <b>OWNER (Chủ cửa hàng)</b> mới có quyền truy cập và thay đổi.
</div>
<p class="text-indent">
  Khi cửa hàng muốn đổi sang tài khoản ngân hàng khác để nhận tiền, thực hiện các bước sau:
</p>
<ul>
  <li>Truy cập menu <b>Cổng thanh toán</b> trên thanh Sidebar.</li>
  <li>Tại phần <b>Cấu hình VietQR (Ưu tiên)</b>:
    <ul>
      <li><b>Số tài khoản nhận tiền:</b> Nhập số tài khoản ngân hàng mới (Ví dụ: <b>0389336829</b>).</li>
      <li><b>Mã ngân hàng (BIN / Code):</b> Chọn ngân hàng thụ hưởng từ danh sách chọn (Ví dụ: <b>MB Bank - Mã 970422</b>, <b>Vietcombank - Mã 970436</b>, <b>Techcombank - Mã 970407</b>, <b>ACB - Mã 970416</b>...).</li>
      <li><b>Tên chủ tài khoản:</b> Nhập họ tên in hoa không dấu của chủ tài khoản (Ví dụ: <b>FRESHMART STORE</b> hoặc <b>DINH THE CHI DUNG</b>).</li>
      <li><b>Trạng thái:</b> Chọn <b>Đang hoạt động</b>.</li>
    </ul>
  </li>
  <li>Nhấn nút <b>Lưu cấu hình VietQR</b>.</li>
</ul>
<div class="callout-box">
  <b>Cơ chế cập nhật thời gian thực:</b> Ngay khi bấm Lưu, thông tin mới được ghi đè trực tiếp vào bảng <b>CongThanhToan</b> trong cơ sở dữ liệu TiDB Cloud. Mọi khách hàng đặt đơn ngay sau thời điểm đó sẽ quét mã VietQR trỏ thẳng về số tài khoản mới này mà <b>không cần phải sửa đổi bất kỳ dòng mã nguồn PHP hay khởi động lại máy chủ</b>!
</div>

<h2 class="sec-title">3.5. Quản lý & Phân cấp quyền người dùng trực tiếp (permissions.php)</h2>
<p class="text-indent">
  Truy cập menu <b>Cấp quyền</b> (<b>/php/permissions.php</b>):
</p>
<ul>
  <li>Giao diện hiển thị danh sách toàn bộ người dùng đã đăng ký trong hệ thống kèm thông tin vai trò hiện tại và banner thẩm quyền của tài khoản đang đăng nhập.</li>
  <li><b>Tìm kiếm:</b> Nhập tên hoặc email người dùng vào ô tìm kiếm nhanh (hỗ trợ phím Enter).</li>
  <li><b>Ma trận phân cấp thẩm quyền điều chỉnh tài khoản:</b>
    <ul>
      <li><b>Quản trị viên (OWNER):</b> Điều chỉnh tất cả mọi tài khoản trong hệ thống. Có thể phân quyền cho bất kỳ ai thành OWNER, MANAGER, STAFF hoặc USER.</li>
      <li><b>Quản lý Cửa hàng (MANAGER):</b> Điều chỉnh tài khoản Nhân viên (STAFF) và Khách hàng (USER). Không thể can thiệp vào tài khoản Quản lý hoặc Quản trị viên, và không thể tự nâng cấp lên vai trò cao hơn.</li>
      <li><b>Nhân viên (STAFF):</b> Chỉ điều chỉnh được tài khoản Khách hàng (USER). Các tài khoản Nhân viên, Quản lý và Quản trị viên sẽ tự động bị khóa ô chọn và hiển thị trạng thái "Không có quyền".</li>
      <li><b>Khách hàng (USER):</b> Tài khoản mua sắm cá nhân, không có quyền truy cập trang quản trị hay phân quyền (bị chuyển hướng về trang tài khoản cá nhân).</li>
    </ul>
  </li>
  <li>Nhấn nút <b>Lưu quyền</b> tương ứng với người dùng được phép. Hệ thống kiểm tra bảo mật 2 lớp (Frontend và Backend API) trước khi phát lệnh SQL cập nhật ngay lập tức vào CSDL.</li>
</ul>

<h2 class="sec-title">3.6. Công cụ mô phỏng Webhook kiểm thử (simulate_webhook.php)</h2>
<p class="text-indent">
  Nhằm phục vụ công tác chấm thi, nghiệm thu đồ án và kiểm thử chức năng thanh toán tự động mà <b>không cần phải chuyển tiền thật từ app ngân hàng</b>, nhóm tác giả đã xây dựng sẵn công cụ mô phỏng Webhook tại địa chỉ:
</p>
<div class="code-block">
https://doan-yteb.onrender.com/php/api/simulate_webhook.php
(Hoặc trên máy local: http://localhost/DoAn/php/api/simulate_webhook.php)
</div>
<p class="text-indent">
  <b>Quy trình thực hiện mô phỏng chuyển tiền:</b>
</p>
<ul>
  <li>Khách hàng đặt một đơn hàng mới và đang đứng tại màn hình quét mã QR có mã đơn (Ví dụ: <b>FM903964</b>, số tiền <b>297000</b>).</li>
  <li>Người kiểm thử mở tab mới truy cập công cụ <b>simulate_webhook.php</b>.</li>
  <li>Nhập mã đơn hàng <b>FM903964</b> và số tiền <b>297000</b> -> Nhấn nút <b>Bắn tín hiệu Webhook giả lập</b>.</li>
  <li>Hệ thống gửi gói tin JSON chuẩn xác đến <b>php/api/webhook_vietqr.php</b>. Ngay lập tức, quay lại tab màn hình quét mã QR của khách hàng, màn hình sẽ tự động nhảy sang <b>Thanh toán thành công</b> chỉ sau 1 giây!</li>
</ul>

<div class="page-break"></div>

<!-- PHẦN 4 -->
<h1 class="part-title">PHẦN 4: XỬ LÝ SỰ CỐ VÀ CÂU HỎI THƯỜNG GẶP (TROUBLESHOOTING & FAQ)</h1>

<table class="data-table">
  <thead>
    <tr>
      <th style="width: 25%;">Hiện tượng sự cố</th>
      <th style="width: 35%;">Nguyên nhân chính</th>
      <th style="width: 40%;">Cách khắc phục triệt để</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="bold">1. Lỗi "Database connection failed"</td>
      <td>Sai thông tin kết nối máy chủ MySQL/TiDB hoặc cổng 3306 bị chiếm dụng.</td>
      <td>Mở tệp <b>csdl/db.php</b>, kiểm tra lại host, port, username, password. Đảm bảo dịch vụ MySQL trong XAMPP đã chuyển sang màu xanh (Running). Nếu dùng TiDB Cloud, kiểm tra kết nối mạng Internet.</td>
    </tr>
    <tr>
      <td class="bold">2. Lỗi 404 Not Found khi chuyển trang</td>
      <td>Web server Apache chưa bật module <b>mod_rewrite</b> hoặc tệp <b>.htaccess</b> bị thiếu.</td>
      <td>Mở file <b>httpd.conf</b> của Apache, tìm dòng <b>#LoadModule rewrite_module modules/mod_rewrite.so</b> và bỏ dấu <b>#</b> ở đầu dòng. Đảm bảo file <b>.htaccess</b> nằm ở thư mục gốc của dự án.</td>
    </tr>
    <tr>
      <td class="bold">3. Lỗi "Headers already sent"</td>
      <td>Tệp tin PHP bị dính ký tự UTF-8 BOM hoặc khoảng trắng trước thẻ <b>&lt;?php</b>.</td>
      <td>Hệ thống đã được xử lý triệt để: toàn bộ file đã xóa sạch BOM và có sẵn hàm <b>ob_start()</b> trong <b>php/includes/auth.php</b>. Nếu tự tạo file mới, hãy lưu tệp ở định dạng <b>UTF-8 without BOM</b>.</td>
    </tr>
    <tr>
      <td class="bold">4. Quét mã QR rồi nhưng web không tự nhảy trạng thái</td>
      <td>Khách hàng nhập sai nội dung chuyển khoản hoặc chuyển sai số tiền lẻ.</td>
      <td>Yêu cầu khách hàng giữ nguyên nội dung chuyển khoản là mã đơn hàng (Ví dụ: <b>FM903964</b>) do mã VietQR tự động sinh ra. Có thể vào công cụ <b>simulate_webhook.php</b> để kích hoạt lại đơn nếu cần đối soát gấp.</td>
    </tr>
    <tr>
      <td class="bold">5. Bị chuyển hướng về trang login khi vào Dashboard</td>
      <td>Người dùng chưa đăng nhập hoặc tài khoản chỉ có quyền <b>USER</b> thông thường.</td>
      <td>Đăng nhập bằng tài khoản Quản trị viên (<b>admin@freshmart.vn</b> / <b>Admin@123456</b>). Nếu dùng tài khoản khác, nhờ tài khoản Admin vào trang <b>permissions.php</b> để cấp quyền <b>STAFF</b> hoặc <b>OWNER</b>.</td>
    </tr>
    <tr>
      <td class="bold">6. Hình ảnh sản phẩm bị vỡ hoặc không hiển thị</td>
      <td>Link ảnh mạng bên ngoài bị chặn hoặc chưa lưu ảnh cục bộ.</td>
      <td>Hệ thống đã lưu trữ toàn bộ ảnh sản phẩm cốt lõi (Cà chua bi, Táo nhập khẩu...) trực tiếp trong thư mục <b>html/img/</b>. Hệ thống có sẵn fallback tự động nạp ảnh cục bộ giúp trang luôn hiển thị sắc nét 100%.</td>
    </tr>
  </tbody>
</table>

<div style="margin-top: 40px; text-align: center; font-weight: bold; color: #047857;">
  --- HẾT TÀI LIỆU HƯỚNG DẪN CÀI ĐẶT VÀ SỬ DỤNG HỆ THỐNG ---
</div>

</body>
</html>
HTML;

// 1. Lưu file Word HTML (.doc)
$docPath = $outDir . '/TaiLieuHuongDan.doc';
file_put_contents($docPath, "\xEF\xBB\xBF" . $guideHtml);
echo "-> Đã tạo tệp Hướng dẫn Word (.doc): $docPath\n";

// 2. Tạo file Markdown (.md) sạch hoàn toàn không dấu ` và không dấu * đơn lẻ
$mdContent = <<<'MD'
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

## 3.5. Quản lý & Phân cấp quyền người dùng trực tiếp (permissions.php)
Truy cập menu **Cấp quyền** (**/php/permissions.php**):
1. Giao diện hiển thị danh sách toàn bộ người dùng đã đăng ký trong hệ thống kèm thông tin vai trò hiện tại và banner thẩm quyền của tài khoản đang đăng nhập.
2. **Tìm kiếm:** Nhập tên hoặc email người dùng vào ô tìm kiếm nhanh (hỗ trợ phím Enter).
3. **Ma trận phân cấp thẩm quyền điều chỉnh tài khoản:**
   - **Quản trị viên (OWNER):** Điều chỉnh tất cả mọi tài khoản trong hệ thống (OWNER, MANAGER, STAFF, USER).
   - **Quản lý Cửa hàng (MANAGER):** Điều chỉnh tài khoản Nhân viên (STAFF) và Khách hàng (USER). Không được phép chỉnh Quản lý hoặc Quản trị viên.
   - **Nhân viên (STAFF):** Chỉ điều chỉnh được tài khoản Khách hàng (USER). Không được phép chỉnh Nhân viên, Quản lý hoặc Quản trị viên.
   - **Khách hàng (USER):** Tài khoản mua sắm cá nhân, không có quyền truy cập trang quản trị hay phân quyền.
4. Nhấn nút **Lưu quyền** tương ứng với người dùng được phép. Hệ thống kiểm tra bảo mật 2 lớp (Frontend và Backend API) trước khi phát lệnh SQL cập nhật ngay lập tức vào CSDL.

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
MD;

$mdPath = $outDir . '/TaiLieuHuongDan.md';
file_put_contents($mdPath, $mdContent);
echo "-> Đã tạo tệp Hướng dẫn Markdown (.md): $mdPath\n";

// 3. Tạo file Word DOCX chuẩn OpenXML (.docx)
if (class_exists('ZipArchive')) {
    $docxPath = $outDir . '/TaiLieuHuongDan.docx';
    $zip = new ZipArchive();
    if ($zip->open($docxPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>');

        $xmlBody = "";
        $lines = explode("\n", $mdContent);
        $inTable = false;
        $tableRows = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === "") {
                if ($inTable && count($tableRows) > 0) {
                    // Xử lý vẽ bảng
                    $xmlBody .= "<w:tbl><w:tblPr><w:tblW w:w='5000' w:type='pct'/><w:tblBorders><w:top w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:left w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:bottom w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:right w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:insideH w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:insideV w:val='single' w:sz='4' w:space='0' w:color='auto'/></w:tblBorders></w:tblPr>";
                    foreach ($tableRows as $rowIndex => $rowCells) {
                        $isHeader = ($rowIndex === 0);
                        $xmlBody .= "<w:tr>";
                        foreach ($rowCells as $cellText) {
                            $cText = trim($cellText);
                            $cleanCell = str_replace(['**', '*'], '', $cText);
                            $escapedCell = htmlspecialchars($cleanCell, ENT_XML1, 'UTF-8');
                            $bgColor = $isHeader ? "<w:shd w:val='clear' w:color='auto' w:fill='E2E8F0'/>" : "";
                            $boldTag = ($isHeader || str_starts_with($cText, '**')) ? "<w:b/>" : "";
                            $xmlBody .= "<w:tc><w:tcPr>{$bgColor}<w:tcMar><w:top w:w='120' w:type='dxa'/><w:bottom w:w='120' w:type='dxa'/><w:left w:w='160' w:type='dxa'/><w:right w:w='160' w:type='dxa'/></w:tcMar></w:tcPr><w:p><w:pPr><w:jc w:val='" . ($isHeader ? "center" : "left") . "'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/>{$boldTag}<w:sz w:val='24'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/>{$boldTag}<w:sz w:val='24'/></w:rPr><w:t>{$escapedCell}</w:t></w:r></w:p></w:tc>";
                        }
                        $xmlBody .= "</w:tr>";
                    }
                    $xmlBody .= "</w:tbl>";
                    $inTable = false;
                    $tableRows = [];
                }
                continue;
            }

            // Kiểm tra dòng bảng
            if (str_starts_with($trimmed, '|')) {
                if (preg_match('/^\|[\s\-:]+\|/', $trimmed)) {
                    continue; // Dòng phân cách |---|
                }
                $cells = array_values(array_filter(array_map('trim', explode('|', $trimmed)), fn($c) => $c !== ''));
                if (count($cells) > 0) {
                    $inTable = true;
                    $tableRows[] = $cells;
                    continue;
                }
            }

            if ($inTable && count($tableRows) > 0) {
                // Đóng bảng trước khi xử lý đoạn tiếp theo
                $xmlBody .= "<w:tbl><w:tblPr><w:tblW w:w='5000' w:type='pct'/><w:tblBorders><w:top w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:left w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:bottom w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:right w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:insideH w:val='single' w:sz='4' w:space='0' w:color='auto'/><w:insideV w:val='single' w:sz='4' w:space='0' w:color='auto'/></w:tblBorders></w:tblPr>";
                foreach ($tableRows as $rowIndex => $rowCells) {
                    $isHeader = ($rowIndex === 0);
                    $xmlBody .= "<w:tr>";
                    foreach ($rowCells as $cellText) {
                        $cText = trim($cellText);
                        $cleanCell = str_replace(['**', '*'], '', $cText);
                        $escapedCell = htmlspecialchars($cleanCell, ENT_XML1, 'UTF-8');
                        $bgColor = $isHeader ? "<w:shd w:val='clear' w:color='auto' w:fill='E2E8F0'/>" : "";
                        $boldTag = ($isHeader || str_starts_with($cText, '**')) ? "<w:b/>" : "";
                        $xmlBody .= "<w:tc><w:tcPr>{$bgColor}<w:tcMar><w:top w:w='120' w:type='dxa'/><w:bottom w:w='120' w:type='dxa'/><w:left w:w='160' w:type='dxa'/><w:right w:w='160' w:type='dxa'/></w:tcMar></w:tcPr><w:p><w:pPr><w:jc w:val='" . ($isHeader ? "center" : "left") . "'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/>{$boldTag}<w:sz w:val='24'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/>{$boldTag}<w:sz w:val='24'/></w:rPr><w:t>{$escapedCell}</w:t></w:r></w:p></w:tc>";
                    }
                    $xmlBody .= "</w:tr>";
                }
                $xmlBody .= "</w:tbl>";
                $inTable = false;
                $tableRows = [];
            }

            if ($trimmed === '---') {
                $xmlBody .= "<w:p><w:pPr><w:pBdr><w:bottom w:val='single' w:sz='6' w:space='1' w:color='CBD5E1'/></w:pBdr></w:pPr></w:p>";
                continue;
            }

            $isH1 = str_starts_with($trimmed, '# ');
            $isH2 = str_starts_with($trimmed, '## ');
            $isH3 = str_starts_with($trimmed, '### ');

            if ($isH1) {
                $clean = trim(substr($trimmed, 2));
                $escaped = htmlspecialchars(str_replace(['**', '*'], '', $clean), ENT_XML1, 'UTF-8');
                $xmlBody .= "<w:p><w:pPr><w:jc w:val='center'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='30'/><w:color w:val='047857'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='30'/><w:color w:val='047857'/></w:rPr><w:t>{$escaped}</w:t></w:r></w:p>";
            } elseif ($isH2) {
                $clean = trim(substr($trimmed, 3));
                $escaped = htmlspecialchars(str_replace(['**', '*'], '', $clean), ENT_XML1, 'UTF-8');
                $xmlBody .= "<w:p><w:pPr><w:jc w:val='both'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='28'/><w:color w:val='065F46'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='28'/><w:color w:val='065F46'/></w:rPr><w:t>{$escaped}</w:t></w:r></w:p>";
            } elseif ($isH3) {
                $clean = trim(substr($trimmed, 4));
                $escaped = htmlspecialchars(str_replace(['**', '*'], '', $clean), ENT_XML1, 'UTF-8');
                $xmlBody .= "<w:p><w:pPr><w:jc w:val='both'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='26'/><w:color w:val='0F172A'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='26'/><w:color w:val='0F172A'/></w:rPr><w:t>{$escaped}</w:t></w:r></w:p>";
            } else {
                // Phân tích các đoạn in đậm **text**
                $xmlBody .= "<w:p><w:pPr><w:jc w:val='both'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:sz w:val='26'/></w:rPr></w:pPr>";
                $parts = preg_split('/(\*\*.*?\*\*)/', $trimmed, -1, PREG_SPLIT_DELIM_CAPTURE);
                foreach ($parts as $part) {
                    if ($part === "") continue;
                    $isBold = (str_starts_with($part, '**') && str_ends_with($part, '**'));
                    $rawText = $isBold ? substr($part, 2, -2) : $part;
                    $rawText = str_replace('*', '', $rawText);
                    $esc = htmlspecialchars($rawText, ENT_XML1, 'UTF-8');
                    $boldTag = $isBold ? "<w:b/>" : "";
                    $xmlBody .= "<w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/>{$boldTag}<w:sz w:val='26'/></w:rPr><w:t xml:space='preserve'>{$esc}</w:t></w:r>";
                }
                $xmlBody .= "</w:p>";
            }
        }

        $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>' . $xmlBody . '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1440" w:right="1134" w:bottom="1134" w:left="1701"/></w:sectPr></w:body></w:document>';
        $zip->addFromString('word/document.xml', $documentXml);
        $zip->close();
        echo "-> Đã tạo tệp Hướng dẫn Word chuẩn OpenXML (.docx): $docxPath\n";
    }
}

// 4. Sao chép trực tiếp sang thư mục Downloads
if (is_dir($downloadsDir)) {
    copy($outDir . '/TaiLieuHuongDan.docx', $downloadsDir . '/TaiLieuHuongDan.docx');
    copy($outDir . '/TaiLieuHuongDan.doc', $downloadsDir . '/TaiLieuHuongDan.doc');
    copy($outDir . '/TaiLieuHuongDan.md', $downloadsDir . '/TaiLieuHuongDan.md');
    echo "-> Đã sao chép toàn bộ 3 file sang $downloadsDir thành công!\n";
}

// 5. Kiểm tra kiểm toán sạch dấu ` và * đơn lẻ
$backticks = substr_count($mdContent, '`');
preg_match_all('/(?<!\*)\*(?!\*)/', $mdContent, $singleStars);
echo "\n--- KẾT QUẢ KIỂM TOÁN TÀI LIỆU ---\n";
echo "Số ký tự backtick (`): " . $backticks . "\n";
echo "Số ký tự asterisk đơn lẻ (*): " . count($singleStars[0]) . "\n";
if ($backticks === 0 && count($singleStars[0]) === 0) {
    echo "XÁC NHẬN: TÀI LIỆU SẠCH 100% KHÔNG CÒN KÝ TỰ ` VÀ * ĐƠN LẺ!\n";
} else {
    echo "CẢNH BÁO: Vẫn còn ký tự không mong muốn!\n";
}

echo "\n>>> HOÀN THÀNH 100% CẬP NHẬT TÀI LIỆU HƯỚNG DẪN SẠCH! <<<\n";
