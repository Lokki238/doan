<?php
/**
 * ====================================================================
 * SCRIPT TẠO SLIDE THUYẾT TRÌNH BẢO VỆ ĐỒ ÁN CHUYÊN NGÀNH (18 SLIDES)
 * Chuẩn bảo vệ 10-15 phút tại Trường Cao đẳng Lý Tự Trọng TP.HCM
 * ====================================================================
 */

$outDir = __DIR__ . '/..';

// 1. TẠO TẬP TIN SLIDE HTML TƯƠNG TÁC
$slideHtml = <<<'HTML'
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SLIDE BẢO VỆ ĐỒ ÁN - FRESHMART PAY</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../css/all.min.css">
<style>
  :root {
    --primary: #10b981;
    --primary-dark: #047857;
    --primary-light: #d1fae5;
    --dark: #0f172a;
    --dark-card: #1e293b;
    --text-light: #f8fafc;
    --text-muted: #94a3b8;
    --accent: #f59e0b;
    --danger: #ef4444;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--dark);
    color: var(--text-light);
    height: 100vh;
    overflow: hidden;
    user-select: none;
  }
  .slide-container {
    width: 100vw;
    height: 100vh;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .slide {
    display: none;
    width: 90vw;
    max-width: 1350px;
    height: 85vh;
    background: rgba(30, 41, 59, 0.7);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 48px 64px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    flex-direction: column;
    justify-content: space-between;
    animation: fadeIn 0.4s ease-out forwards;
    position: relative;
  }
  .slide.active { display: flex; }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* Slide Header & Titles */
  .slide-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(16, 185, 129, 0.15);
    color: var(--primary);
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
  }
  .slide-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 8px;
    line-height: 1.25;
  }
  .slide-title span { color: var(--primary); }
  .slide-subtitle {
    font-size: 1.05rem;
    color: var(--text-muted);
    margin-bottom: 24px;
  }

  /* Grid & Layouts */
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; align-items: stretch; }
  .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
  .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }

  .card {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
  }
  .card:hover {
    border-color: rgba(16, 185, 129, 0.4);
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -8px rgba(16, 185, 129, 0.2);
  }
  .card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(16, 185, 129, 0.15);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    margin-bottom: 16px;
  }
  .card-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 8px; color: #fff; }
  .card-text { font-size: 0.95rem; color: var(--text-muted); line-height: 1.5; }

  /* Feature items */
  .feature-list { list-style: none; display: flex; flex-direction: column; gap: 14px; }
  .feature-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 1.05rem;
    color: #e2e8f0;
    line-height: 1.5;
  }
  .feature-list li i {
    color: var(--primary);
    margin-top: 4px;
    font-size: 1.1rem;
  }

  /* Code & Diagram Previews */
  .code-box {
    background: #090d16;
    border: 1px solid #1e293b;
    border-radius: 12px;
    padding: 16px 20px;
    font-family: Consolas, monospace;
    font-size: 0.9rem;
    color: #38bdf8;
    line-height: 1.5;
    overflow-x: auto;
  }
  .highlight { color: var(--primary); font-weight: bold; }
  .accent { color: var(--accent); }

  /* Controls & Bottom Bar */
  .bottom-bar {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 20px;
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(12px);
    padding: 10px 24px;
    border-radius: 50px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
    z-index: 100;
  }
  .ctrl-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.1rem;
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
  }
  .ctrl-btn:hover { background: rgba(255, 255, 255, 0.1); color: var(--primary); }
  .slide-counter { font-size: 0.9rem; font-weight: 700; color: var(--text-muted); min-width: 60px; text-align: center; }
  .timer-pill {
    background: rgba(245, 158, 11, 0.15);
    color: var(--accent);
    padding: 4px 12px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .progress-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    z-index: 200;
  }
  .progress-bar {
    height: 100%;
    width: 5%;
    background: linear-gradient(90deg, #10b981, #38bdf8);
    transition: width 0.3s ease;
  }
</style>
</head>
<body>

<div class="progress-container">
  <div class="progress-bar" id="progressBar"></div>
</div>

<div class="slide-container">

  <!-- ==================== SLIDE 1 ==================== -->
  <div class="slide active center" style="text-align: center; justify-content: center; align-items: center;">
    <div class="slide-badge"><i class="fas fa-graduation-cap"></i> TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG TP.HCM • KHOA CNTT</div>
    <h1 style="font-size: 2.7rem; font-weight: 800; max-width: 1100px; line-height: 1.3; margin: 20px 0 15px;">
      THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP <span style="color: var(--primary);">VIETQR & VÍ ĐIỆN TỬ</span>
    </h1>
    <p style="font-size: 1.25rem; color: #94a3b8; margin-bottom: 40px;">ĐỒ ÁN CHUYÊN NGÀNH • KHÓA 2025 • THÁNG 11/2026</p>
    
    <div style="display: flex; gap: 60px; justify-content: center; background: rgba(15, 23, 42, 0.6); padding: 20px 50px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.08);">
      <div style="text-align: left;">
        <p style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; font-weight: bold;">Sinh viên thực hiện:</p>
        <p style="font-size: 1.1rem; font-weight: bold; color: #fff;">1. Đinh Thế Chí Dũng <span style="color: var(--primary); font-size: 0.9rem;">(25002710)</span></p>
        <p style="font-size: 1.1rem; font-weight: bold; color: #fff;">2. Nguyễn Tuấn Hưng <span style="color: var(--primary); font-size: 0.9rem;">(25002711)</span></p>
      </div>
      <div style="text-align: left; border-left: 1px solid rgba(255,255,255,0.1); padding-left: 60px;">
        <p style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; font-weight: bold;">Giảng viên hướng dẫn:</p>
        <p style="font-size: 1.2rem; font-weight: bold; color: #38bdf8;">ThS. Mai Chiếm Tuấn</p>
        <p style="font-size: 0.95rem; color: #94a3b8;">Lớp: 25C2-LMT1 • Ngành Lập trình máy tính</p>
      </div>
    </div>
  </div>

  <!-- ==================== SLIDE 2 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-exclamation-circle"></i> Đặt vấn đề & Thực trạng</div>
      <h2 class="slide-title">Nỗi đau của các <span>Cửa hàng trực tuyến nhỏ</span> tại Việt Nam</h2>
      <p class="slide-subtitle">Khảo sát trên 20 shop bán lẻ nhỏ: 85% đơn hàng gặp khó khăn trong khâu thu tiền và đối soát.</p>
    </div>
    <div class="grid-3">
      <div class="card">
        <div class="card-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;"><i class="fas fa-receipt"></i></div>
        <h3 class="card-title">Lừa đảo Bill giả (Fake Bill)</h3>
        <p class="card-text">Kẻ gian dùng công cụ tạo ảnh hóa đơn chuyển tiền giả. Chủ shop không kiểm tra tài khoản tức thì, vội giao hàng và bị mất trắng tài sản.</p>
      </div>
      <div class="card">
        <div class="card-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i class="fas fa-clock"></i></div>
        <h3 class="card-title">Đối soát thủ công chậm trễ</h3>
        <p class="card-text">Khách hàng chụp màn hình gửi qua Zalo/Facebook; nhân viên phải dò từng số dư, so khớp tên và số tiền khiến thời gian xác nhận đơn kéo dài hàng tiếng.</p>
      </div>
      <div class="card">
        <div class="card-icon" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;"><i class="fas fa-building-columns"></i></div>
        <h3 class="card-title">Rào cản Cổng thanh toán lớn</h3>
        <p class="card-text">VNPay, OnePay đòi hỏi Giấy phép kinh doanh, duyệt hồ sơ phức tạp và thu phí chiết khấu 1.5% - 2.5%/giao dịch, quá sức với hộ kinh doanh nhỏ.</p>
      </div>
    </div>
    <div class="code-box" style="margin-top: 15px;">
      <i class="fas fa-lightbulb highlight"></i> <b>Giải pháp đề xuất:</b> Xây dựng hệ sinh thái bán hàng <b>FreshMart</b> tích hợp thanh toán mã QR động <b>VietQR</b> kết hợp <b>Webhook tự động</b>: Chi phí 0đ, tự động nhảy số sau 2 giây!
    </div>
  </div>

  <!-- ==================== SLIDE 3 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-bullseye"></i> Mục tiêu & Phạm vi</div>
      <h2 class="slide-title">Mục tiêu cốt lõi của <span>Hệ thống FreshMart Pay</span></h2>
      <p class="slide-subtitle">Xây dựng trọn vẹn giải pháp E-Commerce + Payment Gateway cho ngành thực phẩm tươi sạch.</p>
    </div>
    <div class="grid-2">
      <div class="card">
        <h3 class="card-title" style="color: var(--primary);"><i class="fas fa-shopping-basket me-2"></i> Phân hệ Khách hàng (Storefront)</h3>
        <ul class="feature-list" style="margin-top: 15px;">
          <li><i class="fas fa-check-circle"></i> Trải nghiệm mua sắm mượt mà: Xem sản phẩm, phân loại, tìm kiếm.</li>
          <li><i class="fas fa-check-circle"></i> Giỏ hàng động chuẩn LocalStorage: Cập nhật số lượng, voucher giảm giá.</li>
          <li><i class="fas fa-check-circle"></i> Auth Gateway: Bắt buộc đăng nhập bảo mật trước khi đặt hàng.</li>
          <li><i class="fas fa-check-circle"></i> Trang thanh toán VietQR động: Tự tạo mã QR có số tiền và mã hóa đơn.</li>
        </ul>
      </div>
      <div class="card">
        <h3 class="card-title" style="color: #38bdf8;"><i class="fas fa-chart-line me-2"></i> Phân hệ Quản trị (Merchant Hub)</h3>
        <ul class="feature-list" style="margin-top: 15px;">
          <li><i class="fas fa-check-circle"></i> Dashboard tổng quan doanh thu: Thống kê số đơn, biểu đồ cột 7 ngày.</li>
          <li><i class="fas fa-check-circle"></i> Quản lý Cổng thanh toán: Đổi số tài khoản ngân hàng thụ hưởng nhận tiền.</li>
          <li><i class="fas fa-check-circle"></i> Phân quyền đa cấp RBAC: Owner, Staff, User có trang cấp quyền trực tiếp.</li>
          <li><i class="fas fa-check-circle"></i> Xử lý Webhook tự động: Đồng bộ trạng thái đơn hàng trong 2 giây.</li>
        </ul>
      </div>
    </div>
    <p style="font-size: 0.9rem; color: #64748b; margin-top: 10px;"><i>Phạm vi: Tập trung giải quyết thanh toán nội địa qua ngân hàng Việt Nam; không ôm đồm cổng thanh toán quốc tế Visa/Mastercard phức tạp.</i></p>
  </div>

  <!-- ==================== SLIDE 4 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-sitemap"></i> Kiến trúc hệ thống</div>
      <h2 class="slide-title">Mô hình phân tầng <span>Layered Architecture</span></h2>
      <p class="slide-subtitle">Tách bạch rõ ràng giữa Giao diện, REST API, Adapter xử lý và Cơ sở dữ liệu đám mây.</p>
    </div>
    <div class="grid-4">
      <div class="card center" style="text-align: center;">
        <div class="card-icon" style="margin: 0 auto 12px;"><i class="fab fa-html5"></i></div>
        <h4 class="card-title">1. Presentation</h4>
        <p class="card-text">HTML5, CSS3, JavaScript ES6, Bootstrap 5, Chart.js.<br><span class="highlight">Responsive 100%</span></p>
      </div>
      <div class="card center" style="text-align: center;">
        <div class="card-icon" style="margin: 0 auto 12px; background: rgba(56, 189, 248, 0.15); color: #38bdf8;"><i class="fab fa-php"></i></div>
        <h4 class="card-title">2. REST API</h4>
        <p class="card-text">PHP 8.2 Endpoint trả dữ liệu JSON chuẩn hóa.<br><span class="accent">Session Auth & RBAC</span></p>
      </div>
      <div class="card center" style="text-align: center;">
        <div class="card-icon" style="margin: 0 auto 12px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i class="fas fa-plug"></i></div>
        <h4 class="card-title">3. Adapter Layer</h4>
        <p class="card-text">VietQRAdapter chuẩn EMVCo/NAPAS 247.<br><span style="color:#f59e0b;">Idempotent Webhook</span></p>
      </div>
      <div class="card center" style="text-align: center;">
        <div class="card-icon" style="margin: 0 auto 12px; background: rgba(168, 85, 247, 0.15); color: #a855f7;"><i class="fas fa-database"></i></div>
        <h4 class="card-title">4. Database Cloud</h4>
        <p class="card-text">TiDB Cloud Serverless kết nối mã hóa SSL TLS.<br><span style="color:#a855f7;">PDO Prepared Stmt</span></p>
      </div>
    </div>
    <div class="code-box">
      <i class="fas fa-cubes highlight"></i> <b>Tổ chức mã nguồn:</b> Dự án được quy hoạch siêu gọn gàng thành đúng <b>6 thư mục chuẩn</b>: <code>html</code>, <code>php</code>, <code>css</code>, <code>js</code>, <code>csdl</code>, <code>doct</code>.
    </div>
  </div>

  <!-- ==================== SLIDE 5 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-database"></i> Cơ sở dữ liệu</div>
      <h2 class="slide-title">Mô hình thực thể quan hệ <span>ERD Chuẩn 3NF</span></h2>
      <p class="slide-subtitle">Thiết kế tối ưu hiệu năng, bảo mật định danh với khóa chính UUID (36 ký tự) và Index chỉ mục.</p>
    </div>
    <div class="grid-3">
      <div class="card">
        <h4 class="card-title" style="color: var(--primary);"><i class="fas fa-users me-2"></i> Bảng NguoiDung</h4>
        <p class="card-text"><code>id (UUID PK)</code>, <code>email (Unique)</code>, <code>mat_khau (Bcrypt)</code>, <code>ho_ten</code>, <code>vai_tro (Owner/Staff/User)</code>.</p>
      </div>
      <div class="card">
        <h4 class="card-title" style="color: #38bdf8;"><i class="fas fa-store me-2"></i> Bảng CuaHang & CongThanhToan</h4>
        <p class="card-text"><code>loai_cong ('VIETQR')</code>, <code>so_tai_khoan</code>, <code>ma_ngan_hang (BIN)</code>, <code>ten_chu_tai_khoan</code>, <code>api_key (AES-256)</code>, <code>trang_thai</code>.</p>
      </div>
      <div class="card">
        <h4 class="card-title" style="color: #f59e0b;"><i class="fas fa-file-invoice-dollar me-2"></i> Bảng GiaoDich & Webhook</h4>
        <p class="card-text"><code>ma_don_hang (Index)</code>, <code>so_tien</code>, <code>trang_thai (PENDING/SUCCESS)</code>, <code>du_lieu_qr</code>, <code>NhatKyWebhook (Audit JSON)</code>.</p>
      </div>
    </div>
    <div class="code-box" style="font-size: 0.85rem;">
      <span class="highlight">-- Ràng buộc toàn vẹn khóa ngoại (Foreign Keys) bảo vệ dữ liệu:</span><br>
      ALTER TABLE GiaoDich ADD CONSTRAINT fk_user FOREIGN KEY (nguoi_dung_id) REFERENCES NguoiDung(id) ON DELETE CASCADE;<br>
      ALTER TABLE CongThanhToan ADD CONSTRAINT fk_shop FOREIGN KEY (cua_hang_id) REFERENCES CuaHang(id) ON DELETE CASCADE;
    </div>
  </div>

  <!-- ==================== SLIDE 6 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-qrcode"></i> Module VietQR Adapter</div>
      <h2 class="slide-title">Sinh mã QR động theo chuẩn <span>EMVCo / NAPAS 247</span></h2>
      <p class="slide-subtitle">Khách hàng dùng bất kỳ app ngân hàng nào (MB, VCB, Techcombank...) quét mã là nhận đúng số tiền.</p>
    </div>
    <div class="grid-2">
      <div class="card">
        <h4 class="card-title">Quy cách đóng gói chuỗi VietQR Payload</h4>
        <ul class="feature-list" style="font-size: 0.95rem; margin-top: 10px;">
          <li><i class="fas fa-tag"></i> <b>ID 00:</b> Phiên bản quy chuẩn (01)</li>
          <li><i class="fas fa-tag"></i> <b>ID 01:</b> Phương thức khởi tạo (12: QR động có số tiền)</li>
          <li><i class="fas fa-tag"></i> <b>ID 38:</b> Mã định danh quốc gia NAPAS (A000000727) + Mã BIN ngân hàng + Số tài khoản thụ hưởng</li>
          <li><i class="fas fa-tag"></i> <b>ID 54:</b> Số tiền thanh toán thực tế của đơn hàng</li>
          <li><i class="fas fa-tag"></i> <b>ID 62:</b> Nội dung chuyển khoản (Mã đơn hàng: FM903964)</li>
          <li><i class="fas fa-tag"></i> <b>ID 63:</b> Thuật toán kiểm tra tính toàn vẹn <b>CRC-16/CCITT</b></li>
        </ul>
      </div>
      <div class="card center" style="text-align: center; justify-content: center; background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.3);">
        <i class="fas fa-qrcode" style="font-size: 8rem; color: #fff; margin-bottom: 15px;"></i>
        <h4 style="color: var(--primary);">Mã QR Động tự tạo tức thì</h4>
        <p style="font-size: 0.9rem; color: #94a3b8;">Không cần nhập tay số tài khoản hay số tiền<br>Triệt tiêu 100% lỗi chuyển nhầm tiền!</p>
      </div>
    </div>
  </div>

  <!-- ==================== SLIDE 7 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-bolt"></i> Xử lý Webhook Thời Gian Thực</div>
      <h2 class="slide-title">Quy trình bắt tiền tự động & Tính chất <span>Idempotent</span></h2>
      <p class="slide-subtitle">Không còn cảnh nhân viên kiểm tra app ngân hàng thủ công; hệ thống tự nhận tiền sau 2 giây!</p>
    </div>
    <div class="grid-4" style="margin-bottom: 20px;">
      <div class="card center" style="text-align: center;">
        <div style="font-size: 2rem; color: var(--primary); font-weight: bold; margin-bottom: 8px;">1</div>
        <p class="card-text">Khách quét QR chuyển khoản qua app ngân hàng</p>
      </div>
      <div class="card center" style="text-align: center;">
        <div style="font-size: 2rem; color: #38bdf8; font-weight: bold; margin-bottom: 8px;">2</div>
        <p class="card-text">Ngân hàng gửi tín hiệu POST Webhook biến động số dư</p>
      </div>
      <div class="card center" style="text-align: center;">
        <div style="font-size: 2rem; color: #f59e0b; font-weight: bold; margin-bottom: 8px;">3</div>
        <p class="card-text">Server lưu log JSON, kiểm tra đơn PENDING và cập nhật SUCCESS</p>
      </div>
      <div class="card center" style="text-align: center;">
        <div style="font-size: 2rem; color: #a855f7; font-weight: bold; margin-bottom: 8px;">4</div>
        <p class="card-text">Trang web khách hàng tự động báo thành công tức thì</p>
      </div>
    </div>
    <div class="code-box">
      <span class="highlight">// Cơ chế Idempotency chống cộng tiền hai lần khi mạng gửi lặp lại:</span><br>
      $stmt = $pdo->prepare("UPDATE GiaoDich SET trang_thai = 'SUCCESS' WHERE ma_don_hang = :code AND trang_thai = 'PENDING'");<br>
      if ($stmt->rowCount() === 0) { /* Bỏ qua an toàn vì đơn đã xử lý xong từ trước */ }
    </div>
  </div>

  <!-- ==================== SLIDE 8 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-desktop"></i> Giao diện người dùng (Storefront)</div>
      <h2 class="slide-title">Trải nghiệm mua sắm mượt mà trên <span>FreshMart</span></h2>
      <p class="slide-subtitle">Phong cách thiết kế xanh sạch, tải trang cực nhanh và đồng bộ số lượng giỏ hàng tức thời.</p>
    </div>
    <div class="grid-3">
      <div class="card">
        <h4 class="card-title"><i class="fas fa-home me-2" style="color:var(--primary);"></i> Trang chủ & Sản phẩm</h4>
        <p class="card-text">Hiển thị các danh mục rau củ, thịt cá tươi sống, bộ lọc khoảng giá và nút "Thêm vào giỏ" hiệu ứng trực quan.</p>
      </div>
      <div class="card">
        <h4 class="card-title"><i class="fas fa-shopping-cart me-2" style="color:#38bdf8;"></i> Giỏ hàng động</h4>
        <p class="card-text">Tăng giảm số lượng, xóa món có hiệu ứng trượt, tính tổng tiền tự động, áp dụng voucher <code>FRESHMART20</code> giảm 20.000đ.</p>
      </div>
      <div class="card">
        <h4 class="card-title"><i class="fas fa-shield-alt me-2" style="color:#f59e0b;"></i> Đăng nhập thông minh</h4>
        <p class="card-text">Bảo vệ luồng đặt hàng: tự động chuyển hướng đăng nhập và quay trở lại giỏ hàng đầy đủ, không bị mất món.</p>
      </div>
    </div>
    <div class="code-box">
      <i class="fas fa-check-circle highlight"></i> <b>Cơ chế Cache-Busting:</b> Script nội dòng đồng bộ icon giỏ hàng <code>localStorage</code> ngay lập tức, triệt tiêu hoàn toàn lỗi nhấp nháy số lượng cũ trên trình duyệt Cốc Cốc / Chrome.
    </div>
  </div>

  <!-- ==================== SLIDE 9 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-mobile-alt"></i> Màn hình Thanh toán VietQR</div>
      <h2 class="slide-title">Thanh toán không tiền mặt <span>Chỉ với 1 chạm</span></h2>
      <p class="slide-subtitle">Giao diện hướng dẫn trực quan, nút Copy tiện lợi và Polling trạng thái thời gian thực.</p>
    </div>
    <div class="grid-2">
      <div class="card">
        <h4 class="card-title" style="color: var(--primary);">Các tính năng hỗ trợ người dùng tối đa:</h4>
        <ul class="feature-list" style="margin-top: 15px;">
          <li><i class="fas fa-clock" style="color: #ef4444;"></i> <b>Đếm ngược 15 phút:</b> Nhắc nhở thời gian giữ đơn hàng.</li>
          <li><i class="fas fa-copy" style="color: #38bdf8;"></i> <b>Nút Sao chép 1-Click:</b> Copy nhanh STK, Số tiền và Nội dung.</li>
          <li><i class="fas fa-sync-alt" style="color: var(--primary);"></i> <b>Polling 2 giây:</b> Tự động phát hiện thanh toán thành công mà khách không cần nhấn nút F5 tải lại trang.</li>
          <li><i class="fas fa-magic" style="color: #f59e0b;"></i> <b>Nút Giả lập Webhook:</b> Hỗ trợ hội đồng chấm thi test thử chuyển trạng thái ngay trong buổi bảo vệ!</li>
        </ul>
      </div>
      <div class="card center" style="background: rgba(15, 23, 42, 0.8); border: 2px dashed rgba(16, 185, 129, 0.4);">
        <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 10px;">URL Trực tiếp trên Cloud Render:</p>
        <p style="font-size: 1.15rem; font-weight: bold; color: #fff;">doan-yteb.onrender.com/html/payment_qr.html</p>
        <div style="margin-top: 20px; padding: 10px 20px; background: rgba(16, 185, 129, 0.2); border-radius: 50px; color: var(--primary); font-weight: bold;">
          <i class="fas fa-wifi me-2"></i> Tự động kích hoạt qua Webhook
        </div>
      </div>
    </div>
  </div>

  <!-- ==================== SLIDE 10 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-chart-pie"></i> Bảng điều khiển Merchant</div>
      <h2 class="slide-title">Trung tâm giám sát tài chính <span>FreshMart Pay</span></h2>
      <p class="slide-subtitle">Chủ cửa hàng nắm bắt toàn bộ dòng tiền và sức khỏe kinh doanh trong chớp mắt.</p>
    </div>
    <div class="grid-4" style="margin-bottom: 24px;">
      <div class="card center" style="text-align: center;">
        <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase;">Doanh thu hôm nay</p>
        <p style="font-size: 1.8rem; font-weight: 800; color: #38bdf8; margin: 6px 0;">297.000 đ</p>
        <span style="font-size: 0.75rem; color: var(--primary);"><i class="fas fa-arrow-up"></i> Cập nhật tức thì</span>
      </div>
      <div class="card center" style="text-align: center;">
        <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase;">Thành công</p>
        <p style="font-size: 1.8rem; font-weight: 800; color: var(--primary); margin: 6px 0;">1 đơn</p>
        <span style="font-size: 0.75rem; color: var(--primary);">Đã nhận tiền</span>
      </div>
      <div class="card center" style="text-align: center;">
        <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase;">Đang chờ</p>
        <p style="font-size: 1.8rem; font-weight: 800; color: #f59e0b; margin: 6px 0;">3 đơn</p>
        <span style="font-size: 0.75rem; color: #f59e0b;">Chờ khách quét QR</span>
      </div>
      <div class="card center" style="text-align: center;">
        <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase;">Thất bại / Hủy</p>
        <p style="font-size: 1.8rem; font-weight: 800; color: #ef4444; margin: 6px 0;">0 đơn</p>
        <span style="font-size: 0.75rem; color: #64748b;">Tỉ lệ hủy: 0%</span>
      </div>
    </div>
    <div class="grid-2">
      <div class="card">
        <h4 class="card-title"><i class="fas fa-chart-bar me-2" style="color:var(--primary);"></i> Biểu đồ cột doanh thu 7 ngày (Chart.js)</h4>
        <p class="card-text">Vẽ biểu đồ động theo ngày, tự động tính tổng doanh số bán thực phẩm tươi sạch trong tuần.</p>
      </div>
      <div class="card">
        <h4 class="card-title"><i class="fas fa-filter me-2" style="color:#38bdf8;"></i> Bộ lọc giao dịch đa chiều</h4>
        <p class="card-text">Lọc theo trạng thái (Tất cả / Thành công / Chờ), loại cổng (VietQR/COD) và nút xác nhận tiền thủ công dự phòng.</p>
      </div>
    </div>
  </div>

  <!-- ==================== SLIDE 11 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-user-shield"></i> Cấu hình & Phân quyền</div>
      <h2 class="slide-title">Quản trị linh hoạt <span>Đổi STK & Cấp quyền CSDL</span></h2>
      <p class="slide-subtitle">Không cần can thiệp vào code; chủ shop tự đổi số tài khoản nhận tiền và phân quyền tài xế/nhân viên.</p>
    </div>
    <div class="grid-2">
      <div class="card">
        <h4 class="card-title" style="color: var(--primary);"><i class="fas fa-credit-card me-2"></i> Trang Cổng thanh toán (gateways.php)</h4>
        <ul class="feature-list" style="margin-top: 15px;">
          <li><i class="fas fa-check"></i> Đổi số tài khoản ngân hàng thụ hưởng (VD: MB Bank, Vietcombank...).</li>
          <li><i class="fas fa-check"></i> Chọn mã BIN ngân hàng và Tên chủ tài khoản in hoa không dấu.</li>
          <li><i class="fas fa-check"></i> Bấm "Lưu cấu hình" -> Khách hàng tiếp theo quét mã sẽ lập tức chuyển vào tài khoản mới.</li>
        </ul>
      </div>
      <div class="card">
        <h4 class="card-title" style="color: #38bdf8;"><i class="fas fa-users-cog me-2"></i> Trang Cấp quyền tài khoản (permissions.php)</h4>
        <ul class="feature-list" style="margin-top: 15px;">
          <li><i class="fas fa-check"></i> Thanh tìm kiếm tài khoản thông minh theo email hoặc họ tên.</li>
          <li><i class="fas fa-check"></i> Chuyển đổi linh hoạt giữa 3 vai trò: <b>OWNER</b> (Chủ shop), <b>STAFF</b> (Nhân viên), <b>USER</b> (Khách).</li>
          <li><i class="fas fa-check"></i> Ghi nhận trực tiếp vào CSDL TiDB, tài khoản có quyền truy cập trang quản trị ngay lập tức.</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- ==================== SLIDE 12 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-lock"></i> An toàn thông tin</div>
      <h2 class="slide-title">Tiêu chuẩn bảo mật <span>Chuẩn OWASP Top 10</span></h2>
      <p class="slide-subtitle">Bảo vệ toàn diện dữ liệu khách hàng và số dư tài chính của cửa hàng.</p>
    </div>
    <div class="grid-4">
      <div class="card">
        <h4 class="card-title" style="color: #38bdf8;">Bcrypt Hashing</h4>
        <p class="card-text">Mật khẩu được băm một chiều với Salt ngẫu nhiên qua <code>password_hash()</code>, chống crack từ điển 100%.</p>
      </div>
      <div class="card">
        <h4 class="card-title" style="color: var(--primary);">AES-256-CBC</h4>
        <p class="card-text">Mã hóa đối xứng các khóa API Secret Token lưu trong CSDL qua <code>openssl_encrypt()</code>.</p>
      </div>
      <div class="card">
        <h4 class="card-title" style="color: #f59e0b;">PDO Param Binding</h4>
        <p class="card-text">Prepared Statements triệt tiêu hoàn toàn nguy cơ tấn công SQL Injection trên mọi form nhập liệu.</p>
      </div>
      <div class="card">
        <h4 class="card-title" style="color: #a855f7;">HMAC-SHA256</h4>
        <p class="card-text">Xác thực chữ ký số Webhook để đảm bảo gói tin đến từ ngân hàng thật, chống kẻ gian giả mạo tín hiệu.</p>
      </div>
    </div>
    <div class="code-box">
      <i class="fas fa-shield-virus highlight"></i> <b>Bảo vệ tệp mật khẩu (.gitignore):</b> File chứa mật khẩu CSDL <code>csdl/db.php</code> và <code>security.php</code> tuyệt đối không đưa lên GitHub; sử dụng cơ chế tạo file tự động từ file mẫu khi deploy Docker.
    </div>
  </div>

  <!-- ==================== SLIDE 13 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-cloud-upload-alt"></i> Triển khai thực tế</div>
      <h2 class="slide-title">Vận hành trực tiếp trên <span>Nền tảng Đám mây (Cloud)</span></h2>
      <p class="slide-subtitle">Không chỉ chạy thử nghiệm trên máy cá nhân localhost; hệ thống đã chạy thật 100% trên Internet.</p>
    </div>
    <div class="grid-3">
      <div class="card center" style="text-align: center;">
        <div class="card-icon" style="margin: 0 auto 12px;"><i class="fab fa-docker"></i></div>
        <h4 class="card-title">Docker Container</h4>
        <p class="card-text">Đóng gói chuẩn môi trường PHP 8.2 Apache, tự động kích hoạt rewrite module và cấu hình cổng mạng 10000.</p>
      </div>
      <div class="card center" style="text-align: center;">
        <div class="card-icon" style="margin: 0 auto 12px; background: rgba(56, 189, 248, 0.15); color: #38bdf8;"><i class="fas fa-server"></i></div>
        <h4 class="card-title">Render Cloud Hosting</h4>
        <p class="card-text">Triển khai Web Service tự động đồng bộ CI/CD từ GitHub repo. Tên miền HTTPS có chứng chỉ SSL Cloudflare miễn phí.</p>
      </div>
      <div class="card center" style="text-align: center;">
        <div class="card-icon" style="margin: 0 auto 12px; background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i class="fas fa-database"></i></div>
        <h4 class="card-title">TiDB Cloud Serverless</h4>
        <p class="card-text">Cơ sở dữ liệu phân tán chuẩn MySQL đặt trên AWS, kết nối bảo mật qua chứng chỉ số <code>isrgrootx1.pem</code>.</p>
      </div>
    </div>
    <div class="code-box center" style="text-align: center; font-size: 1.1rem; padding: 14px;">
      🌐 <b>Đường dẫn ứng dụng trực tuyến:</b> <a href="https://doan-yteb.onrender.com" target="_blank" style="color: #38bdf8; text-decoration: underline;">https://doan-yteb.onrender.com</a>
    </div>
  </div>

  <!-- ==================== SLIDE 14 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-vial"></i> Kiểm thử chất lượng</div>
      <h2 class="slide-title">Kế hoạch & Kết quả <span>Kiểm thử toàn diện</span></h2>
      <p class="slide-subtitle">Đã xuất bản file Word Test Plan & Test Cases chi tiết và hoàn thành bộ test tự động đạt 100% Pass.</p>
    </div>
    <div class="grid-2">
      <div class="card">
        <h4 class="card-title" style="color: var(--primary);"><i class="fas fa-clipboard-check me-2"></i> Hồ sơ kiểm thử (Test Cases)</h4>
        <ul class="feature-list" style="margin-top: 15px;">
          <li><i class="fas fa-check"></i> <b>File Word chuyên nghiệp:</b> Đã tạo tệp <code>doct/TEST_PLAN_AND_CASES.docx</code>.</li>
          <li><i class="fas fa-check"></i> <b>Kiểm thử chức năng:</b> 10/10 ca kiểm thử then chốt đều đạt (Auth, Giỏ hàng, Đặt hàng, Tạo QR, Webhook, RBAC).</li>
          <li><i class="fas fa-check"></i> <b>Kiểm thử giao diện:</b> Không vỡ layout khi chuyển đổi giữa điện thoại và máy tính.</li>
        </ul>
      </div>
      <div class="card">
        <h4 class="card-title" style="color: #38bdf8;"><i class="fas fa-terminal me-2"></i> Smoke Tests tự động (run_smoke_tests.php)</h4>
        <div class="code-box" style="font-size: 0.8rem; margin-top: 10px;">
          [TEST 1] Sinh chuỗi UUID 36 ký tự: <span class="highlight">PASS</span><br>
          [TEST 2] Mã hóa AES-256 đối xứng: <span class="highlight">PASS</span><br>
          [TEST 3] Băm mật khẩu Bcrypt: <span class="highlight">PASS</span><br>
          [TEST 4] Logic phân quyền isPrivileged: <span class="highlight">PASS</span><br>
          [TEST 5] Xác thực chữ ký HMAC-SHA256: <span class="highlight">PASS</span><br>
          [TEST 6] Kết nối CSDL TiDB Cloud: <span class="highlight">PASS</span><br>
          ---------------------------------------------<br>
          <b>TỔNG KẾT: 100% CÁC BÀI TEST ĐẠT KẾT QUẢ PASS!</b>
        </div>
      </div>
    </div>
  </div>

  <!-- ==================== SLIDE 15 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-tasks"></i> Tiến độ thực hiện</div>
      <h2 class="slide-title">Hoàn thành <span>Vượt mức kế hoạch 15 tuần</span></h2>
      <p class="slide-subtitle">Bám sát lộ trình đồ án chuyên ngành và hoàn thành sớm các mốc kỹ thuật quan trọng.</p>
    </div>
    <div class="grid-3">
      <div class="card">
        <div style="color: var(--primary); font-weight: 800; font-size: 1.1rem; margin-bottom: 8px;">GIAI ĐOẠN 1 (Tuần 1–6)</div>
        <h4 class="card-title">Khảo sát & Thiết kế</h4>
        <p class="card-text">Khảo sát nghiệp vụ, phân tích FR/NFR, xây dựng ERD CSDL và thiết kế giao diện Figma. <b style="color:var(--primary);">(100% Xong)</b></p>
      </div>
      <div class="card">
        <div style="color: #38bdf8; font-weight: 800; font-size: 1.1rem; margin-bottom: 8px;">GIAI ĐOẠN 2 (Tuần 7–11)</div>
        <h4 class="card-title">Phát triển & Kiểm thử</h4>
        <p class="card-text">Lập trình Backend REST API, VietQRAdapter, Webhook engine, Dashboard và chạy kiểm thử tự động. <b style="color:#38bdf8;">(100% Xong)</b></p>
      </div>
      <div class="card">
        <div style="color: #f59e0b; font-weight: 800; font-size: 1.1rem; margin-bottom: 8px;">GIAI ĐOẠN 3 (Tuần 12–15)</div>
        <h4 class="card-title">Hoàn thiện & Bảo vệ</h4>
        <p class="card-text">Sắp xếp 6 thư mục chuẩn, deploy Render Cloud, viết báo cáo Word theo mẫu và slide bảo vệ. <b style="color:#f59e0b;">(Sẵn sàng 100%)</b></p>
      </div>
    </div>
    <div class="code-box" style="text-align: center;">
      🎉 <b>Tự đánh giá:</b> Đồ án đã hoàn thành toàn diện cả phần lý thuyết báo cáo và sản phẩm ứng dụng thực tế.
    </div>
  </div>

  <!-- ==================== SLIDE 16 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-play-circle"></i> Kịch bản trình diễn</div>
      <h2 class="slide-title">Kịch bản <span>Demo trực tiếp cho Hội đồng</span></h2>
      <p class="slide-subtitle">Quy trình 3 bước chứng minh tính năng tự động hóa không tiền mặt của hệ thống.</p>
    </div>
    <div class="grid-3">
      <div class="card">
        <div class="card-icon"><i class="fas fa-cart-plus"></i></div>
        <h4 class="card-title">Bước 1: Mua sắm & Đặt đơn</h4>
        <p class="card-text">Khách chọn Cà chua bi, Thịt bò Úc vào giỏ hàng. Đăng nhập tài khoản và chọn phương thức "Chuyển khoản VietQR".</p>
      </div>
      <div class="card">
        <div class="card-icon" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;"><i class="fas fa-qrcode"></i></div>
        <h4 class="card-title">Bước 2: Quét mã & Bắn Webhook</h4>
        <p class="card-text">Hệ thống tạo mã VietQR chuẩn mang mã đơn hàng FM903964. Thực hiện quét bằng app ngân hàng thật hoặc bấm "Giả lập Webhook".</p>
      </div>
      <div class="card">
        <div class="card-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;"><i class="fas fa-chart-line"></i></div>
        <h4 class="card-title">Bước 3: Nhảy số Real-time</h4>
        <p class="card-text">Màn hình thanh toán báo thành công ngay lập tức; mở Dashboard xem doanh thu và danh sách đơn tự động tăng số.</p>
      </div>
    </div>
    <div class="code-box">
      <i class="fas fa-shield-alt highlight"></i> <b>Trình diễn thêm:</b> Vào trang <code>permissions.php</code> tìm kiếm tài khoản và cấp quyền STAFF/OWNER thay đổi CSDL trực tiếp.
    </div>
  </div>

  <!-- ==================== SLIDE 17 ==================== -->
  <div class="slide">
    <div>
      <div class="slide-badge"><i class="fas fa-flag-checkered"></i> Tổng kết đề tài</div>
      <h2 class="slide-title">Kết luận & <span>Hướng phát triển tương lai</span></h2>
      <p class="slide-subtitle">Giải pháp thanh toán thiết thực, hiệu quả và sẵn sàng mở rộng quy mô lớn.</p>
    </div>
    <div class="grid-2">
      <div class="card">
        <h4 class="card-title" style="color: var(--primary);">Giá trị thực tiễn mang lại:</h4>
        <ul class="feature-list" style="margin-top: 15px;">
          <li><i class="fas fa-check"></i> Giúp các cửa hàng nhỏ tiết kiệm 100% phí đăng ký cổng thanh toán doanh nghiệp.</li>
          <li><i class="fas fa-check"></i> Loại bỏ hoàn toàn nguy cơ mất tiền bởi chiêu trò lừa đảo "hóa đơn chuyển tiền giả".</li>
          <li><i class="fas fa-check"></i> Giảm thời gian xác nhận đơn từ 15-30 phút xuống chỉ còn 2-3 giây.</li>
        </ul>
      </div>
      <div class="card">
        <h4 class="card-title" style="color: #38bdf8;">Định hướng nâng cấp tiếp theo:</h4>
        <ul class="feature-list" style="margin-top: 15px;">
          <li><i class="fas fa-arrow-right"></i> Phát triển ứng dụng di động Mobile App có âm thanh thông báo chuông "Ting ting" khi có tiền về.</li>
          <li><i class="fas fa-arrow-right"></i> Tích hợp công nghệ AI phân tích xu hướng mua sắm thực phẩm để dự báo lượng hàng tồn kho.</li>
          <li><i class="fas fa-arrow-right"></i> Đóng gói thành Plugin SaaS tích hợp nhanh vào WooCommerce, Haravan, Shopify.</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- ==================== SLIDE 18 ==================== -->
  <div class="slide center" style="text-align: center; justify-content: center; align-items: center;">
    <div class="slide-badge"><i class="fas fa-heart" style="color: #ef4444;"></i> LỜI CẢM ƠN CHÂN THÀNH</div>
    <h1 style="font-size: 3.5rem; font-weight: 800; color: #fff; margin: 20px 0 10px;">
      XIN CẢM ƠN <span style="color: var(--primary);">QUÝ THẦY CÔ!</span>
    </h1>
    <p style="font-size: 1.3rem; color: #94a3b8; max-width: 800px; margin-bottom: 35px; line-height: 1.6;">
      Nhóm sinh viên xin trân trọng cảm ơn Hội đồng chấm thi đã chú ý lắng nghe.<br>
      Kính mong nhận được những ý kiến đóng góp quý báu từ quý Thầy Cô!
    </p>

    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 20px 50px; display: inline-flex; gap: 40px; text-align: left;">
      <div>
        <p style="font-size: 0.85rem; color: #64748b; text-transform: uppercase;">Sinh viên thực hiện:</p>
        <p style="font-size: 1.1rem; font-weight: bold;">Đinh Thế Chí Dũng & Nguyễn Tuấn Hưng</p>
      </div>
      <div style="border-left: 1px solid rgba(255,255,255,0.1); padding-left: 40px;">
        <p style="font-size: 0.85rem; color: #64748b; text-transform: uppercase;">Trải nghiệm Live Demo:</p>
        <p style="font-size: 1.1rem; font-weight: bold; color: #38bdf8;"><a href="https://doan-yteb.onrender.com" target="_blank" style="color: #38bdf8;">doan-yteb.onrender.com</a></p>
      </div>
    </div>
    
    <div style="margin-top: 30px; font-size: 1.4rem; font-weight: bold; color: var(--accent);">
      <i class="fas fa-comments me-2"></i> PHIÊN HỎI & ĐÁP (Q & A)
    </div>
  </div>

</div>

<!-- Thanh điều khiển Bottom Bar -->
<div class="bottom-bar">
  <button class="ctrl-btn" id="prevBtn" title="Slide trước (Phím Mũi tên Trái)"><i class="fas fa-chevron-left"></i></button>
  <span class="slide-counter" id="slideCounter">1 / 18</span>
  <button class="ctrl-btn" id="nextBtn" title="Slide sau (Phím Mũi tên Phải)"><i class="fas fa-chevron-right"></i></button>
  <div style="width: 1px; height: 24px; background: rgba(255,255,255,0.2);"></div>
  <div class="timer-pill" id="timerDisplay" title="Bộ đếm giờ thuyết trình (15 phút)"><i class="fas fa-stopwatch"></i> 15:00</div>
  <button class="ctrl-btn" id="fullScreenBtn" title="Chế độ toàn màn hình (F11)"><i class="fas fa-expand"></i></button>
</div>

<script>
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slide');
  const totalSlides = slides.length;
  const slideCounter = document.getElementById('slideCounter');
  const progressBar = document.getElementById('progressBar');

  function updateSlide(index) {
    slides.forEach((s, idx) => {
      s.classList.toggle('active', idx === index);
    });
    currentSlide = index;
    slideCounter.textContent = (currentSlide + 1) + ' / ' + totalSlides;
    progressBar.style.width = (((currentSlide + 1) / totalSlides) * 100) + '%';
  }

  document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentSlide < totalSlides - 1) updateSlide(currentSlide + 1);
  });
  document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentSlide > 0) updateSlide(currentSlide - 1);
  });

  // Bắt phím điều hướng bàn phím
  window.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight' || e.key === ' ' || e.key === 'PageDown') {
      if (currentSlide < totalSlides - 1) updateSlide(currentSlide + 1);
    } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
      if (currentSlide > 0) updateSlide(currentSlide - 1);
    } else if (e.key === 'Home') {
      updateSlide(0);
    } else if (e.key === 'End') {
      updateSlide(totalSlides - 1);
    } else if (e.key.toLowerCase() === 'f') {
      toggleFullScreen();
    }
  });

  // Chế độ toàn màn hình
  function toggleFullScreen() {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen().catch(err => {});
    } else {
      if (document.exitFullscreen) document.exitFullscreen();
    }
  }
  document.getElementById('fullScreenBtn').addEventListener('click', toggleFullScreen);

  // Bộ bấm giờ đếm ngược 15 phút bảo vệ đồ án
  let timeLeft = 15 * 60;
  const timerDisplay = document.getElementById('timerDisplay');
  setInterval(() => {
    if (timeLeft > 0) {
      timeLeft--;
      const min = Math.floor(timeLeft / 60);
      const sec = timeLeft % 60;
      timerDisplay.innerHTML = `<i class="fas fa-stopwatch"></i> ${min.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
      if (timeLeft < 3 * 60) {
        timerDisplay.style.background = 'rgba(239, 68, 68, 0.2)';
        timerDisplay.style.color = '#ef4444';
      }
    } else {
      timerDisplay.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Hết giờ!`;
    }
  }, 1000);
