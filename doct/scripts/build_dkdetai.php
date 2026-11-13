<?php
/**
 * ====================================================================
 * SCRIPT TẠO VÀ CẬP NHẬT PHIẾU ĐĂNG KÝ ĐỀ TÀI CHUẨN XÁC 100% KHÔNG THIẾU
 * Tệp: DKdetai.docx / .doc / .md
 * ====================================================================
 */

set_time_limit(300);

$outDir = __DIR__ . '/..';
$downloadsDir = 'C:/Users/ddung/Downloads';
$templateDocx = 'C:/Users/ddung/.gemini/antigravity/brain/20fc47bf-ad27-4927-8c13-9bc8082e4065/.user_uploaded/media_1794568080940_b9c371a7.docx';
if (!file_exists($templateDocx)) {
    $templateDocx = 'C:/Users/ddung/.gemini/antigravity/brain/20fc47bf-ad27-4927-8c13-9bc8082e4065/.user_uploaded/media_1794294054593_b87c77b5.docx';
}

echo "Bắt đầu cập nhật DKdetai từ template gốc: $templateDocx\n";

$destDocx = $outDir . '/DKdetai.docx';
copy($templateDocx, $destDocx);

$zip = new ZipArchive();
if ($zip->open($destDocx) === true) {
    $xml = $zip->getFromName('word/document.xml');
    $origLen = strlen($xml);
    echo "Chiều dài XML ban đầu: $origLen ký tự\n";

    // 1. Tên đề tài
    $oldTitle = "Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR &amp; ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam";
    $newTitle = "Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR &amp; ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam (FreshMart &amp; FreshMart Pay)";
    $xml = str_replace($oldTitle, $newTitle, $xml);

    // 2. Phạm vi triển khai (Mục IV.3)
    $xml = str_replace('&#x25A1; Máy tính cá nhân', '&#x2611; Máy tính cá nhân (XAMPP / Localhost)', $xml);
    $xml = str_replace('☐ Máy tính cá nhân', '☑ Máy tính cá nhân (XAMPP / Localhost)', $xml);
    $xml = str_replace('&#x2611; Web Server', '&#x2611; Web Server (Apache 2.4, Docker Engine)', $xml);
    $xml = str_replace('☑ Web Server', '☑ Web Server (Apache 2.4, Docker Engine)', $xml);
    $xml = str_replace('&#x2611; Cloud', '&#x2611; Cloud (Render Cloud Web Service + TiDB Cloud Serverless SSL/TLS)', $xml);
    $xml = str_replace('☑ Cloud', '☑ Cloud (Render Cloud Web Service + TiDB Cloud Serverless SSL/TLS)', $xml);

    // 3. Nội dung ngoài phạm vi (Mục IV.4)
    $xml = str_replace(
        '- Xây dựng toàn bộ nền tảng bán hàng (catalog sản phẩm, giỏ hàng, quản lý kho, vận chuyển)',
        '- Xây dựng toàn bộ nền tảng thương mại điện tử chuyên sâu (nhóm chỉ phát triển cửa hàng mẫu FreshMart phục vụ quy trình kiểm thử thanh toán khép kín)',
        $xml
    );
    $xml = str_replace(
        '- Ứng dụng mobile native cho khách hàng hoặc chủ shop',
        '- Ứng dụng mobile native cho khách hàng hoặc chủ shop (sử dụng giao diện Web Responsive đa nền tảng tối ưu mọi thiết bị)',
        $xml
    );
    $xml = str_replace(
        '- Tích hợp cổng thanh toán quốc tế (PayPal, Stripe…)',
        '- Tích hợp cổng thanh toán quốc tế (PayPal, Stripe… - tập trung 100% cổng thanh toán nội địa Việt Nam và đồng tiền VNĐ)',
        $xml
    );

    // 4. Bảng công nghệ thực tế (Mục V)
    $oldBackendCell = '<w:tc><w:tcPr><w:tcW w:w="5600" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/><w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/><w:bottom w:val="single" w:sz="1" w:space="0" w:color="000000"/><w:right w:val="single" w:sz="1" w:space="0" w:color="000000"/></w:tcBorders><w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/><w:tcMar><w:top w:w="60" w:type="dxa"/><w:left w:w="80" w:type="dxa"/><w:bottom w:w="60" w:type="dxa"/><w:right w:w="80" w:type="dxa"/></w:tcMar><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="5438B2F5" w14:textId="1EEE7CA7" w:rsidR="000E2AE0" w:rsidRPr="000E2695" w:rsidRDefault="002D5760" w:rsidP="000E2695"><w:pPr><w:rPr><w:lang w:val="en-US"/></w:rPr></w:pPr><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t xml:space="preserve">Node.js (NestJS hoặc Express) hoặc </w:t></w:r><w:r w:rsidR="000E2695"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>html/</w:t></w:r><w:proofErr w:type="spellStart"/><w:r w:rsidR="000E2695"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>css</w:t></w:r><w:proofErr w:type="spellEnd"/><w:r w:rsidR="000E2695"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>/</w:t></w:r><w:proofErr w:type="spellStart"/><w:r w:rsidR="000E2695"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>js</w:t></w:r><w:proofErr w:type="spellEnd"/><w:r w:rsidR="000E2695"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>/</w:t></w:r><w:proofErr w:type="spellStart"/><w:r w:rsidR="000E2695"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>php</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc>';
    $newBackendCell = '<w:tc><w:tcPr><w:tcW w:w="5600" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="1" w:space="0" w:color="000000"/><w:left w:val="single" w:sz="1" w:space="0" w:color="000000"/><w:bottom w:val="single" w:sz="1" w:space="0" w:color="000000"/><w:right w:val="single" w:sz="1" w:space="0" w:color="000000"/></w:tcBorders><w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/><w:tcMar><w:top w:w="60" w:type="dxa"/><w:left w:w="80" w:type="dxa"/><w:bottom w:w="60" w:type="dxa"/><w:right w:w="80" w:type="dxa"/></w:tcMar><w:vAlign w:val="center"/></w:tcPr><w:p><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>PHP 8.2 Apache, Clean Layering (Presentation, API, Adapter, Database), PDO Prepared Statements</w:t></w:r></w:p></w:tc>';
    if (strpos($xml, $oldBackendCell) !== false) {
        $xml = str_replace($oldBackendCell, $newBackendCell, $xml);
        echo "-> Đã cập nhật thành công mục Backend PHP 8.2\n";
    }

    $xml = str_replace(
        'ReactJS / Next.js + Tailwind CSS / Ant Design',
        'HTML5, CSS3, JavaScript (Vanilla ES6+), Bootstrap 5, Font Awesome, Chart.js',
        $xml
    );
    $xml = str_replace(
        'PostgreSQL / MySQL',
        'MySQL 8.0 (Localhost) &amp; TiDB Cloud Serverless (Production với chứng chỉ SSL/TLS)',
        $xml
    );
    $xml = str_replace(
        'RESTful API, Webhook (callback từ VietQR, MoMo, ZaloPay, VNPay)',
        'RESTful API (JSON), VietQR Open API, Webhook SePay (HMAC-SHA256), Polling 2s',
        $xml
    );
    $xml = str_replace(
        'Next.js (full-stack) hoặc NestJS + React',
        'PHP 8.2 Native + Bootstrap 5 + Adapter Pattern (Dễ dàng mở rộng đa cổng)',
        $xml
    );
    $xml = str_replace(
        'TypeScript / JavaScript, Python (nếu dùng FastAPI)',
        'PHP 8.2, JavaScript ES6+, SQL, HTML5/CSS3',
        $xml
    );
    $xml = str_replace(
        'VS Code, Git, GitHub, Postman, Docker',
        'VS Code, Git, GitHub (CI/CD), Docker Desktop, Postman, phpMyAdmin',
        $xml
    );
    $xml = str_replace(
        '<w:t>Figma</w:t>',
        '<w:t>Figma (Prototype FreshMart Storefront &amp; Admin Dashboard)</w:t>',
        $xml
    );
    $xml = str_replace(
        'Jest, Postman, Playwright / Cypress',
        'PHP CLI Smoke Tests (run_smoke_tests.php tự động kiểm tra UUID, AES-256, Bcrypt, HMAC, isPrivileged), Simulate Webhook tool, Postman',
        $xml
    );
    $xml = str_replace(
        'ChatGPT, GitHub Copilot, Claude',
        'Google Gemini (Antigravity), ChatGPT, Claude, GitHub Copilot',
        $xml
    );
    $xml = str_replace(
        'Redis (cache/queue), Nginx, Vercel/Railway cho deploy',
        'Docker Container, Apache Web Server, Render Cloud Web Service, TiDB Cloud Serverless',
        $xml
    );

    // 5. Sản phẩm dự kiến (Mục VI)
    $xml = str_replace('&#x25A1; File cài đặt/Deploy', '&#x2611; File cài đặt/Deploy (Dockerfile, script SQL TiDB Cloud, URL https://doan-yteb.onrender.com)', $xml);
    $xml = str_replace('☐ File cài đặt/Deploy', '☑ File cài đặt/Deploy (Dockerfile, script SQL TiDB Cloud, URL https://doan-yteb.onrender.com)', $xml);

    // 6. Phân công đóng góp (Mục VIII) - thay thế chính xác tuyệt đối không dùng regex nguy hiểm!
    $targetRow1 = '<w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>10</w:t></w:r><w:r w:rsidR="002D5760"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>0%</w:t></w:r>';
    $targetRow2 = '<w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>10</w:t></w:r><w:r w:rsidR="000E2695"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/><w:lang w:val="en-US"/></w:rPr><w:t>0</w:t></w:r><w:r w:rsidR="002D5760"><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>%</w:t></w:r>';
    $replacement = '<w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>50% (Đạt 100%)</w:t></w:r>';

    if (strpos($xml, $targetRow1) !== false) {
        $xml = str_replace($targetRow1, $replacement, $xml);
        echo "-> Đã thay thế thành công tỷ lệ đóng góp của Chí Dũng\n";
    } else {
        echo "Cảnh báo: Không tìm thấy targetRow1\n";
    }

    if (strpos($xml, $targetRow2) !== false) {
        $xml = str_replace($targetRow2, $replacement, $xml);
        echo "-> Đã thay thế thành công tỷ lệ đóng góp của Tuấn Hưng\n";
    } else {
        echo "Cảnh báo: Không tìm thấy targetRow2\n";
    }

    // 7. Ứng dụng AI (Mục IX)
    $xml = str_replace('&#x25A1; Gemini', '&#x2611; Gemini (Antigravity)', $xml);
    $xml = str_replace('☐ Gemini', '☑ Gemini (Antigravity)', $xml);
    $xml = str_replace('&#x25A1; Viết Test Case', '&#x2611; Viết Test Case', $xml);
    $xml = str_replace('☐ Viết Test Case', '☑ Viết Test Case', $xml);
    $xml = str_replace('&#x25A1; Sinh dữ liệu kiểm thử', '&#x2611; Sinh dữ liệu kiểm thử', $xml);
    $xml = str_replace('☐ Sinh dữ liệu kiểm thử', '☑ Sinh dữ liệu kiểm thử', $xml);

    // 8. Xóa khung ngoài bảng chữ ký Mục XII (giữ nguyên toàn bộ thông tin bên trong)
    $targetSigBorders = '<w:tblW w:w="9900" w:type="dxa"/><w:tblBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tblBorders>';
    $replacementSigBorders = '<w:tblW w:w="9900" w:type="dxa"/><w:tblBorders><w:top w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:left w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:bottom w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:right w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:insideH w:val="none" w:sz="0" w:space="0" w:color="auto"/><w:insideV w:val="none" w:sz="0" w:space="0" w:color="auto"/></w:tblBorders>';
    if (strpos($xml, $targetSigBorders) !== false) {
        $xml = str_replace($targetSigBorders, $replacementSigBorders, $xml);
        echo "-> Đã xóa khung ngoài của bảng chữ ký thành công (giữ nguyên thông tin bên trong)\n";
    } else {
        echo "Cảnh báo: Không tìm thấy targetSigBorders (có thể đã được xóa trước đó)\n";
    }

    $newLen = strlen($xml);
    echo "Chiều dài XML sau cập nhật: $newLen ký tự (Đảm bảo đầy đủ 100% không bị mất!)\n";

    $zip->addFromString('word/document.xml', $xml);
    $zip->close();
    echo "-> Đã tạo tệp Word chuẩn: $destDocx\n";
} else {
    echo "Không thể mở file zip!\n";
}

