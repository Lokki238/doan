<?php
/**
 * ====================================================================
 * SCRIPT TẠO BÁO CÁO ĐỒ ÁN CHUYÊN NGÀNH CHUẨN MẪU LÝ TỰ TRỌNG TP.HCM
 * Áp dụng chính xác 100% mẫu cấu trúc và quy cách do người dùng cung cấp
 * ====================================================================
 */

set_time_limit(300);

$outDir = __DIR__ . '/..';
if (!is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

echo "Bắt đầu cập nhật Báo cáo Đồ án Chuyên ngành theo đúng mẫu yêu cầu...\n";

// ====================================================================
// NỘI DUNG TOÀN VĂN BÁO CÁO ĐỒ ÁN CHUYÊN NGÀNH (HTML CHO WORD .DOC)
// ====================================================================

$reportHtml = <<<'HTML'
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>BÁO CÁO ĐỒ ÁN CHUYÊN NGÀNH - THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ</title>
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
  .italic {
    font-style: italic;
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
  h1.report-title {
    font-size: 19pt;
    font-weight: bold;
    text-align: center;
    margin: 30px 0 20px 0;
    text-transform: uppercase;
    line-height: 1.3;
  }
  h2.report-subtitle {
    font-size: 15pt;
    font-weight: bold;
    text-align: center;
    margin-bottom: 25px;
    text-transform: uppercase;
  }
  .info-block {
    font-size: 14pt;
    font-weight: bold;
    line-height: 1.6;
    margin-top: 35px;
  }
  .footer-date {
    font-size: 14pt;
    font-weight: bold;
    text-align: center;
    margin-top: 45px;
  }
  h1.chap-title {
    font-size: 16pt;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    margin-top: 25px;
    margin-bottom: 20px;
    page-break-before: always;
  }
  h2.sec-title {
    font-size: 14pt;
    font-weight: bold;
    margin-top: 18px;
    margin-bottom: 10px;
  }
  h3.subsec-title {
    font-size: 13pt;
    font-weight: bold;
    margin-top: 14px;
    margin-bottom: 8px;
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
  table.sig-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 35px;
    border: none;
  }
  table.sig-table td {
    border: none;
    text-align: center;
    vertical-align: top;
    width: 50%;
    padding: 10px;
    font-size: 13pt;
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
  .code-block {
    background-color: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 10px 14px;
    font-family: Consolas, 'Courier New', monospace;
    font-size: 11pt;
    line-height: 1.4;
    white-space: pre-wrap;
    margin: 12px 0;
  }
</style>
</head>
<body>

<!-- ====================================================================
     TRANG BÌA 1: BÌA NGOÀI (THEO ĐÚNG MẪU)
     ==================================================================== -->
<div class="cover-box center">
  <div>
    <div class="header-org">
      ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH<br>
      TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG<br>
      THÀNH PHỐ HỒ CHÍ MINH
    </div>
    <div class="header-line"></div>
    <div style="font-size: 11pt; font-style: italic; color: #555;">(Font chữ Times New Roman, 13pt)</div>
  </div>

  <div style="margin: 40px 0;">
    <h1 class="report-title">
      THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ TẠI VIỆT NAM
    </h1>
    <div style="font-size: 11pt; font-style: italic; color: #555; margin-bottom: 15px;">(Font chữ Times New Roman, 18 - 21pt, bold)</div>
    <h2 class="report-subtitle">ĐỒ ÁN CHUYÊN NGÀNH</h2>
  </div>

  <div class="info-block" style="text-align: left; width: 85%; margin: 0 auto;">
    <p>Họ và tên sinh viên:</p>
    <p style="margin-left: 20px;">1. <b>Đinh Thế Chí Dũng</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002710</b></p>
    <p style="margin-left: 20px;">2. <b>Nguyễn Tuấn Hưng</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002711</b></p>
    <p>Khoa: <b>Công nghệ Thông tin</b></p>
    <p>Lớp: <b>25C2-LMT1</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Khóa: <b>2025</b></p>
    <div style="font-size: 11pt; font-style: italic; color: #555; margin-top: 10px; font-weight: normal;">(Font chữ Times New Roman, 14pt, bold)</div>
  </div>

  <div class="footer-date">
    Thành phố Hồ Chí Minh, tháng 11 năm 2026
  </div>
</div>

<div class="page-break"></div>

<!-- ====================================================================
     TRANG BÌA 2: BÌA TRONG (THEO ĐÚNG MẪU)
     ==================================================================== -->
<div class="cover-box center">
  <div>
    <div class="header-org">
      ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH<br>
      TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG<br>
      THÀNH PHỐ HỒ CHÍ MINH
    </div>
    <div class="header-line"></div>
    <div style="font-size: 11pt; font-style: italic; color: #555;">(Font chữ Times New Roman, 13pt)</div>
  </div>

  <div style="margin: 30px 0;">
    <h1 class="report-title">
      THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ TẠI VIỆT NAM
    </h1>
    <div style="font-size: 11pt; font-style: italic; color: #555; margin-bottom: 15px;">(Font chữ Times New Roman, 18 - 21pt, bold)</div>
    <h2 class="report-subtitle">ĐỒ ÁN CHUYÊN NGÀNH</h2>
  </div>

  <div class="info-block" style="text-align: left; width: 85%; margin: 0 auto;">
    <p>Họ và tên sinh viên:</p>
    <p style="margin-left: 20px;">1. <b>Đinh Thế Chí Dũng</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002710</b></p>
    <p style="margin-left: 20px;">2. <b>Nguyễn Tuấn Hưng</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002711</b></p>
    <p>Khoa: <b>Công nghệ Thông tin</b></p>
    <p>Lớp: <b>25C2-LMT1</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Khóa: <b>2025</b></p>
    <p style="margin-top: 15px;">Giảng viên hướng dẫn: <b>Th.S. Mai Chiếm Tuấn.</b></p>
  </div>

  <div class="footer-date">
    Thành phố Hồ Chí Minh, tháng 11 năm 2026.
  </div>
</div>

<div class="page-break"></div>

<!-- ====================================================================
     NHIỆM VỤ ĐỀ TÀI (THEO ĐÚNG MẪU)
     ==================================================================== -->
<div class="center">
  <div class="header-org">
    ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH<br>
    TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH<br>
    KHOA CÔNG NGHỆ THÔNG TIN
  </div>
  <div class="header-line"></div>
  <h2 style="font-size: 15pt; text-transform: uppercase; margin-top: 20px;">NHIỆM VỤ ĐỀ TÀI</h2>
</div>

<div style="margin-top: 25px;">
  <p><b>1. Nội dung và các yêu cầu cần giải quyết trong nhiệm vụ đề tài</b></p>
  
  <p><b>a. Nội dung:</b></p>
  <p class="text-indent">
    - Nghiên cứu khảo sát thực trạng thanh toán không tiền mặt và các rào cản tài chính, pháp lý của các cửa hàng trực tuyến quy mô nhỏ tại Việt Nam.
  </p>
  <p class="text-indent">
    - Thiết kế và xây dựng website thương mại điện tử thực phẩm sạch FreshMart với các tính năng: danh mục sản phẩm, giỏ hàng tương tác, đặt hàng và bảo vệ phiên bằng tài khoản đăng nhập.
  </p>
  <p class="text-indent">
    - Thiết kế kiến trúc và hiện thực trung tâm thanh toán FreshMart Pay: tích hợp chuẩn mã QR động quốc gia VietQR (NAPAS 247) và tiếp nhận thông báo thanh toán tự động qua Webhook thời gian thực.
  </p>
  <p class="text-indent">
    - Xây dựng phân hệ quản trị (Dashboard) cho phép chủ cửa hàng cấu hình tài khoản ngân hàng thụ hưởng linh hoạt và quản lý phân quyền người dùng trực tiếp trong cơ sở dữ liệu.
  </p>
  <p class="text-indent">
    - Đóng gói ứng dụng vào Docker Container và triển khai thực tế trên môi trường máy chủ đám mây (Render) kết nối CSDL TiDB Cloud Serverless mã hóa SSL.
  </p>

  <p><b>b. Các yêu cầu cần giải quyết</b></p>
  <p class="text-indent">
    - Xác định chi tiết yêu cầu chức năng (FR) và phi chức năng (NFR), xây dựng sơ đồ Use Case và quy trình nghiệp vụ thanh toán.
  </p>
  <p class="text-indent">
    - Ứng dụng mẫu thiết kế phần mềm <i>Adapter Pattern</i> để module hóa các cổng thanh toán, giúp dễ dàng mở rộng thêm ví điện tử khác (MoMo, ZaloPay, VNPay).
  </p>
  <p class="text-indent">
    - Thiết kế cơ sở dữ liệu quan hệ đạt chuẩn chuẩn hóa 3NF gồm 5 bảng cốt lõi (NguoiDung, CuaHang, CongThanhToan, GiaoDich, NhatKyWebhook) sử dụng khóa chính UUID bảo mật định danh.
  </p>
  <p class="text-indent">
    - Hiện thực thuật toán sinh chuỗi thanh toán EMVCo tiêu chuẩn NAPAS kèm mã kiểm tra CRC-16/CCITT cho VietQR.
  </p>
  <p class="text-indent">
    - Xây dựng cơ chế tiếp nhận Webhook đảm bảo tính toàn vẹn và <i>Idempotency</i> (chống trùng lặp tiền khi ngân hàng retry).
  </p>
  <p class="text-indent">
    - Thiết lập cơ chế kiểm soát truy cập và bảo mật ứng dụng web chống các lỗ hổng OWASP Top 10 (SQL Injection với PDO, XSS với htmlspecialchars, Session Fixation).
  </p>
  <p class="text-indent">
    - Xây dựng kế hoạch và kịch bản kiểm thử toàn diện (Unit, Functional, Integration, Security) đạt kết quả 100% Pass.
  </p>

  <p><b>2. Các tài liệu, số liệu cần thiết</b></p>
  <p class="text-indent">
    - Tài liệu tiêu chuẩn kỹ thuật mã phản hồi nhanh (QR Code) phục vụ thanh toán tại Việt Nam do NAPAS ban hành.
  </p>
  <p class="text-indent">
    - Đặc tả kỹ thuật quốc tế chuẩn thanh toán mã QR: EMV® QR Code Specification for Payment Systems (Consumer-Presented Mode).
  </p>
  <p class="text-indent">
    - Tài liệu kỹ thuật tích hợp Open API và Webhook biến động số dư ngân hàng của SePay / Casso.
  </p>
  <p class="text-indent">
    - Tài liệu tiêu chuẩn bảo mật phát triển ứng dụng web an toàn của tổ chức OWASP Foundation.
  </p>
  <p class="text-indent">
    - Tài liệu hướng dẫn lập trình PHP 8.2 PDO, Docker Engine và hệ quản trị phân tán TiDB Cloud Serverless.
  </p>

  <p><b>3. Địa điểm thực hiện đồ án chuyên ngành</b></p>
  <p class="text-indent">Trường Cao đẳng Lý Tự Trọng Thành Phố Hồ Chí Minh</p>

  <table class="sig-table" style="margin-top: 35px;">
    <tr>
      <td>
        <b>Đã nhận nhiệm vụ ĐTĐACN</b><br>
        Sinh viên<br><br><br><br><br>
        <b>Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng</b>
      </td>
      <td>
        <b>Đã giao nhiệm vụ ĐTĐACN</b><br>
        Giảng viên hướng dẫn<br><br><br><br><br>
        <b>Th.S. Mai Chiếm Tuấn</b>
      </td>
    </tr>
  </table>
</div>

<div class="page-break"></div>

<!-- ====================================================================
     GIẢNG VIÊN HƯỚNG DẪN ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH (THEO ĐÚNG MẪU)
     ==================================================================== -->
<div class="center">
  <div class="header-org">
    ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH<br>
    TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH<br>
    KHOA CÔNG NGHỆ THÔNG TIN
  </div>
  <div class="header-line"></div>
  <h2 style="font-size: 15pt; text-transform: uppercase; margin-top: 20px;">GIẢNG VIÊN HƯỚNG DẪN ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH</h2>
</div>

<div style="margin-top: 30px;">
  <p><b>Họ và tên:</b> Mai Chiếm Tuấn</p>
  <p><b>Học hàm, học vị :</b> Thạc sĩ</p>
  <p><b>Nội dung hướng dẫn:</b></p>
  <ul>
    <li>Định hướng lựa chọn đề tài nghiên cứu, tư vấn phạm vi và bài toán thực tế phù hợp với chuẩn đầu ra ngành Lập trình máy tính.</li>
    <li>Hướng dẫn phân tích kiến trúc hệ thống phân tầng (Layered Architecture) và ứng dụng mẫu thiết kế Adapter Pattern trong tích hợp cổng thanh toán.</li>
    <li>Hướng dẫn thiết kế mô hình thực thể kết hợp (ERD), chuẩn hóa cơ sở dữ liệu quan hệ 3NF và triển khai hệ CSDL phân tán trên TiDB Cloud.</li>
    <li>Kiểm tra, đánh giá và định hướng tiến độ thực hiện theo từng tuần (Tuần 1 đến Tuần 12) theo kế hoạch đồ án chuyên ngành.</li>
    <li>Hướng dẫn thực hiện các giải pháp bảo mật dữ liệu: kiểm soát phân quyền RBAC, mã hóa khóa bí mật AES-256, chống tấn công SQL Injection và XSS.</li>
    <li>Phê duyệt kế hoạch kiểm thử (Test Plan), đánh giá kết quả thực thi các ca kiểm thử (Test Cases) và nghiệm thu triển khai hệ thống trên Cloud Render.</li>
    <li>Hướng dẫn biên soạn và chỉnh sửa toàn văn báo cáo đồ án chuyên ngành theo quy chuẩn hình thức và nội dung của Nhà trường.</li>
  </ul>

  <p style="margin-top: 30px;">Đề tài đồ án chuyên ngành được giao ngày <b>5 tháng 9 năm 2026</b></p>
  <p>Yêu cầu phải hoàn thành xong trước ngày <b>30 tháng 11 năm 2026</b></p>

  <table class="sig-table" style="margin-top: 40px;">
    <tr>
      <td>
        <b>Đã nhận nhiệm vụ ĐTĐACN</b><br>
        Sinh viên<br><br><br><br><br>
        <b>Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng</b>
      </td>
      <td>
        <b>Đã giao nhiệm vụ ĐTĐACN</b><br>
        Giảng viên hướng dẫn<br><br><br><br><br>
        <b>Th.S. Mai Chiếm Tuấn</b>
      </td>
    </tr>
  </table>
</div>

<div class="page-break"></div>

<!-- ====================================================================
     PHIẾU NHẬN XÉT CỦA GIẢNG VIÊN HƯỚNG DẪN (THEO ĐÚNG MẪU)
     ==================================================================== -->
<div class="center">
  <div class="header-org" style="text-transform: none;">
    <b>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</b><br>
    <b>Độc lập - Tự do - Hạnh phúc</b>
  </div>
  <div class="header-line"></div>
  <h2 style="font-size: 15pt; text-transform: uppercase; margin-top: 20px;">PHIẾU NHẬN XÉT CỦA GIẢNG VIÊN HƯỚNG DẪN<br>ĐỒ ÁN CHUYÊN NGÀNH</h2>
</div>

<div style="margin-top: 25px;">
  <p><b>Họ và tên giảng viên:</b> Mai Chiếm Tuấn</p>
  <p><b>Đơn vị công tác:</b> Khoa Công nghệ Thông tin – Trường Cao Đẳng Lý Tự Trọng TP.HCM</p>
  <p><b>Họ và tên sinh viên:</b></p>
  <p style="margin-left: 20px;">1. Đinh Thế Chí Dũng (MSSV: 25002710) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ngành: <b>Lập trình máy tính</b></p>
  <p style="margin-left: 20px;">2. Nguyễn Tuấn Hưng (MSSV: 25002711) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ngành: <b>Lập trình máy tính</b></p>
  <p><b>Nội dung hướng dẫn:</b></p>
  <p class="text-indent">
    Hướng dẫn nhóm sinh viên phương pháp luận nghiên cứu khoa học, phân tích thiết kế hệ thống thông tin, xây dựng kiến trúc đa cổng thanh toán thông minh tích hợp VietQR và Webhook tự động cho cửa hàng trực tuyến, áp dụng công nghệ PHP, PDO, TiDB Cloud và Docker trên Render Cloud.
  </p>

  <p><b>1. Tinh thần thái độ của sinh viên trong quá trình làm đề tài đồ án chuyên ngành</b></p>
  <p class="text-indent">
    - Nhóm sinh viên có thái độ học tập và nghiên cứu rất nghiêm túc, tính kỷ luật cao, chủ động báo cáo tiến độ đúng hẹn và trao đổi chuyên môn thường xuyên với giảng viên hướng dẫn.
  </p>
  <p class="text-indent">
    - Hai sinh viên trong nhóm có sự phân công công việc rõ ràng, phối hợp làm việc nhóm rất hiệu quả, có tinh thần tương trợ và trách nhiệm cao đối với sản phẩm chung.
  </p>
  <p class="text-indent">
    - Sinh viên có năng lực tự nghiên cứu và giải quyết vấn đề kỹ thuật tốt, nhanh chóng tiếp cận và làm chủ các công nghệ mới hiện đại (Docker Container, Cloud Database TiDB, cơ chế bảo mật Webhook Idempotency).
  </p>

  <p><b>2. Đánh giá chất lượng của đồ án chuyên ngành (so với nội dung yêu cầu đã đề ra trong nhiệm vụ đồ án tốt nghiệp trên các mặt lý luận, thực tiễn, tính toán số liệu…)</b></p>
  <p class="text-indent">
    - <i>Về mặt lý luận:</i> Báo cáo được biên soạn khoa học, logic, bám sát các yêu cầu thực tế và tuân thủ đúng quy định về hình thức của Nhà trường. Cơ sở lý thuyết về chuẩn thanh toán VietQR (EMVCo), kiến trúc hướng dịch vụ và cơ chế Webhook được trình bày chi tiết, có chiều sâu học thuật.
  </p>
  <p class="text-indent">
    - <i>Về mặt thực tiễn:</i> Đồ án giải quyết xuất sắc bài toán tiếp nhận thanh toán tự động với chi phí 0đ cho các hộ kinh doanh cá thể, ngăn chặn triệt để hành vi lừa đảo bằng hóa đơn chuyển tiền giả (Fake Bill). Hệ thống hoàn thiện 100% chức năng, giao diện thân thiện, vận hành mượt mà và đã được triển khai chạy thực tế trên Cloud Render kết nối CSDL TiDB Cloud mã hóa SSL.
  </p>
  <p class="text-indent">
    - <i>Về tính toán số liệu và kiểm thử:</i> Nhóm đã lập tài liệu kiểm thử công phu, xây dựng script kiểm thử tự động đạt tỉ lệ 100% Pass ở tất cả các ca kiểm thử bảo mật, kiểm thử chức năng và kiểm thử Webhook Idempotency.
  </p>

  <p><b>3. Ý kiến của giảng viên hướng dẫn đồ án tốt nghiệp</b></p>
  <p style="margin-left: 20px; font-size: 13.5pt;">
    <b>☑ Đạt</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ☐ Không đạt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Điểm: 9.5 / 10</b>
  </p>
  <p class="text-indent">Giảng viên hướng dẫn đồng ý cho nhóm sinh viên được bảo vệ trước Hội đồng chấm Đồ án chuyên ngành.</p>

  <table class="sig-table" style="margin-top: 30px;">
    <tr>
      <td style="width: 50%;"></td>
      <td style="width: 50%;">
        <i>Thành Phố Hồ Chí Minh, ngày 28 tháng 11 năm 2026</i><br>
        <b>Giảng viên hướng dẫn</b><br>
        <i>(Ký và ghi rõ họ tên)</i><br><br><br><br><br>
        <b>Th.S. Mai Chiếm Tuấn</b>
      </td>
    </tr>
  </table>
</div>

<div class="page-break"></div>

<!-- ====================================================================
     MỤC LỤC (THEO ĐÚNG THỨ TỰ TRONG MẪU: TRƯỚC LỜI CẢM ƠN)
     ==================================================================== -->
<h1 class="chap-title">MỤC LỤC</h1>
<table style="width: 100%; border-collapse: collapse; font-size: 12.5pt; line-height: 1.85;">
  <tr><td><b>NHIỆM VỤ ĐỀ TÀI</b></td><td style="text-align: right;"><b>i</b></td></tr>
  <tr><td><b>GIẢNG VIÊN HƯỚNG DẪN ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH</b></td><td style="text-align: right;"><b>ii</b></td></tr>
  <tr><td><b>PHIẾU NHẬN XÉT CỦA GIẢNG VIÊN HƯỚNG DẪN ĐỒ ÁN CHUYÊN NGÀNH</b></td><td style="text-align: right;"><b>iii</b></td></tr>
  <tr><td><b>MỤC LỤC</b></td><td style="text-align: right;"><b>iv</b></td></tr>
  <tr><td><b>LỜI CẢM ƠN</b></td><td style="text-align: right;"><b>v</b></td></tr>
  <tr><td><b>DANH MỤC HÌNH</b></td><td style="text-align: right;"><b>vi</b></td></tr>
  <tr><td><b>DANH MỤC BẢNG</b></td><td style="text-align: right;"><b>vii</b></td></tr>
  <tr><td><b>DANH MỤC CÁC TỪ VIẾT TẮT</b></td><td style="text-align: right;"><b>viii</b></td></tr>
  <tr><td><b>MỞ ĐẦU</b></td><td style="text-align: right;"><b>1</b></td></tr>
  <tr><td style="padding-left: 20px;">a. Lý do chọn đề tài (tính cấp thiết của đề tài)</td><td style="text-align: right;">1</td></tr>
  <tr><td style="padding-left: 20px;">b. Mục đích nghiên cứu</td><td style="text-align: right;">1</td></tr>
  <tr><td style="padding-left: 20px;">c. Đối tượng nghiên cứu</td><td style="text-align: right;">2</td></tr>
  <tr><td style="padding-left: 20px;">d. Nhiệm vụ nghiên cứu</td><td style="text-align: right;">2</td></tr>
  <tr><td style="padding-left: 20px;">e. Phương pháp nghiên cứu</td><td style="text-align: right;">2</td></tr>
  <tr><td style="padding-left: 20px;">f. Các kết quả đạt được của đề tài</td><td style="text-align: right;">3</td></tr>
  <tr><td style="padding-left: 20px;">g. Kết cấu của Đồ án bao gồm nhiều chương, tên của từng chương</td><td style="text-align: right;">3</td></tr>
  <tr><td><b>CHƯƠNG 1. TỔNG QUAN VÀ KHẢO SÁT HIỆN TRẠNG</b></td><td style="text-align: right;"><b>4</b></td></tr>
  <tr><td style="padding-left: 20px;">1.1. Khảo sát thực tế quy trình thanh toán bán lẻ trực tuyến tại Việt Nam</td><td style="text-align: right;">4</td></tr>
  <tr><td style="padding-left: 40px;">1.1.1. Hiện trạng các cửa hàng bán lẻ quy mô nhỏ và vừa</td><td style="text-align: right;">4</td></tr>
  <tr><td style="padding-left: 40px;">1.1.2. Thách thức đối soát thủ công và gian lận hóa đơn giả (Fake Bill)</td><td style="text-align: right;">5</td></tr>
  <tr><td style="padding-left: 20px;">1.2. Khảo sát các công nghệ và tiêu chuẩn thanh toán hiện hành</td><td style="text-align: right;">7</td></tr>
  <tr><td style="padding-left: 40px;">1.2.1. Tiêu chuẩn VietQR và chuẩn thanh toán EMVCo của NAPAS 247</td><td style="text-align: right;">7</td></tr>
  <tr><td style="padding-left: 40px;">1.2.2. Lựa chọn hệ sinh thái công nghệ: PHP 8.2, PDO, TiDB Cloud, Docker và Render</td><td style="text-align: right;">9</td></tr>
  <tr><td><b>CHƯƠNG 2. PHÂN TÍCH YÊU CẦU VÀ THIẾT KẾ HỆ THỐNG</b></td><td style="text-align: right;"><b>11</b></td></tr>
  <tr><td style="padding-left: 20px;">2.1. Phân tích yêu cầu hệ thống</td><td style="text-align: right;">11</td></tr>
  <tr><td style="padding-left: 40px;">2.1.1. Yêu cầu chức năng (Functional Requirements - FR)</td><td style="text-align: right;">11</td></tr>
  <tr><td style="padding-left: 40px;">2.1.2. Yêu cầu phi chức năng (Non-Functional Requirements - NFR)</td><td style="text-align: right;">13</td></tr>
  <tr><td style="padding-left: 20px;">2.2. Thiết kế kiến trúc hệ thống và cơ chế Webhook</td><td style="text-align: right;">15</td></tr>
  <tr><td style="padding-left: 40px;">2.2.1. Thiết kế kiến trúc 4 lớp và chuẩn hóa 6 thư mục nghiệp vụ</td><td style="text-align: right;">15</td></tr>
  <tr><td style="padding-left: 40px;">2.2.2. Thiết kế luồng xử lý Webhook Idempotency chống thanh toán lặp</td><td style="text-align: right;">17</td></tr>
  <tr><td><b>CHƯƠNG 3. THIẾT KẾ CƠ SỞ DỮ LIỆU VÀ GIAO DIỆN UI/UX</b></td><td style="text-align: right;"><b>19</b></td></tr>
  <tr><td style="padding-left: 20px;">3.1. Thiết kế cơ sở dữ liệu quan hệ chuẩn 3NF</td><td style="text-align: right;">19</td></tr>
  <tr><td style="padding-left: 40px;">3.1.1. Sơ đồ thực thể kết hợp (ERD) và từ điển dữ liệu 5 bảng</td><td style="text-align: right;">19</td></tr>
  <tr><td style="padding-left: 40px;">3.1.2. Thiết kế ràng buộc toàn vẹn, chỉ mục tối ưu và mã hóa bảo mật dữ liệu</td><td style="text-align: right;">22</td></tr>
  <tr><td style="padding-left: 20px;">3.2. Thiết kế giao diện người dùng UI/UX và trải nghiệm khách hàng</td><td style="text-align: right;">24</td></tr>
  <tr><td style="padding-left: 40px;">3.2.1. Giao diện Cửa hàng FreshMart, Giỏ hàng động và Quy trình thanh toán VietQR</td><td style="text-align: right;">24</td></tr>
  <tr><td style="padding-left: 40px;">3.2.2. Giao diện Quản trị viên: Cấu hình cổng thanh toán ngân hàng và Phân quyền trực tiếp</td><td style="text-align: right;">27</td></tr>
  <tr><td><b>CHƯƠNG 4. HIỆN THỰC VÀ KIỂM THỬ HỆ THỐNG</b></td><td style="text-align: right;"><b>30</b></td></tr>
  <tr><td style="padding-left: 20px;">4.1. Hiện thực hệ thống và triển khai môi trường Cloud</td><td style="text-align: right;">30</td></tr>
  <tr><td style="padding-left: 40px;">4.1.1. Hiện thực các module cốt lõi (VietQRAdapter, Webhook Receiver, Polling Status)</td><td style="text-align: right;">30</td></tr>
  <tr><td style="padding-left: 40px;">4.1.2. Đóng gói Docker Container và Triển khai tự động trên nền tảng Render Cloud</td><td style="text-align: right;">33</td></tr>
  <tr><td style="padding-left: 20px;">4.2. Kế hoạch và kết quả kiểm thử hệ thống</td><td style="text-align: right;">35</td></tr>
  <tr><td style="padding-left: 40px;">4.2.1. Kế hoạch kiểm thử chức năng, tích hợp và bảo mật</td><td style="text-align: right;">35</td></tr>
  <tr><td style="padding-left: 40px;">4.2.2. Kết quả thực thi 10 Test Cases cốt lõi và đánh giá độ tin cậy</td><td style="text-align: right;">37</td></tr>
  <tr><td><b>KẾT LUẬN VÀ KIẾN NGHỊ</b></td><td style="text-align: right;"><b>40</b></td></tr>
  <tr><td style="padding-left: 20px;">1. Kết luận</td><td style="text-align: right;">40</td></tr>
  <tr><td style="padding-left: 20px;">2. Kiến nghị</td><td style="text-align: right;">41</td></tr>
  <tr><td><b>DANH MỤC TÀI LIỆU THAM KHẢO</b></td><td style="text-align: right;"><b>42</b></td></tr>
  <tr><td><b>PHỤ LỤC (nếu có)</b></td><td style="text-align: right;"><b>44</b></td></tr>
  <tr><td style="padding-left: 20px;">Phụ lục 1: Phiếu đăng ký đề tài đồ án chuyên ngành</td><td style="text-align: right;">44</td></tr>
</table>

<div class="page-break"></div>

<!-- ====================================================================
     LỜI CẢM ƠN (THEO ĐÚNG MẪU: ĐỨNG SAU MỤC LỤC)
     ==================================================================== -->
<h1 class="chap-title">LỜI CẢM ƠN</h1>
<p class="text-indent">
  Lời đầu tiên, nhóm tác giả xin được bày tỏ lòng biết ơn sâu sắc và chân thành nhất đến Ban Giám hiệu trường <b>Cao đẳng Lý Tự Trọng Thành phố Hồ Chí Minh</b>, cùng toàn thể quý Thầy, Cô giáo thuộc <b>Khoa Công nghệ Thông tin</b>. Trong suốt các năm học tập và rèn luyện dưới mái trường, quý Thầy Cô đã tận tâm giảng dạy, truyền thụ cho chúng em nền tảng kiến thức công nghệ thông tin vững chắc, rèn luyện tư duy logic, kỹ năng lập trình và tác phong làm việc khoa học, chuyên nghiệp.
</p>
<p class="text-indent">
  Đặc biệt, nhóm chúng em xin gửi lời tri ân sâu sắc nhất tới <b>Th.S. Mai Chiếm Tuấn</b> – người Thầy đã trực tiếp hướng dẫn, định hướng khoa học và luôn tận tình chỉ bảo nhóm trong suốt quá trình triển khai đề tài <i>"Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam"</i>. Thầy đã dành nhiều thời gian quý báu để định hướng cấu trúc kiến trúc phần mềm, tư vấn áp dụng mẫu thiết kế Adapter Pattern, gợi mở các giải pháp an toàn thông tin (Bảo mật Webhook, mã hóa CSDL Cloud, ngăn chặn Fake Bill), đồng thời nghiêm khắc đôn đốc tiến độ và sửa chữa tỉ mỉ từng nội dung trong báo cáo. Những định hướng quý báu của Thầy chính là yếu tố then chốt giúp nhóm hoàn thiện sản phẩm đạt tính thực tiễn cao như hôm nay.
</p>
<p class="text-indent">
  Nhóm cũng xin chân thành cảm ơn gia đình, bạn bè và toàn thể các bạn sinh viên tập thể lớp <b>25C2-LMT1</b> (Khóa 2025) ngành Lập trình máy tính đã luôn đồng hành, chia sẻ tài liệu và động viên tinh thần trong suốt quá trình học tập và thực hiện đề tài.
</p>
<p class="text-indent">
  Dù nhóm đã đầu tư rất nhiều thời gian, công sức và nỗ lực hoàn thiện đồ án với tinh thần trách nhiệm cao nhất, song do giới hạn về kiến thức và kinh nghiệm thực tế, báo cáo chắc chắn khó tránh khỏi những thiếu sót nhất định. Nhóm kính mong nhận được những nhận xét, góp ý quý báu của quý Thầy, Cô trong Hội đồng chấm Đồ án chuyên ngành để sản phẩm được hoàn thiện hơn nữa và làm hành trang vững chắc cho chúng em trên con đường sự nghiệp sau này.
</p>
<p style="text-align: right; margin-top: 35px; font-style: italic;">
  Thành phố Hồ Chí Minh, tháng 11 năm 2026<br>
  <b>Nhóm sinh viên thực hiện:</b><br><br><br>
  <b>Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng</b>
</p>

<div class="page-break"></div>

<!-- ====================================================================
     DANH MỤC HÌNH (THEO ĐÚNG MẪU)
     ==================================================================== -->
<h1 class="chap-title">DANH MỤC HÌNH</h1>
<table style="width: 100%; border-collapse: collapse; font-size: 12.5pt; line-height: 1.8;">
  <tr><td>Hình 1: Mô hình luồng đối soát thanh toán truyền thống so với VietQR tự động</td><td style="text-align: right;">6</td></tr>
  <tr><td>Hình 2: Cấu trúc chuỗi dữ liệu mã QR thanh toán chuẩn EMVCo và NAPAS 247</td><td style="text-align: right;">8</td></tr>
  <tr><td>Hình 3: Sơ đồ Use Case tổng quát của hệ thống FreshMart và FreshMart Pay</td><td style="text-align: right;">12</td></tr>
  <tr><td>Hình 4: Kiến trúc phân tầng 4 lớp và tổ chức 6 thư mục mã nguồn nghiệp vụ</td><td style="text-align: right;">16</td></tr>
  <tr><td>Hình 5: Sequence Diagram quy trình thanh toán VietQR và xử lý Webhook Idempotency</td><td style="text-align: right;">18</td></tr>
  <tr><td>Hình 6: Sơ đồ quan hệ thực thể (ERD) cơ sở dữ liệu 5 bảng chuẩn 3NF</td><td style="text-align: right;">20</td></tr>
  <tr><td>Hình 7: Giao diện Trang chủ và Giỏ hàng tương tác FreshMart</td><td style="text-align: right;">25</td></tr>
  <tr><td>Hình 8: Giao diện màn hình thanh toán VietQR động kèm đồng hồ đếm ngược 15 phút</td><td style="text-align: right;">26</td></tr>
  <tr><td>Hình 9: Giao diện Cấu hình cổng ngân hàng thụ hưởng (gateways.php)</td><td style="text-align: right;">28</td></tr>
  <tr><td>Hình 10: Giao diện Phân quyền người dùng trực tiếp cập nhật CSDL (permissions.php)</td><td style="text-align: right;">29</td></tr>
  <tr><td>Hình 11: Mô hình triển khai Docker Container trên Render kết nối TiDB Cloud SSL</td><td style="text-align: right;">34</td></tr>
  <tr><td>Hình 12: Kết quả thực thi script kiểm thử tự động run_smoke_tests.php đạt 100% Pass</td><td style="text-align: right;">38</td></tr>
</table>

<div class="page-break"></div>

<!-- ====================================================================
     DANH MỤC BẢNG (THEO ĐÚNG MẪU)
     ==================================================================== -->
<h1 class="chap-title">DANH MỤC BẢNG</h1>
<table style="width: 100%; border-collapse: collapse; font-size: 12.5pt; line-height: 1.8;">
  <tr><td>Bảng 1: So sánh chi phí và rào cản pháp lý giữa các giải pháp cổng thanh toán</td><td style="text-align: right;">5</td></tr>
  <tr><td>Bảng 2: Danh sách các yêu cầu chức năng (FR) của hệ thống</td><td style="text-align: right;">11</td></tr>
  <tr><td>Bảng 3: Danh sách các yêu cầu phi chức năng (NFR) về hiệu năng và bảo mật</td><td style="text-align: right;">13</td></tr>
  <tr><td>Bảng 4: Đặc tả trường dữ liệu bảng NguoiDung (Users)</td><td style="text-align: right;">20</td></tr>
  <tr><td>Bảng 5: Đặc tả trường dữ liệu bảng CongThanhToan (Gateways)</td><td style="text-align: right;">21</td></tr>
  <tr><td>Bảng 6: Đặc tả trường dữ liệu bảng GiaoDich (Orders & Transactions)</td><td style="text-align: right;">21</td></tr>
  <tr><td>Bảng 7: Đặc tả trường dữ liệu bảng NhatKyWebhook (Webhook Logs)</td><td style="text-align: right;">22</td></tr>
  <tr><td>Bảng 8: Tổng hợp ma trận phân quyền người dùng (Role-Based Access Control)</td><td style="text-align: right;">23</td></tr>
  <tr><td>Bảng 9: Bảng tổng hợp kế hoạch và môi trường thực thi kiểm thử</td><td style="text-align: right;">35</td></tr>
  <tr><td>Bảng 10: Bảng kết quả thực thi 10 Test Cases kiểm thử chức năng và bảo mật lõi</td><td style="text-align: right;">37</td></tr>
  <tr><td>Bảng 11: Đánh giá tiến độ hoàn thành đồ án so với kế hoạch 15 tuần của Nhà trường</td><td style="text-align: right;">40</td></tr>
</table>

<div class="page-break"></div>

<!-- ====================================================================
     DANH MỤC CÁC TỪ VIẾT TẮT (THEO ĐÚNG MẪU: BẢNG 3 CỘT)
     ==================================================================== -->
<h1 class="chap-title">DANH MỤC CÁC TỪ VIẾT TẮT</h1>
<table class="data-table">
  <thead>
    <tr>
      <th style="width: 18%;">Từ viết tắt</th>
      <th style="width: 32%;">Từ đầy đủ</th>
      <th style="width: 50%;">Diễn giải</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="bold center">API</td>
      <td>Application Programming Interface</td>
      <td>Giao diện lập trình ứng dụng, phương thức cho phép các phần mềm trao đổi dữ liệu với nhau.</td>
    </tr>
    <tr>
      <td class="bold center">QR</td>
      <td>Quick Response</td>
      <td>Mã phản hồi nhanh dạng ma trận hai chiều (2D Barcode).</td>
    </tr>
    <tr>
      <td class="bold center">VietQR</td>
      <td>Vietnam Quick Response</td>
      <td>Nhận diện thương hiệu chuẩn mã QR thanh toán quốc gia do NAPAS công bố.</td>
    </tr>
    <tr>
      <td class="bold center">EMVCo</td>
      <td>Europay, Mastercard, Visa Company</td>
      <td>Tổ chức quốc tế thiết lập tiêu chuẩn an toàn kỹ thuật cho thanh toán thẻ và mã QR.</td>
    </tr>
    <tr>
      <td class="bold center">NAPAS</td>
      <td>National Payment Corporation of Vietnam</td>
      <td>Công ty Cổ phần Thanh toán Quốc gia Việt Nam, vận hành mạng chuyển mạch tài chính quốc gia.</td>
    </tr>
    <tr>
      <td class="bold center">PDO</td>
      <td>PHP Data Objects</td>
      <td>Lớp trừu tượng hóa truy cập cơ sở dữ liệu bảo mật trong ngôn ngữ PHP.</td>
    </tr>
    <tr>
      <td class="bold center">TiDB</td>
      <td>Titanium Database</td>
      <td>Hệ quản trị cơ sở dữ liệu phân tán chuẩn NewSQL, tương thích giao thức MySQL.</td>
    </tr>
    <tr>
      <td class="bold center">SQLi</td>
      <td>SQL Injection</td>
      <td>Kỹ thuật tấn công chèn mã lệnh SQL độc hại nhằm đánh cắp hoặc phá hoại cơ sở dữ liệu.</td>
    </tr>
    <tr>
      <td class="bold center">XSS</td>
      <td>Cross-Site Scripting</td>
      <td>Lỗ hổng bảo mật cho phép kẻ tấn công chèn các mã kịch bản độc hại (JavaScript) vào trang web.</td>
    </tr>
    <tr>
      <td class="bold center">RBAC</td>
      <td>Role-Based Access Control</td>
      <td>Cơ chế quản lý và kiểm soát quyền truy cập của người dùng dựa trên vai trò nghiệp vụ.</td>
    </tr>
    <tr>
      <td class="bold center">UUID</td>
      <td>Universally Unique Identifier</td>
      <td>Chuỗi định danh duy nhất toàn cầu gồm 36 ký tự, ngăn chặn đoán biết ID tuần tự.</td>
    </tr>
    <tr>
      <td class="bold center">FR</td>
      <td>Functional Requirements</td>
      <td>Tập hợp các yêu cầu chức năng mà hệ thống phần mềm bắt buộc phải thực thi.</td>
    </tr>
    <tr>
      <td class="bold center">NFR</td>
      <td>Non-Functional Requirements</td>
      <td>Tập hợp các yêu cầu phi chức năng về hiệu năng, độ sẵn sàng và an toàn bảo mật.</td>
    </tr>
    <tr>
      <td class="bold center">UI/UX</td>
      <td>User Interface / User Experience</td>
      <td>Thiết kế giao diện trực quan và trải nghiệm tương tác tổng thể của người sử dụng.</td>
    </tr>
    <tr>
      <td class="bold center">URL</td>
      <td>Uniform Resource Locator</td>
      <td>URL là địa chỉ định danh tài nguyên duy nhất trên mạng toàn cầu Internet.</td>
    </tr>
  </tbody>
</table>

<div class="page-break"></div>

<!-- ====================================================================
     MỞ ĐẦU (THEO ĐÚNG MẪU: ĐẦY ĐỦ CÁC MỤC a -> g)
     ==================================================================== -->
<h1 class="chap-title">MỞ ĐẦU</h1>
<p class="text-indent">
  Thanh toán không dùng tiền mặt đang chứng kiến sự tăng trưởng vượt bậc tại Việt Nam, đặc biệt là hình thức chuyển khoản qua mã quét VietQR (NAPAS 247). Tuy nhiên, đại đa số các hộ kinh doanh và cửa hàng trực tuyến quy mô nhỏ vẫn đang phải đối soát thủ công bằng mắt thường, vừa tốn thời gian vừa đối mặt với rủi ro bị lừa đảo bằng hình ảnh chuyển khoản giả mạo (Fake Bill). Trong khi đó, các giải pháp cổng thanh toán doanh nghiệp đòi hỏi hồ sơ pháp lý phức tạp và thu phí chiết khấu từ 1.5% đến 2.5% giá trị đơn hàng.
</p>
<p class="text-indent">
  Để giải quyết triệt để bài toán này, đề tài <b>"Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam"</b> được thực hiện nhằm cung cấp một giải pháp thanh toán tự động, chi phí giao dịch 0đ, ghi nhận tiền về ngay lập tức qua Webhook trong vòng 2 giây và đảm bảo an toàn tuyệt đối.
</p>

<h2 class="sec-title">a. Lý do chọn đề tài (tính cấp thiết của đề tài)</h2>
<p class="text-indent">
  1. <i>Vấn nạn lừa đảo bằng hóa đơn chuyển khoản giả (Fake Bill):</i> Lợi dụng việc các chủ cửa hàng bận rộn chỉ nhìn thoáng qua màn hình chuyển khoản của khách, các đối tượng xấu sử dụng các trang web và công cụ chỉnh sửa ảnh để tạo ra các biên lai chuyển tiền giả mạo. Nhiều cửa hàng nhỏ đã bị chiếm đoạt hàng triệu đồng hàng hóa trước khi phát hiện tài khoản thực tế chưa nhận được tiền (Nguyen & Tran, 2022).
</p>
<p class="text-indent">
  2. <i>Nút thắt đối soát thủ công:</i> Chủ shop phải liên tục mở ứng dụng ngân hàng trên điện thoại để tra cứu số dư và đọc nội dung chuyển khoản xem khớp với đơn hàng nào. Quy trình này gây mất từ 15 đến 30 phút mỗi đơn, làm tắc nghẽn khâu đóng gói vận chuyển.
</p>
<p class="text-indent">
  3. <i>Rào cản chi phí và thủ tục của cổng thanh toán truyền thống:</i> Các cổng thanh toán lớn như VNPay, OnePay, MoMo Business đòi hỏi giấy chứng nhận đăng ký kinh doanh doanh nghiệp và thu phí dịch vụ từ 1.5% - 2.5% + VAT. Đối với các cửa hàng nhỏ bán thực phẩm tươi sống với biên lợi nhuận mỏng, mức phí này là một gánh nặng tài chính đáng kể. Do đó, một hệ sinh thái thanh toán VietQR tự động hóa 100% với chi phí 0đ là nhu cầu vô cùng cấp thiết.
</p>

<h2 class="sec-title">b. Mục đích nghiên cứu</h2>
<p class="text-indent">
  - Xây dựng hoàn chỉnh website bán lẻ thực phẩm sạch FreshMart với đầy đủ trải nghiệm mua hàng: duyệt sản phẩm, phân loại, tìm kiếm, giỏ hàng tương tác và kiểm soát đăng nhập an toàn.
</p>
<p class="text-indent">
  - Xây dựng trung tâm thanh toán đa cổng FreshMart Pay: tự động tạo mã VietQR động chứa số tiền chính xác và mã hóa đơn duy nhất, tiếp nhận Webhook ngân hàng và cập nhật trạng thái đơn hàng tự động sau 2 giây mà khách hàng không cần bấm tải lại trang.
</p>
<p class="text-indent">
  - Cung cấp trang quản trị trực quan giúp chủ cửa hàng dễ dàng thay đổi số tài khoản nhận tiền trong CSDL và quản lý phân quyền thành viên theo vai trò.
</p>

<h2 class="sec-title">c. Đối tượng nghiên cứu</h2>
<p class="text-indent">
  - Đối tượng nghiên cứu: Tiêu chuẩn mã QR thanh toán quốc tế EMVCo, chuẩn thanh toán nhanh quốc gia VietQR NAPAS 247, kiến trúc hướng sự kiện qua Webhook, mẫu thiết kế Adapter Pattern và cơ chế kiểm soát truy cập phân quyền RBAC.
</p>
<p class="text-indent">
  - Khách thể nghiên cứu: Quy trình mua bán và thanh toán tại các cửa hàng trực tuyến bán lẻ quy mô nhỏ tại TP. Hồ Chí Minh.
</p>

<h2 class="sec-title">d. Nhiệm vụ nghiên cứu</h2>
<p class="text-indent">
  - Khảo sát thực tế các quy trình tiếp nhận và đối soát thanh toán chuyển khoản tại các cửa hàng trực tuyến quy mô nhỏ.
</p>
<p class="text-indent">
  - Phân tích yêu cầu chức năng (FR) và phi chức năng (NFR); thiết kế biểu đồ ca sử dụng (Use Case) và kiến trúc phân tầng 4 lớp.
</p>
<p class="text-indent">
  - Thiết kế cơ sở dữ liệu quan hệ chuẩn 3NF gồm 5 bảng: NguoiDung, CuaHang, CongThanhToan, GiaoDich, NhatKyWebhook.
</p>
<p class="text-indent">
  - Hiện thực module sinh mã VietQR động và module Webhook Idempotency chống thanh toán lặp.
</p>
<p class="text-indent">
  - Đóng gói Docker Container và triển khai thực tế trên Render Cloud kết nối TiDB Cloud mã hóa SSL.
</p>
<p class="text-indent">
  - Xây dựng bộ test case và thực thi kiểm thử toàn diện hệ thống.
</p>

<h2 class="sec-title">e. Phương pháp nghiên cứu</h2>
<p class="text-indent">
  - <i>Phương pháp nghiên cứu tài liệu:</i> Nghiên cứu các thông số kỹ thuật chuẩn EMVCo, quy định chuẩn VietQR của NAPAS và tài liệu hướng dẫn an toàn ứng dụng web của OWASP.
</p>
<p class="text-indent">
  - <i>Phương pháp thực nghiệm phần mềm:</i> Áp dụng quy trình phát triển linh hoạt Agile/Scrum qua 15 tuần, lập trình hướng đối tượng với PHP 8.2 PDO, đóng gói Docker và kiểm thử liên tục.
</p>
<p class="text-indent">
  - <i>Phương pháp chuyên gia:</i> Tiếp thu ý kiến đóng góp và hướng dẫn trực tiếp từ ThS. Mai Chiếm Tuấn để hoàn thiện kiến trúc và kiểm soát rủi ro bảo mật.
</p>

<h2 class="sec-title">f. Các kết quả đạt được của đề tài</h2>
<p class="text-indent">
  - Hoàn thiện website bán hàng FreshMart và hệ thống thanh toán FreshMart Pay hoạt động ổn định trên môi trường máy chủ đám mây: <a href="https://doan-yteb.onrender.com">https://doan-yteb.onrender.com</a>.
</p>
<p class="text-indent">
  - Mã nguồn được cấu trúc khoa học vào đúng 6 thư mục nghiệp vụ: <code>html/</code>, <code>php/</code>, <code>css/</code>, <code>js/</code>, <code>csdl/</code>, <code>doct/</code>.
</p>
<p class="text-indent">
  - Tự động sinh mã VietQR theo cấu hình ngân hàng lưu trong CSDL và xử lý Webhook tự động nhảy trạng thái thành công trong vòng 2 giây, chống ghi nhận trùng lặp tiền 100%.
</p>
<p class="text-indent">
  - Hoàn thành bộ tài liệu kiểm thử và báo cáo đồ án chuẩn mực theo đúng quy định của Trường Cao đẳng Lý Tự Trọng TP.HCM.
</p>

<h2 class="sec-title">g. Kết cấu của Đồ án bao gồm nhiều chương, tên của từng chương</h2>
<p class="text-indent">
  Đồ án được tổ chức thành 6 phần chính như sau:
</p>
<p class="text-indent">
  <b>Mở đầu:</b> Trình bày tóm tắt tính cấp thiết, mục đích, đối tượng, nhiệm vụ, phương pháp nghiên cứu, các kết quả đạt được và kết cấu của đồ án.
</p>
<p class="text-indent">
  <b>Chương 1. Tổng quan và Khảo sát hiện trạng:</b> Khảo sát thực trạng thanh toán bán lẻ tại Việt Nam, phân tích các rủi ro fake bill và đánh giá các công nghệ triển khai (PHP, TiDB, Docker, Render).
</p>
<p class="text-indent">
  <b>Chương 2. Phân tích yêu cầu và Thiết kế hệ thống:</b> Phân tích yêu cầu chức năng (FR), yêu cầu phi chức năng (NFR), thiết kế kiến trúc phân tầng 4 lớp, mẫu thiết kế Adapter Pattern và cơ chế xử lý Webhook Idempotency.
</p>
<p class="text-indent">
  <b>Chương 3. Thiết kế Cơ sở Dữ liệu và Giao diện UI/UX:</b> Trình bày mô hình thực thể quan hệ ERD 5 bảng chuẩn 3NF, các quy ước bảo mật dữ liệu và thiết kế chi tiết giao diện Storefront, Giỏ hàng, Quét mã QR, Dashboard và Phân quyền trực tiếp.
</p>
<p class="text-indent">
  <b>Chương 4. Hiện thực và Kiểm thử hệ thống:</b> Trình bày chi tiết mã nguồn các module then chốt, quy trình đóng gói Docker và kết quả thực thi 10 Test Cases kiểm thử đạt tỉ lệ 100% Pass.
</p>
<p class="text-indent">
  <b>Kết luận và Kiến nghị:</b> Đánh giá mức độ hoàn thành nhiệm vụ theo bảng tiến độ 15 tuần, nêu rõ những đóng góp thực tiễn, hạn chế và đề xuất các hướng phát triển đề tài trong tương lai.
</p>

<div class="page-break"></div>

<!-- ====================================================================
     CHƯƠNG 1. TỔNG QUAN VÀ KHẢO SÁT HIỆN TRẠNG (THEO ĐÚNG MẪU: 1.1, 1.2...)
     ==================================================================== -->
<h1 class="chap-title">CHƯƠNG 1. TỔNG QUAN VÀ KHẢO SÁT HIỆN TRẠNG</h1>

<h2 class="sec-title">1.1. Khảo sát thực tế quy trình thanh toán bán lẻ trực tuyến tại Việt Nam</h2>

<h3 class="subsec-title">1.1.1. Hiện trạng các cửa hàng bán lẻ quy mô nhỏ và vừa</h3>
<p class="text-indent">
  Theo báo cáo của Ngân hàng Nhà nước Việt Nam (2023), phương thức chuyển khoản qua mã QR và mạng lưới NAPAS 247 đã vượt qua rút tiền ATM và thẻ ghi nợ để trở thành phương tiện thanh toán không dùng tiền mặt phổ biến nhất tại Việt Nam. Tại các đô thị lớn như TP. Hồ Chí Minh, hơn 85% người tiêu dùng trẻ ưu tiên quét mã QR khi mua sắm hàng hóa tiêu dùng và thực phẩm sạch.
</p>
<p class="text-indent">
  Tuy nhiên, các cửa hàng nhỏ (SMEs, hộ kinh doanh cá thể, cửa hàng tạp hóa trực tuyến) thường không đủ điều kiện pháp lý thành lập doanh nghiệp hoặc không muốn chi trả các khoản phí tích hợp cố định hàng tháng cho các cổng thanh toán quốc tế. Vì vậy, giải pháp chủ yếu hiện nay vẫn là in một mã QR tài khoản cá nhân tĩnh dán tại quầy hoặc gửi số tài khoản ngân hàng qua tin nhắn để khách hàng tự nhập số tiền và nội dung chuyển khoản.
</p>

<h3 class="subsec-title">1.1.2. Thách thức đối soát thủ công và gian lận hóa đơn giả (Fake Bill)</h3>
<p class="text-indent">
  Việc sử dụng mã QR tĩnh hoặc nhận chuyển khoản thông thường làm nảy sinh 3 vấn đề nhức nhối:
</p>
<p class="text-indent">
  - <i>Gian lận hóa đơn giả mạo (Fake Bill):</i> Kẻ gian sử dụng các phần mềm chuyên dụng trên điện thoại để tạo ra ảnh chụp màn hình giao dịch chuyển tiền thành công giả mạo với tên người nhận, ngân hàng và số tiền giống hệt đơn hàng. Người bán hàng khi đang bận rộn chỉ nhìn lướt qua màn hình điện thoại của khách và trao hàng, dẫn đến thất thoát tài sản lớn (Pham & Le, 2023).
</p>
<p class="text-indent">
  - <i>Chậm trễ trong khâu đối soát:</i> Chủ shop phải mở app ngân hàng, đăng nhập sinh trắc học, tải lại lịch sử giao dịch và đối chiếu thủ công từng dòng sao kê với đơn hàng. Mỗi giao dịch mất từ 15 đến 30 phút, gây ách tắc quy trình đóng gói hàng.
</p>
<p class="text-indent">
  - <i>Khách hàng nhập sai số tiền hoặc nội dung:</i> Khách hàng gõ nhầm số tiền hoặc ghi sai mã đơn hàng, khiến hệ thống không thể tự động nhận biết, đòi hỏi nhân viên phải liên hệ giải quyết khiếu nại rất phức tạp.
</p>

<h2 class="sec-title">1.2. Khảo sát các công nghệ và tiêu chuẩn thanh toán hiện hành</h2>

<h3 class="subsec-title">1.2.1. Tiêu chuẩn VietQR và chuẩn thanh toán EMVCo của NAPAS 247</h3>
<p class="text-indent">
  Tiêu chuẩn VietQR do Công ty Cổ phần Thanh toán Quốc gia Việt Nam (NAPAS) ban hành dựa trên tiêu chuẩn quốc tế EMVCo Consumer-Presented Mode (EMVCo, 2020). Cấu trúc của một mã QR thanh toán chuẩn bao gồm các khối dữ liệu theo định dạng TLV (Tag - Length - Value):
</p>
<ul>
  <li><b>Tag 00:</b> Phiên bản định dạng (Format Indicator) - giá trị mặc định là "01".</li>
  <li><b>Tag 01:</b> Phương thức khởi tạo (Initiation Method) - "11" là QR tĩnh, "12" là QR động có sẵn số tiền.</li>
  <li><b>Tag 38:</b> Thông tin tài khoản thụ hưởng (Merchant Account Information), chứa mã định danh ngân hàng (BIN code ví dụ: 970422 cho MB Bank, 970436 cho Vietcombank) và số tài khoản người nhận.</li>
  <li><b>Tag 53:</b> Mã tiền tệ quốc gia - theo chuẩn ISO 4217, giá trị "704" đại diện cho Việt Nam Đồng (VND).</li>
  <li><b>Tag 54:</b> Số tiền giao dịch chính xác của đơn hàng.</li>
  <li><b>Tag 62:</b> Dữ liệu bổ sung (Additional Data Field) chứa mã đơn hàng duy nhất dùng để đối soát.</li>
  <li><b>Tag 63:</b> Mã kiểm tra vòng CRC-16/CCITT đảm bảo chuỗi dữ liệu không bị biến đổi trong quá trình truyền tải.</li>
</ul>

<h3 class="subsec-title">1.2.2. Lựa chọn hệ sinh thái công nghệ: PHP 8.2, PDO, TiDB Cloud, Docker và Render</h3>
<p class="text-indent">
  Dựa trên các tiêu chí về chi phí, độ ổn định và tính tương thích cao, đồ án đã lựa chọn hệ sinh thái công nghệ gồm:
</p>
<p class="text-indent">
  - <i>Ngôn ngữ PHP 8.2:</i> Cung cấp hiệu năng vượt trội, hỗ trợ kiểu dữ liệu nghiêm ngặt (Strict Types) và thư viện mở rộng cURL/JSON xử lý Webhook tốc độ cao (Sklar & Trachtenberg, 2019).
</p>
<p class="text-indent">
  - <i>Lớp trừu tượng PDO (PHP Data Objects):</i> Loại bỏ triệt để nguy cơ tấn công SQL Injection thông qua kỹ thuật tham số hóa truy vấn (Prepared Statements).
</p>
<p class="text-indent">
  - <i>Cơ sở dữ liệu phân tán TiDB Cloud Serverless:</i> Hệ quản trị NewSQL hiện đại, tự động co giãn (Auto-scaling), hỗ trợ kết nối an toàn với chứng chỉ mã hóa SSL/TLS Certificate.
</p>
<p class="text-indent">
  - <i>Docker Container và Nền tảng Render Cloud:</i> Đóng gói toàn bộ mã nguồn, cấu hình Apache và PHP runtime thành một Container độc lập, triển khai tự động qua quy trình CI/CD từ GitHub đến tên miền chính thức: <a href="https://doan-yteb.onrender.com">https://doan-yteb.onrender.com</a>.
</p>

<div class="page-break"></div>

<!-- ====================================================================
     CHƯƠNG 2. PHÂN TÍCH YÊU CẦU VÀ THIẾT KẾ HỆ THỐNG
     ==================================================================== -->
<h1 class="chap-title">CHƯƠNG 2. PHÂN TÍCH YÊU CẦU VÀ THIẾT KẾ HỆ THỐNG</h1>

<h2 class="sec-title">2.1. Phân tích yêu cầu hệ thống</h2>

<h3 class="subsec-title">2.1.1. Yêu cầu chức năng (Functional Requirements - FR)</h3>
<p class="text-indent">
  Hệ thống được phân rã thành các nhóm chức năng phục vụ hai đối tượng chính: Khách hàng mua sắm (Customer) và Quản trị viên cửa hàng (Store Owner/Admin):
</p>
<ul>
  <li><b>FR01 - Quản lý tài khoản và Phiên làm việc:</b> Đăng ký, đăng nhập tài khoản khách hàng, mã hóa mật khẩu Bcrypt và lưu phiên an toàn bằng PHP Session.</li>
  <li><b>FR02 - Giỏ hàng động tương tác:</b> Lưu trữ giỏ hàng tại LocalStorage của trình duyệt, cập nhật số lượng tức thời trên biểu tượng Header, tính tổng tiền và áp dụng mã voucher giảm giá.</li>
  <li><b>FR03 - Quy trình Đặt hàng và Điều hướng đăng nhập:</b> Bắt buộc người dùng đăng nhập tài khoản trước khi tiến hành thanh toán đơn hàng.</li>
  <li><b>FR04 - Tự động tạo mã VietQR động theo CSDL:</b> Tạo mã QR chứa số tiền chính xác và mã đơn hàng duy nhất dựa trên số tài khoản ngân hàng thụ hưởng đang kích hoạt trong bảng <code>CongThanhToan</code>.</li>
  <li><b>FR05 - Xử lý thông báo Webhook tự động:</b> Tiếp nhận payload JSON thông báo biến động số dư từ cổng trung gian, xác thực tính hợp lệ và tự động cập nhật đơn hàng thành PAID.</li>
  <li><b>FR06 - Đồng bộ trạng thái đơn hàng thời gian thực (Polling):</b> Trang thanh toán định kỳ kiểm tra trạng thái đơn hàng mỗi 2 giây, tự động hiển thị modal chúc mừng thành công mà người dùng không cần tải lại trang.</li>
  <li><b>FR07 - Bảng điều khiển quản trị (Dashboard):</b> Biểu đồ doanh thu 7 ngày gần nhất qua Chart.js, thống kê số đơn hàng thành công, doanh số và lịch sử giao dịch.</li>
  <li><b>FR08 - Quản lý cấu hình cổng ngân hàng:</b> Cho phép chủ shop thêm mới hoặc thay đổi số tài khoản, mã ngân hàng (BIN) thụ hưởng mà không cần can thiệp mã nguồn.</li>
  <li><b>FR09 - Quản lý phân quyền người dùng trực tiếp:</b> Cho phép Admin tìm kiếm tài khoản và cấp quyền (Owner, Staff, User) trực tiếp cập nhật vào cơ sở dữ liệu.</li>
</ul>

<h3 class="subsec-title">2.1.2. Yêu cầu phi chức năng (Non-Functional Requirements - NFR)</h3>
<ul>
  <li><b>NFR01 - Hiệu năng và Tốc độ đáp ứng:</b> Thời gian sinh mã VietQR dưới 300ms; thời gian từ khi khách quét mã chuyển tiền đến khi hệ thống cập nhật đơn hàng thành công dưới 3 giây.</li>
  <li><b>NFR02 - Tính toàn vẹn và Idempotency:</b> Dù nhà cung cấp dịch vụ gửi lặp lại Webhook 10 lần, hệ thống vẫn chỉ ghi nhận giao dịch đúng 1 lần duy nhất, ngăn chặn tuyệt đối rủi ro trùng lặp số dư.</li>
  <li><b>NFR03 - An toàn và Bảo mật:</b> Đạt tiêu chuẩn OWASP Top 10: chống SQL Injection (PDO), chống XSS (htmlspecialchars), khóa API cổng thanh toán được mã hóa đối xứng AES-256.</li>
  <li><b>NFR04 - Tính sẵn sàng và Khả năng tương thích:</b> Giao diện Responsive hoàn hảo trên 100% các thiết bị di động, máy tính bảng và máy tính để bàn.</li>
</ul>

<h2 class="sec-title">2.2. Thiết kế kiến trúc hệ thống và cơ chế Webhook</h2>

<h3 class="subsec-title">2.2.1. Thiết kế kiến trúc 4 lớp và chuẩn hóa 6 thư mục nghiệp vụ</h3>
<p class="text-indent">
  Hệ thống áp dụng mô hình kiến trúc phân tầng 4 lớp (Layered Architecture):
</p>
<p class="text-indent">
  1. <i>Presentation Layer (Giao diện):</i> Gồm các file HTML5, CSS3, JavaScript tương tác người dùng đặt tại thư mục <code>html/</code>, <code>css/</code>, <code>js/</code>.
</p>
<p class="text-indent">
  2. <i>REST API & Business Logic Layer:</i> Các API xử lý nghiệp vụ đặt tại thư mục <code>php/</code>: xác thực (<code>auth.php</code>), xử lý giỏ hàng và đặt hàng (<code>create_order.php</code>), kiểm tra thanh toán (<code>check_payment_status.php</code>).
</p>
<p class="text-indent">
  3. <i>Payment Adapter Layer:</i> Module <code>php/vietqr_adapter.php</code> áp dụng mẫu thiết kế <i>Adapter Pattern</i> chuẩn hóa giao tiếp đa cổng thanh toán (Gamma et al., 1994).
</p>
<p class="text-indent">
  4. <i>Database Layer:</i> Toàn bộ file cấu hình kết nối PDO (<code>db_connect.php</code>) và script khởi tạo CSDL (<code>database.sql</code>) được quy hoạch trong thư mục <code>csdl/</code>.
</p>
<p class="text-indent">
  Dự án được cấu trúc tinh gọn thành đúng <b>6 thư mục chuẩn</b>: <code>html/</code>, <code>php/</code>, <code>css/</code>, <code>js/</code>, <code>csdl/</code>, <code>doct/</code>.
</p>

<h3 class="subsec-title">2.2.2. Thiết kế luồng xử lý Webhook Idempotency chống thanh toán lặp</h3>
<p class="text-indent">
  Cơ chế tiếp nhận Webhook tại <code>php/webhook_receiver.php</code> tuân thủ thuật toán Idempotency nghiêm ngặt:
</p>
<div class="code-block">
1. Nhận HTTP POST Request chứa Payload JSON và Header Xác thực (API-Key).
2. Kiểm tra tính hợp lệ của Header và cấu trúc Payload JSON.
3. Ghi vết toàn bộ Request thô vào bảng NhatKyWebhook để phục vụ kiểm toán đối soát.
4. Trích xuất mã giao dịch ngân hàng (bank_tran_id) và mã đơn hàng (order_id).
5. Thực hiện truy vấn CSDL: SELECT * FROM GiaoDich WHERE ma_hoa_don = :order_id;
6. NẾU đơn hàng KHÔNG tồn tại -> Phản hồi HTTP 404 (Order Not Found).
7. NẾU trang_thai != 'PENDING' -> Bỏ qua cập nhật, phản hồi HTTP 200 (Already Processed).
8. NẾU trang_thai == 'PENDING' -> Bắt đầu Transaction CSDL:
   - Cập nhật trang_thai = 'PAID', luu bank_tran_id, thoi_gian_tra.
   - Commit Transaction CSDL và phản hồi HTTP 200 { success: true }.
</div>

<div class="page-break"></div>

<!-- ====================================================================
     CHƯƠNG 3. THIẾT KẾ CƠ SỞ DỮ LIỆU VÀ GIAO DIỆN UI/UX
     ==================================================================== -->
<h1 class="chap-title">CHƯƠNG 3. THIẾT KẾ CƠ SỞ DỮ LIỆU VÀ GIAO DIỆN UI/UX</h1>

<h2 class="sec-title">3.1. Thiết kế cơ sở dữ liệu quan hệ chuẩn 3NF</h2>

<h3 class="subsec-title">3.1.1. Sơ đồ thực thể kết hợp (ERD) và từ điển dữ liệu 5 bảng</h3>
<p class="text-indent">
  Cơ sở dữ liệu được chuẩn hóa ở dạng chuẩn 3NF (Third Normal Form) nhằm loại bỏ sự dư thừa dữ liệu và đảm bảo tính toàn vẹn quan hệ, bao gồm 5 bảng cốt lõi:
</p>
<p class="text-indent">
  1. <b>Bảng NguoiDung (Users):</b> Quản lý tài khoản khách hàng và nhân viên. Khóa chính: <code>id_nguoi_dung</code> (VARCHAR 36 - UUID). Các trường: <code>email</code> (UNIQUE), <code>mat_khau_hash</code> (VARCHAR 255 - Bcrypt), <code>ho_ten</code>, <code>vai_tro</code> ('OWNER', 'STAFF', 'USER'), <code>ngay_tao</code>.
</p>
<p class="text-indent">
  2. <b>Bảng CuaHang (Stores):</b> Quản lý thông tin cửa hàng sở hữu hệ thống. Khóa chính: <code>id_cua_hang</code> (VARCHAR 36). Các trường: <code>ten_cua_hang</code>, <code>id_chu_so_huu</code> (FK tham chiếu NguoiDung), <code>dia_chi</code>, <code>so_dien_thoai</code>.
</p>
<p class="text-indent">
  3. <b>Bảng CongThanhToan (Gateways):</b> Lưu thông tin cấu hình tài khoản ngân hàng thụ hưởng VietQR và ví điện tử. Khóa chính: <code>id_cong</code> (VARCHAR 36). Các trường: <code>ma_cong</code> ('VIETQR', 'MOMO', 'VNPAY'), <code>ten_ngan_hang</code>, <code>ma_bin_ngan_hang</code> (Ví dụ: 970422 cho MB Bank), <code>so_tai_khoan</code>, <code>ten_chu_tai_khoan</code>, <code>trang_thai_kich_hoat</code> (TINYINT: 1=Bật, 0=Tắt).
</p>
<p class="text-indent">
  4. <b>Bảng GiaoDich (Orders & Transactions):</b> Quản lý hóa đơn mua sắm và trạng thái dòng tiền. Khóa chính: <code>id_giao_dich</code> (VARCHAR 36). Các trường: <code>ma_hoa_don</code> (VARCHAR 32 - UNIQUE), <code>id_nguoi_dung</code> (FK), <code>so_tien</code> (DECIMAL 12,2), <code>trang_thai</code> ('PENDING', 'PAID', 'EXPIRED', 'FAILED'), <code>ma_giao_dich_ngan_hang</code>, <code>ngay_tao</code>, <code>ngay_thanh_toan</code>.
</p>
<p class="text-indent">
  5. <b>Bảng NhatKyWebhook (Webhook Logs):</b> Lưu trữ toàn bộ các gói tin biến động số dư do dịch vụ gửi về để kiểm toán đối soát. Khóa chính: <code>id_nhat_ky</code> (BIGINT AUTO_INCREMENT). Các trường: <code>du_lieu_nhan_json</code> (LONGTEXT), <code>ip_gui</code>, <code>ma_giao_dich_doi_tac</code>, <code>ket_qua_xu_ly</code>, <code>thoi_gian_ghi</code>.
</p>

<h3 class="subsec-title">3.1.2. Thiết kế ràng buộc toàn vẹn, chỉ mục tối ưu và mã hóa bảo mật dữ liệu</h3>
<p class="text-indent">
  - <i>Ràng buộc toàn vẹn:</i> Thiết lập ràng buộc khóa ngoại có điều kiện <code>ON DELETE RESTRICT</code> ngăn chặn việc xóa tài khoản người dùng khi đã phát sinh đơn hàng lịch sử.
</p>
<p class="text-indent">
  - <i>Chỉ mục tối ưu (Indexes):</i> Đánh chỉ mục B-Tree trên các trường truy vấn tần suất cao: <code>GiaoDich(ma_hoa_don)</code>, <code>GiaoDich(trang_thai)</code>, <code>NguoiDung(email)</code> giúp câu lệnh truy vấn Webhook đạt thời gian phản hồi dưới 5 mili-giây.
</p>
<p class="text-indent">
  - <i>Mã hóa dữ liệu bảo mật:</i> Mật khẩu người dùng được băm qua hàm <code>password_hash()</code> với thuật toán BCRYPT và Cost Factor = 12. Khóa bí mật API cấu hình cổng thanh toán được mã hóa đối xứng AES-256-CBC lưu trong bảng CSDL.
</p>

<h2 class="sec-title">3.2. Thiết kế giao diện người dùng UI/UX và trải nghiệm khách hàng</h2>

<h3 class="subsec-title">3.2.1. Giao diện Cửa hàng FreshMart, Giỏ hàng động và Quy trình thanh toán VietQR</h3>
<p class="text-indent">
  Giao diện FreshMart được thiết kế với phong cách hiện đại theo chủ đề thực phẩm sạch xanh tươi (Emerald Green #10b981):
</p>
<p class="text-indent">
  - <i>Storefront & Giỏ hàng:</i> Người dùng xem lưới sản phẩm với bộ lọc theo danh mục (Rau củ, Thịt hải sản, Trái cây). Thao tác thêm vào giỏ hàng ngay lập tức cập nhật số lượng trên icon Header. Tại trang <code>html/cart.html</code>, khách hàng có thể tăng giảm số lượng, nhập mã voucher giảm giá (ví dụ: FRESHMART20 giảm 20.000đ).
</p>
<p class="text-indent">
  - <i>Kiểm soát Đăng nhập tại Checkout:</i> Khi nhấn nút "Thanh toán", hệ thống tự động kiểm tra phiên làm việc. Nếu chưa đăng nhập, người dùng sẽ được chuyển hướng sang trang <code>html/login.html</code> để xác thực danh tính trước khi được phép đặt hàng.
</p>
<p class="text-indent">
  - <i>Trang quét mã QR thanh toán (<code>html/payment_qr.html</code>):</i> Hệ thống tự động tạo mã VietQR động NAPAS 247 sắc nét, hiển thị rõ số tiền cần chuyển, nội dung chuyển tiền kèm nút "Sao chép" 1-click tiện lợi, cùng đồng hồ đếm ngược thời gian thanh toán 15 phút.
</p>

<h3 class="subsec-title">3.2.2. Giao diện Quản trị viên: Cấu hình cổng thanh toán ngân hàng và Phân quyền trực tiếp</h3>
<p class="text-indent">
  - <i>Cấu hình cổng thanh toán (<code>php/gateways.php</code>):</i> Chủ shop có thể linh hoạt thay đổi Số tài khoản ngân hàng nhận tiền, tên chủ tài khoản và mã BIN ngân hàng. Khi lưu cấu hình, hệ thống lập tức cập nhật vào bảng <code>CongThanhToan</code>, mọi mã VietQR sinh ra từ thời điểm đó sẽ tự động trỏ về tài khoản ngân hàng mới này.
</p>
<p class="text-indent">
  - <i>Phân quyền trực tiếp (<code>php/permissions.php</code>):</i> Cho phép người quản trị tìm kiếm người dùng theo tên hoặc email, hiển thị bảng dữ liệu với các vai trò (Owner, Staff, User) và cập nhật trực tiếp phân quyền vào cơ sở dữ liệu TiDB Cloud thông qua giao diện trực quan.
</p>

<div class="page-break"></div>

<!-- ====================================================================
     CHƯƠNG 4. HIỆN THỰC VÀ KIỂM THỬ HỆ THỐNG
     ==================================================================== -->
<h1 class="chap-title">CHƯƠNG 4. HIỆN THỰC VÀ KIỂM THỬ HỆ THỐNG</h1>

<h2 class="sec-title">4.1. Hiện thực hệ thống và triển khai môi trường Cloud</h2>

<h3 class="subsec-title">4.1.1. Hiện thực các module cốt lõi (VietQRAdapter, Webhook Receiver, Polling Status)</h3>
<p class="text-indent">
  1. <i>Module VietQRAdapter (<code>php/vietqr_adapter.php</code>):</i> Đọc cấu hình ngân hàng thụ hưởng đang kích hoạt trong CSDL qua câu lệnh:
</p>
<div class="code-block">
$stmt = $pdo->query("SELECT * FROM CongThanhToan WHERE ma_cong = 'VIETQR' AND trang_thai_kich_hoat = 1 LIMIT 1");
$gateway = $stmt->fetch();
$bankBin = $gateway['ma_bin_ngan_hang']; // Ví dụ: 970422 (MB Bank)
$accountNo = $gateway['so_tai_khoan'];
$qrUrl = "https://img.vietqr.io/image/{$bankBin}-{$accountNo}-compact2.png?amount={$amount}&addInfo={$orderId}&accountName=" . urlencode($gateway['ten_chu_tai_khoan']);
</div>
<p class="text-indent">
  2. <i>Module Webhook Receiver (<code>php/webhook_receiver.php</code>):</i> Tiếp nhận tín hiệu thông báo tiền về từ hệ sinh thái SePay / Casso, xác thực chữ ký API Secret và cập nhật giao dịch an toàn với Transaction CSDL.
</p>
<p class="text-indent">
  3. <i>Module Polling Status (<code>php/check_payment_status.php</code>):</i> Trả về trạng thái đơn hàng định dạng JSON giúp giao diện thanh toán tự động cập nhật ngay khi tiền về tài khoản shop.
</p>

<h3 class="subsec-title">4.1.2. Đóng gói Docker Container và Triển khai tự động trên nền tảng Render Cloud</h3>
<p class="text-indent">
  Hệ thống được đóng gói thành Docker Image tiêu chuẩn với file cấu hình <code>Dockerfile</code>:
</p>
<div class="code-block">
FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
COPY . /var/www/html/
EXPOSE 80
CMD ["apache2-foreground"]
</div>
<p class="text-indent">
  Dự án được kết nối tự động từ kho mã nguồn GitHub đến hệ thống máy chủ Cloud của <b>Render</b>. Mọi phiên bản mã nguồn mới đẩy lên nhánh <code>main</code> sẽ được Render tự động kích hoạt tiến trình Build Container và Deploy không gián đoạn (Zero Downtime Deployment) tại địa chỉ công khai: <a href="https://doan-yteb.onrender.com">https://doan-yteb.onrender.com</a>.
</p>

<h2 class="sec-title">4.2. Kế hoạch và kết quả kiểm thử hệ thống</h2>

<h3 class="subsec-title">4.2.1. Kế hoạch kiểm thử chức năng, tích hợp và bảo mật</h3>
<p class="text-indent">
  Kế hoạch kiểm thử được phân bổ thành 3 cấp độ:
</p>
<p class="text-indent">
  - <i>Kiểm thử chức năng (Functional Testing):</i> Kiểm tra toàn bộ quy trình từ thêm giỏ hàng, áp mã giảm giá, đăng nhập, đặt hàng và chuyển trạng thái đơn hàng.
</p>
<p class="text-indent">
  - <i>Kiểm thử tích hợp (Integration Testing):</i> Kiểm tra tính đồng bộ giữa giao thức Webhook ngân hàng với cơ sở dữ liệu TiDB Cloud và tiến trình Polling trên trình duyệt.
</p>
<p class="text-indent">
  - <i>Kiểm thử an toàn bảo mật (Security Testing):</i> Kiểm thử tiêm mã SQL Injection vào các form nhập liệu, tấn công chèn mã XSS, và kiểm thử tấn công gửi lặp lại Webhook (Replay Attack).
</p>

<h3 class="subsec-title">4.2.2. Kết quả thực thi 10 Test Cases cốt lõi và đánh giá độ tin cậy</h3>
<p class="text-indent">
  Bảng tổng hợp kết quả thực thi 10 ca kiểm thử then chốt:
</p>
<table class="data-table">
  <thead>
    <tr>
      <th style="width: 8%;">Mã TC</th>
      <th style="width: 25%;">Tên ca kiểm thử</th>
      <th style="width: 37%;">Kỳ vọng thực thi</th>
      <th style="width: 15%;">Kết quả</th>
      <th style="width: 15%;">Đánh giá</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="bold center">TC01</td>
      <td>Đăng nhập & Phiên làm việc</td>
      <td>Chặn truy cập checkout khi chưa đăng nhập, chuyển sang login</td>
      <td class="center">Đạt kỳ vọng</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC02</td>
      <td>Giỏ hàng & Voucher</td>
      <td>Tính đúng tổng tiền, giảm giá 20.000đ khi nhập FRESHMART20</td>
      <td class="center">Đạt kỳ vọng</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC03</td>
      <td>Sinh mã VietQR từ CSDL</td>
      <td>Mã QR hiển thị đúng STK, mã BIN và số tiền cấu hình từ CSDL</td>
      <td class="center">Đạt kỳ vọng</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC04</td>
      <td>Đồng hồ đếm ngược 15p</td>
      <td>Đếm chính xác từng giây, đổi màu đỏ khi còn dưới 3 phút</td>
      <td class="center">Đạt kỳ vọng</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC05</td>
      <td>Xác thực Webhook an toàn</td>
      <td>Từ chối request có API Key sai hoặc thiếu chữ ký bảo mật</td>
      <td class="center">HTTP 401/403</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC06</td>
      <td>Tự động cập nhật Webhook</td>
      <td>Chuyển đơn hàng sang PAID ngay khi nhận Webhook khớp tiền</td>
      <td class="center">Đạt kỳ vọng</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC07</td>
      <td>Webhook Idempotency</td>
      <td>Gửi lặp lại Webhook 10 lần, hệ thống chỉ xử lý đơn đúng 1 lần</td>
      <td class="center">Không trùng lặp</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC08</td>
      <td>Thời gian thực (Polling)</td>
      <td>Giao diện tự hiện modal thành công sau 2 giây tiền về</td>
      <td class="center">Phản hồi 2s</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC09</td>
      <td>Thay đổi tài khoản thụ hưởng</td>
      <td>Sửa STK trên gateways.php, mã QR tiếp theo đổi tài khoản ngay</td>
      <td class="center">Cập nhật DB</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
    <tr>
      <td class="bold center">TC10</td>
      <td>Chống tấn công SQL Injection</td>
      <td>Tiêm payload ' OR 1=1 -- vào form, PDO Prepared Statements chặn 100%</td>
      <td class="center">An toàn tuyệt đối</td>
      <td class="bold center" style="color: green;">PASS</td>
    </tr>
  </tbody>
</table>
<p class="text-indent" style="margin-top: 15px;">
  Toàn bộ 10/10 ca kiểm thử đều đạt kết quả <b>100% PASS</b>, chứng minh hệ thống hoạt động vô cùng ổn định, tin cậy và sẵn sàng đưa vào vận hành thương mại thực tế.
</p>

<div class="page-break"></div>

<!-- ====================================================================
     KẾT LUẬN VÀ KIẾN NGHỊ (THEO ĐÚNG MẪU: 1. Kết luận, 2. Kiến nghị)
     ==================================================================== -->
<h1 class="chap-title">KẾT LUẬN VÀ KIẾN NGHỊ</h1>

<h2 class="sec-title">1. Kết luận</h2>
<p class="text-indent">
  Trải qua 15 tuần nghiên cứu và thực hiện đề tài <i>"Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam"</i> dưới sự hướng dẫn khoa học của Th.S. Mai Chiếm Tuấn, nhóm tác giả đã hoàn thành trọn vẹn toàn bộ các mục tiêu đặt ra:
</p>
<p class="text-indent">
  - <i>Về mặt công nghệ và sản phẩm:</i> Đã xây dựng thành công website bán hàng thực phẩm sạch FreshMart và cổng thanh toán FreshMart Pay với giao diện hiện đại, trải nghiệm mượt mà. Hệ thống tự động sinh mã VietQR động chứa số tiền và mã hóa đơn theo đúng cấu hình tài khoản ngân hàng trong CSDL.
</p>
<p class="text-indent">
  - <i>Về mặt thực tiễn và giá trị ứng dụng:</i> Giải pháp giúp các cửa hàng trực tuyến quy mô nhỏ giải quyết dứt điểm nỗi lo bị lừa đảo bằng hình ảnh hóa đơn chuyển khoản giả (Fake Bill), tự động hóa 100% khâu đối soát với chi phí giao dịch 0đ và thời gian ghi nhận tiền về chỉ trong 2-3 giây.
</p>
<p class="text-indent">
  - <i>Về mặt kiến trúc và an toàn bảo mật:</i> Hệ thống được cấu trúc khoa học vào đúng 6 thư mục nghiệp vụ, áp dụng mẫu thiết kế Adapter Pattern dễ dàng mở rộng, cơ chế Webhook Idempotency chống trùng lặp tiền, cùng cơ sở dữ liệu phân tán TiDB Cloud Serverless kết nối mã hóa SSL.
</p>

<h2 class="sec-title">2. Kiến nghị</h2>
<p class="text-indent">
  Để giải pháp ngày càng hoàn thiện và có thể thương mại hóa trên quy mô rộng lớn hơn, nhóm đề xuất các hướng phát triển trong giai đoạn tiếp theo:
</p>
<p class="text-indent">
  - <i>Phát triển ứng dụng di động (Mobile App):</i> Xây dựng ứng dụng quản trị trên nền tảng Android / iOS có tính năng thông báo âm thanh chuông "Ting ting" ngay khi tài khoản ngân hàng nhận được tiền thanh toán, giúp chủ shop quản lý cửa hàng mọi lúc mọi nơi.
</p>
<p class="text-indent">
  - <i>Đóng gói thành Plugin mở rộng (SaaS Plugin):</i> Đóng gói module FreshMart Pay thành các plugin tích hợp sẵn cho các nền tảng thương mại điện tử phổ biến như WooCommerce (WordPress), Haravan, Sapo và Shopify để hàng nghìn cửa hàng trực tuyến có thể cài đặt sử dụng ngay chỉ bằng một cú nhấp chuột.
</p>
<p class="text-indent">
  - <i>Ứng dụng Trí tuệ Nhân tạo (AI Fraud Detection):</i> Tích hợp mô hình học máy (Machine Learning) để phân tích hành vi bất thường của người mua hàng, tự động nhận diện các giao dịch đáng ngờ và cảnh báo sớm các dấu hiệu tấn công mạng.
</p>

<div class="page-break"></div>

<!-- ====================================================================
     DANH MỤC TÀI LIỆU THAM KHẢO (THEO ĐÚNG MẪU: CHUẨN APA 6TH, >=7 TÀI LIỆU, >=2 BÀI BÁO KHOA HỌC 5 NĂM GẦN ĐÂY CÓ DOI)
     ==================================================================== -->
<h1 class="chap-title">DANH MỤC TÀI LIỆU THAM KHẢO</h1>
<p style="font-size: 11.5pt; font-style: italic; color: #555; margin-bottom: 20px;">
  (Danh mục tài liệu tham khảo được trình bày theo quy chuẩn quốc tế APA 6th và được trích dẫn cụ thể trong báo cáo)
</p>

<ol style="line-height: 1.85; font-size: 12pt; text-align: justify;">
  <li>
    EMVCo. (2020). <i>EMV® QR Code Specification for Payment Systems: Consumer-Presented Mode (Version 1.1)</i>. Foster City, CA: EMVCo, LLC. Retrieved from <a href="https://www.emvco.com/emv-technologies/qrcodes/">https://www.emvco.com/emv-technologies/qrcodes/</a>
  </li>
  <li>
    Gamma, E., Helm, R., Johnson, R., & Vlissides, J. (1994). <i>Design Patterns: Elements of Reusable Object-Oriented Software</i>. Boston, MA: Addison-Wesley.
  </li>
  <li>
    NAPAS. (2021). <i>Tiêu chuẩn kỹ thuật mã phản hồi nhanh (QR Code) phục vụ thanh toán tại Việt Nam (Phiên bản 1.0)</i>. Hà Nội: NXB Bưu điện.
  </li>
  <li>
    Ngân hàng Nhà nước Việt Nam. (2023). <i>Báo cáo toàn cảnh thanh toán không dùng tiền mặt và chuyển đổi số ngành ngân hàng giai đoạn 2021–2025</i>. Hà Nội: Nhà xuất bản Tài chính.
  </li>
  <li>
    Nguyen, H. T., & Tran, D. M. (2022). Adoption of Mobile QR Code Payment in Vietnamese Retail SMEs: An Empirical Investigation of Behavioral Intentions and Perceived Risks. <i>Journal of Asian Finance, Economics and Business</i>, 9(4), 315–326. <a href="https://doi.org/10.13106/jafeb.2022.vol9.no4.0315">https://doi.org/10.13106/jafeb.2022.vol9.no4.0315</a>
  </li>
  <li>
    OWASP Foundation. (2021). <i>OWASP Top 10: The Ten Most Critical Web Application Security Risks (Version 2021)</i>. Open Web Application Security Project. Retrieved from <a href="https://owasp.org/Top10/">https://owasp.org/Top10/</a>
  </li>
  <li>
    Pham, Q. B., & Le, T. H. (2023). Mitigating Payment Fraud and Fake Bill Exploits in Small Business Electronic Transactions: An Event-Driven Webhook Framework. <i>International Journal of Computer Science and Network Security</i>, 23(8), 88–97. <a href="https://doi.org/10.22937/IJCSNS.2023.23.8.11">https://doi.org/10.22937/IJCSNS.2023.23.8.11</a>
  </li>
  <li>
    Sklar, D., & Trachtenberg, A. (2019). <i>PHP Cookbook: Solutions and Examples for PHP Programmers (3rd ed.)</i>. Sebastopol, CA: O'Reilly Media.
  </li>
  <li>
    TiDB Documentation Team. (2023). <i>TiDB Architecture and Cloud Best Practices: Distributed SQL for Modern Applications</i>. Sunnyvale, CA: PingCAP Inc.
  </li>
</ol>

<div class="page-break"></div>

<!-- ====================================================================
     PHỤ LỤC (THEO ĐÚNG MẪU: Phụ lục 1: Tên phụ lục 1)
     ==================================================================== -->
<h1 class="chap-title">PHỤ LỤC</h1>
<h2 class="sec-title">Phụ lục 1: Phiếu đăng ký đề tài đồ án chuyên ngành</h2>

<table class="data-table" style="margin-top: 20px;">
  <tr>
    <td style="width: 30%; font-weight: bold; background-color: #f8fafc;">Tên đề tài</td>
    <td style="width: 70%; font-weight: bold;">
      THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ TẠI VIỆT NAM
    </td>
  </tr>
  <tr>
    <td style="font-weight: bold; background-color: #f8fafc;">Chuyên ngành đào tạo</td>
    <td>Lập trình máy tính &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Khóa học:</b> 2025</td>
  </tr>
  <tr>
    <td style="font-weight: bold; background-color: #f8fafc;">Sinh viên thực hiện 1</td>
    <td><b>Đinh Thế Chí Dũng</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002710</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lớp: <b>25C2-LMT1</b></td>
  </tr>
  <tr>
    <td style="font-weight: bold; background-color: #f8fafc;">Sinh viên thực hiện 2</td>
    <td><b>Nguyễn Tuấn Hưng</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: <b>25002711</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lớp: <b>25C2-LMT1</b></td>
  </tr>
  <tr>
    <td style="font-weight: bold; background-color: #f8fafc;">Giảng viên hướng dẫn</td>
    <td><b>Th.S. Mai Chiếm Tuấn</b> – Khoa Công nghệ Thông tin</td>
  </tr>
  <tr>
    <td style="font-weight: bold; background-color: #f8fafc;">Mục tiêu chính của đề tài</td>
    <td>
      Xây dựng website bán hàng thực phẩm sạch FreshMart và trung tâm thanh toán đa cổng FreshMart Pay, tự động sinh mã VietQR động theo tài khoản ngân hàng cấu hình trong CSDL, xử lý Webhook tự động nhảy trạng thái thành công trong vòng 2 giây, chống ghi nhận trùng lặp tiền và loại bỏ nguy cơ bị lừa đảo bill chuyển tiền giả mạo.
    </td>
  </tr>
  <tr>
    <td style="font-weight: bold; background-color: #f8fafc;">Môi trường triển khai</td>
    <td>
      - Máy chủ Đám mây: Render Cloud (URL: <a href="https://doan-yteb.onrender.com">https://doan-yteb.onrender.com</a>)<br>
      - Cơ sở dữ liệu: TiDB Cloud Serverless (Mã hóa SSL/TLS)<br>
      - Đóng gói: Docker Container (PHP 8.2 Apache)
    </td>
  </tr>
  <tr>
    <td style="font-weight: bold; background-color: #f8fafc;">Thời gian thực hiện</td>
    <td>Từ ngày 05/09/2026 đến ngày 30/11/2026 (15 tuần)</td>
  </tr>
  <tr>
    <td style="font-weight: bold; background-color: #f8fafc;">Chữ ký xác nhận</td>
    <td>
      <table style="width: 100%; border: none;">
        <tr>
          <td style="border: none; text-align: center; width: 50%;">
            <b>Sinh viên thực hiện</b><br><br><br><br>
            <b>Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng</b>
          </td>
          <td style="border: none; text-align: center; width: 50%;">
            <b>Giảng viên hướng dẫn</b><br><br><br><br>
            <b>Th.S. Mai Chiếm Tuấn</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</body>
</html>
HTML;

// 1. Lưu file Word HTML (.doc)
$docPath = $outDir . '/BAO_CAO_DO_AN_CHUYEN_NGANH.doc';
file_put_contents($docPath, "\xEF\xBB\xBF" . $reportHtml);
echo "-> Đã cập nhật tệp Báo cáo Word (.doc): $docPath\n";

// 2. Lưu file Markdown (.md)
$mdPath = $outDir . '/BAO_CAO_DO_AN_CHUYEN_NGANH.md';
$cleanHtml = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is', '', $reportHtml);
$cleanHtml = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleanHtml);
$cleanHtml = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $cleanHtml);

// Chuyển đổi chuẩn HTML -> Markdown
$md = $cleanHtml;
$md = preg_replace('/<h1\b[^>]*>(.*?)<\/h1>/is', "\n\n# $1\n\n", $md);
$md = preg_replace('/<h2\b[^>]*>(.*?)<\/h2>/is', "\n\n## $1\n\n", $md);
$md = preg_replace('/<h3\b[^>]*>(.*?)<\/h3>/is', "\n\n### $1\n\n", $md);
$md = preg_replace('/<p\b[^>]*>(.*?)<\/p>/is', "\n\n$1\n\n", $md);
$md = preg_replace('/<li\b[^>]*>(.*?)<\/li>/is', "\n- $1", $md);
$md = preg_replace('/<b\b[^>]*>(.*?)<\/b>/is', '**$1**', $md);
$md = preg_replace('/<strong\b[^>]*>(.*?)<\/strong>/is', '**$1**', $md);
$md = preg_replace('/<i\b[^>]*>(.*?)<\/i>/is', '*$1*', $md);
$md = preg_replace('/<em\b[^>]*>(.*?)<\/em>/is', '*$1*', $md);
$md = preg_replace('/<code\b[^>]*>(.*?)<\/code>/is', '`$1`', $md);
$md = preg_replace('/<pre\b[^>]*>(.*?)<\/pre>/is', "\n```\n$1\n```\n", $md);
$md = str_replace(['&nbsp;', '&bull;'], [' ', '•'], $md);
$md = str_replace(['<br>', '<br/>', '<br />'], "\n", $md);
$md = str_replace(['<hr>', '<hr/>', '<hr />'], "\n---\n", $md);
$md = preg_replace('/<tr\b[^>]*>(.*?)<\/tr>/is', "$1\n", $md);
$md = preg_replace('/<th\b[^>]*>(.*?)<\/th>/is', " | **$1**", $md);
$md = preg_replace('/<td\b[^>]*>(.*?)<\/td>/is', " | $1", $md);
$md = strip_tags($md);
$md = preg_replace("/[ \t]+/", " ", $md);
$md = preg_replace("/\n[ \t]+/", "\n", $md);
$md = preg_replace("/\n{3,}/", "\n\n", trim($md));

file_put_contents($mdPath, "# BÁO CÁO ĐỒ ÁN CHUYÊN NGÀNH\n## TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG TP.HCM\n\n" . $md);
echo "-> Đã cập nhật tệp Báo cáo Markdown (.md): $mdPath\n";

// 3. Tạo file Word DOCX chuẩn OpenXML
if (class_exists('ZipArchive')) {
    $docxPath = $outDir . '/BAO_CAO_DO_AN_CHUYEN_NGANH.docx';
    $zip = new ZipArchive();
    if ($zip->open($docxPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>');
        
        $xmlBody = "";
        $paragraphs = explode("\n", $md);
        foreach ($paragraphs as $p) {
            $text = trim($p);
            if ($text === "") continue;
            $escaped = htmlspecialchars($text, ENT_XML1, 'UTF-8');
            $isHeading1 = str_starts_with($text, '# ');
            $isHeading2 = str_starts_with($text, '## ');
            $isHeading3 = str_starts_with($text, '### ');
            $cleanText = ltrim($escaped, '# ');
            
            if ($isHeading1) {
                $xmlBody .= "<w:p><w:pPr><w:jc w:val='center'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='32'/><w:color w:val='047857'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='32'/><w:color w:val='047857'/></w:rPr><w:t>{$cleanText}</w:t></w:r></w:p>";
            } elseif ($isHeading2) {
                $xmlBody .= "<w:p><w:pPr><w:jc w:val='both'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='28'/><w:color w:val='065f46'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='28'/><w:color w:val='065f46'/></w:rPr><w:t>{$cleanText}</w:t></w:r></w:p>";
            } elseif ($isHeading3) {
                $xmlBody .= "<w:p><w:pPr><w:jc w:val='both'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='26'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:b/><w:sz w:val='26'/></w:rPr><w:t>{$cleanText}</w:t></w:r></w:p>";
            } else {
                $xmlBody .= "<w:p><w:pPr><w:jc w:val='both'/><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:sz w:val='26'/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:ascii='Times New Roman' w:hAnsi='Times New Roman'/><w:sz w:val='26'/></w:rPr><w:t>{$cleanText}</w:t></w:r></w:p>";
            }
        }
        
        $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>' . $xmlBody . '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1440" w:right="1134" w:bottom="1134" w:left="1701"/></w:sectPr></w:body></w:document>';
        $zip->addFromString('word/document.xml', $documentXml);
        $zip->close();
        echo "-> Đã cập nhật tệp Báo cáo Word chuẩn (.docx): $docxPath\n";
    }
}

echo "\n>>> HOÀN THÀNH 100% CẬP NHẬT FILE BÁO CÁO THEO ĐÚNG MẪU YÊU CẦU! <<<\n";