</script>

</body>
</html>
HTML;

file_put_contents($outDir . '/SLIDE_THUYET_TRINH.html', $slideHtml);
echo "-> Đã tạo Slide thuyết trình HTML tương tác: $outDir/SLIDE_THUYET_TRINH.html\n";

// 2. TẠO KỊCH BẢN THUYẾT TRÌNH MARKDOWN VÀ WORD
$notes = <<<'MARKDOWN'
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
MARKDOWN;

file_put_contents($outDir . '/SLIDE_THUYET_TRINH.md', $notes);
echo "-> Đã tạo Kịch bản thuyết trình Markdown (.md): $outDir/SLIDE_THUYET_TRINH.md\n";

$docHtml = "<!DOCTYPE html><html><head><meta charset='utf-8'><style>body{font-family:'Times New Roman',serif;font-size:12pt;line-height:1.6;margin:25mm;}h1,h2,h3{color:#047857;}</style></head><body>"
  . nl2br(htmlspecialchars($notes)) . "</body></html>";
file_put_contents($outDir . '/SLIDE_THUYET_TRINH.doc', "\xEF\xBB\xBF" . $docHtml);
echo "-> Đã tạo Kịch bản thuyết trình Word (.doc): $outDir/SLIDE_THUYET_TRINH.doc\n";