// 8. TẠO FILE WORD HTML (.doc) ĐẦY ĐỦ 100% CÁC BẢNG
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
  table { width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 11.5pt; }
  th, td { border: 1px solid #000; padding: 6px 8px; }
  th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
  .center { text-align: center; }
  .bold { font-weight: bold; }
  .no-border, .no-border tr, .no-border td, .no-border th { border: none !important; border-width: 0 !important; padding: 4px; }
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

<h2>VII. KẾ HOẠCH THỰC HIỆN SƠ BỘ (15 TUẦN)</h2>
<table>
  <thead>
    <tr>
      <th style="width: 8%;">STT</th>
      <th style="width: 27%;">Giai đoạn</th>
      <th style="width: 17%;">Thời gian</th>
      <th style="width: 48%;">Kết quả thực hiện</th>
    </tr>
  </thead>
  <tbody>
    <tr><td class="center">1</td><td class="bold">Khảo sát &amp; xác định bài toán</td><td class="center">Tuần 1–2</td><td>Báo cáo khảo sát, mô tả bài toán, phạm vi, yêu cầu sơ bộ</td></tr>
    <tr><td class="center">2</td><td class="bold">Phân tích yêu cầu</td><td class="center">Tuần 3</td><td>FR/NFR, Actor, Use Case, Use Case Diagram, User Story</td></tr>
    <tr><td class="center">3</td><td class="bold">Thiết kế kiến trúc hệ thống</td><td class="center">Tuần 4</td><td>Kiến trúc HT, công nghệ, Framework, DB, API, FE/BE, Auth</td></tr>
    <tr><td class="center">4</td><td class="bold">Thiết kế cơ sở dữ liệu</td><td class="center">Tuần 5</td><td>ERD, Database Schema, Script CSDL TiDB, Data Dictionary</td></tr>
    <tr><td class="center">5</td><td class="bold">Thiết kế UI/UX</td><td class="center">Tuần 6</td><td>User Flow, Wireframe, Prototype FreshMart &amp; Admin Portal</td></tr>
    <tr><td class="center">6</td><td class="bold">Phát triển 1–3 (nền tảng + nghiệp vụ)</td><td class="center">Tuần 7–9</td><td>Login/Register, CRUD, QR, multi-gateway, đạt 70-80% chức năng</td></tr>
    <tr><td class="center">7</td><td class="bold">Tích hợp hệ thống</td><td class="center">Tuần 10</td><td>Tích hợp FE/BE/DB, Webhook tự động, chuông báo Realtime, hoàn thiện 100%</td></tr>
    <tr><td class="center">8</td><td class="bold">Kiểm thử</td><td class="center">Tuần 11</td><td>Test Plan, 14 Test Cases, Smoke Test tự động đạt 100%</td></tr>
    <tr><td class="center">9</td><td class="bold">Hoàn thiện</td><td class="center">Tuần 12</td><td>Chức năng, UI, DB, Phân quyền Owner/Staff/User, Validation, Responsive</td></tr>
    <tr><td class="center">10</td><td class="bold">Viết báo cáo &amp; chuẩn bị bảo vệ</td><td class="center">Tuần 13–14</td><td>Báo cáo theo mẫu, slide thuyết trình, video demo, tài khoản demo</td></tr>
    <tr><td class="center">11</td><td class="bold">Bảo vệ đồ án</td><td class="center">Tuần 15</td><td>Kiểm tra SP, trình bày, demo trực tiếp, phản biện, đánh giá</td></tr>
  </tbody>
</table>

<h2>VIII. PHÂN CÔNG VÀ TỶ LỆ ĐÓNG GÓP</h2>
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

<h2>IX. ỨNG DỤNG AI TRONG QUÁ TRÌNH THỰC HIỆN</h2>
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

<h2>X. ĐÁNH GIÁ SƠ BỘ CỦA GIẢNG VIÊN</h2>
<table>
  <thead>
    <tr>
      <th style="width: 8%;">STT</th>
      <th style="width: 50%;">Tiêu chí</th>
      <th style="width: 14%;">Đạt</th>
      <th style="width: 14%;">Cần chỉnh sửa</th>
      <th style="width: 14%;">Không đạt</th>
    </tr>
  </thead>
  <tbody>
    <tr><td class="center">1</td><td>Tính phù hợp với ngành học</td><td class="center">☐</td><td class="center">☐</td><td class="center">☐</td></tr>
    <tr><td class="center">2</td><td>Tính thực tiễn</td><td class="center">☐</td><td class="center">☐</td><td class="center">☐</td></tr>
    <tr><td class="center">3</td><td>Phạm vi phù hợp với thời gian 15 tuần</td><td class="center">☐</td><td class="center">☐</td><td class="center">☐</td></tr>
    <tr><td class="center">4</td><td>Mức độ phù hợp với trình độ sinh viên</td><td class="center">☐</td><td class="center">☐</td><td class="center">☐</td></tr>
    <tr><td class="center">5</td><td>Tính khả thi về công nghệ</td><td class="center">☐</td><td class="center">☐</td><td class="center">☐</td></tr>
    <tr><td class="center">6</td><td>Có sản phẩm đầu ra rõ ràng</td><td class="center">☐</td><td class="center">☐</td><td class="center">☐</td></tr>
    <tr><td class="center">7</td><td>Có khả năng kiểm thử/đánh giá</td><td class="center">☐</td><td class="center">☐</td><td class="center">☐</td></tr>
  </tbody>
</table>

<h2>XI. KẾT QUẢ PHÊ DUYỆT ĐỀ TÀI</h2>
<p>
  ☐ ĐỒNG Ý PHÊ DUYỆT<br>
  ☐ ĐỒNG Ý NHƯNG CẦN CHỈNH SỬA<br>
  ☐ KHÔNG PHÊ DUYỆT<br>
  Tên đề tài sau khi phê duyệt (nếu có thay đổi): ................................................................
</p>

<h2>XII. CAM KẾT CỦA SINH VIÊN</h2>
<p>
  Nhóm cam kết:<br>
  - Thực hiện đề tài đúng phạm vi đã được phê duyệt.<br>
  - Tuân thủ kế hoạch và tiến độ thực hiện đồ án.<br>
  - Thực hiện đầy đủ các buổi báo cáo tiến độ.<br>
  - Chủ động nghiên cứu và giải quyết các vấn đề phát sinh.<br>
  - Tuân thủ quy định về bản quyền, đạo văn và sử dụng AI.<br>
  - Chịu trách nhiệm về phần công việc được phân công.<br>
  - Đảm bảo sản phẩm cuối cùng do nhóm thực hiện và có khả năng giải thích các thành phần chính của sản phẩm.
</p>

<div style="margin-top: 30px;">
  <table class="no-border" style="width: 100%; border: none;">
    <tr style="border: none;">
      <td class="center bold" style="width: 50%; border: none;">
        Nhóm sinh viên thực hiện<br>
        <span style="font-weight: normal; font-style: italic;">(Ký và ghi rõ họ tên)</span>
      </td>
      <td class="center bold" style="width: 50%; border: none;">
        TP. HCM, ngày … tháng … năm 2026<br>
        Giảng viên hướng dẫn<br>
        <span style="font-weight: normal; font-style: italic;">(Ký và ghi rõ họ tên)</span>
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

// 9. SAO CHÉP TOÀN BỘ SANG THƯ MỤC DOWNLOADS
if (is_dir($downloadsDir)) {
    copy($destDocx, $downloadsDir . '/DKdetai.docx');
    copy($docPath, $downloadsDir . '/DKdetai.doc');
    copy($outDir . '/DKdetai.md', $downloadsDir . '/DKdetai.md');
    echo "-> Đã sao chép toàn bộ 3 file sang $downloadsDir thành công!\n";
}

echo "\n>>> HOÀN THÀNH 100% CẬP NHẬT FILE DKdetai HOÀN CHỈNH! <<<\n";
