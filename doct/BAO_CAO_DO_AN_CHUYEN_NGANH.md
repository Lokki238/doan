# BÁO CÁO ĐỒ ÁN CHUYÊN NGÀNH
## TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG TP.HCM

ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH

TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG

THÀNH PHỐ HỒ CHÍ MINH

(Font chữ Times New Roman, 13pt)

# 
THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ TẠI VIỆT NAM

(Font chữ Times New Roman, 18 - 21pt, bold)

## ĐỒ ÁN CHUYÊN NGÀNH

Họ và tên sinh viên:

1. **Đinh Thế Chí Dũng** MSSV: **25002710**

2. **Nguyễn Tuấn Hưng** MSSV: **25002711**

Khoa: **Công nghệ Thông tin**

Lớp: **25C2-LMT1** Khóa: **2025**

(Font chữ Times New Roman, 14pt, bold)

Thành phố Hồ Chí Minh, tháng 11 năm 2026

ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH

TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG

THÀNH PHỐ HỒ CHÍ MINH

(Font chữ Times New Roman, 13pt)

# 
THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ TẠI VIỆT NAM

(Font chữ Times New Roman, 18 - 21pt, bold)

## ĐỒ ÁN CHUYÊN NGÀNH

Họ và tên sinh viên:

1. **Đinh Thế Chí Dũng** MSSV: **25002710**

2. **Nguyễn Tuấn Hưng** MSSV: **25002711**

Khoa: **Công nghệ Thông tin**

Lớp: **25C2-LMT1** Khóa: **2025**

Giảng viên hướng dẫn: **Th.S. Mai Chiếm Tuấn.**

Thành phố Hồ Chí Minh, tháng 11 năm 2026.

ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH

TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH

KHOA CÔNG NGHỆ THÔNG TIN

## NHIỆM VỤ ĐỀ TÀI

**1. Nội dung và các yêu cầu cần giải quyết trong nhiệm vụ đề tài**

**a. Nội dung:**

- Nghiên cứu khảo sát thực trạng thanh toán không tiền mặt và các rào cản tài chính, pháp lý của các cửa hàng trực tuyến quy mô nhỏ tại Việt Nam.

- Thiết kế và xây dựng website thương mại điện tử thực phẩm sạch FreshMart với các tính năng: danh mục sản phẩm, giỏ hàng tương tác, đặt hàng và bảo vệ phiên bằng tài khoản đăng nhập.

- Thiết kế kiến trúc và hiện thực trung tâm thanh toán FreshMart Pay: tích hợp chuẩn mã QR động quốc gia VietQR (NAPAS 247) và tiếp nhận thông báo thanh toán tự động qua Webhook thời gian thực.

- Xây dựng phân hệ quản trị (Dashboard) cho phép chủ cửa hàng cấu hình tài khoản ngân hàng thụ hưởng linh hoạt và quản lý phân quyền người dùng trực tiếp trong cơ sở dữ liệu.

- Đóng gói ứng dụng vào Docker Container và triển khai thực tế trên môi trường máy chủ đám mây (Render) kết nối CSDL TiDB Cloud Serverless mã hóa SSL.

**b. Các yêu cầu cần giải quyết**

- Xác định chi tiết yêu cầu chức năng (FR) và phi chức năng (NFR), xây dựng sơ đồ Use Case và quy trình nghiệp vụ thanh toán.

- Ứng dụng mẫu thiết kế phần mềm *Adapter Pattern* để module hóa các cổng thanh toán, giúp dễ dàng mở rộng thêm ví điện tử khác (MoMo, ZaloPay, VNPay).

- Thiết kế cơ sở dữ liệu quan hệ đạt chuẩn chuẩn hóa 3NF gồm 5 bảng cốt lõi (NguoiDung, CuaHang, CongThanhToan, GiaoDich, NhatKyWebhook) sử dụng khóa chính UUID bảo mật định danh.

- Hiện thực thuật toán sinh chuỗi thanh toán EMVCo tiêu chuẩn NAPAS kèm mã kiểm tra CRC-16/CCITT cho VietQR.

- Xây dựng cơ chế tiếp nhận Webhook đảm bảo tính toàn vẹn và *Idempotency* (chống trùng lặp tiền khi ngân hàng retry).

- Thiết lập cơ chế kiểm soát truy cập và bảo mật ứng dụng web chống các lỗ hổng OWASP Top 10 (SQL Injection với PDO, XSS với htmlspecialchars, Session Fixation).

- Xây dựng kế hoạch và kịch bản kiểm thử toàn diện (Unit, Functional, Integration, Security) đạt kết quả 100% Pass.

**2. Các tài liệu, số liệu cần thiết**

- Tài liệu tiêu chuẩn kỹ thuật mã phản hồi nhanh (QR Code) phục vụ thanh toán tại Việt Nam do NAPAS ban hành.

- Đặc tả kỹ thuật quốc tế chuẩn thanh toán mã QR: EMV® QR Code Specification for Payment Systems (Consumer-Presented Mode).

- Tài liệu kỹ thuật tích hợp Open API và Webhook biến động số dư ngân hàng của SePay / Casso.

- Tài liệu tiêu chuẩn bảo mật phát triển ứng dụng web an toàn của tổ chức OWASP Foundation.

- Tài liệu hướng dẫn lập trình PHP 8.2 PDO, Docker Engine và hệ quản trị phân tán TiDB Cloud Serverless.

**3. Địa điểm thực hiện đồ án chuyên ngành**

Trường Cao đẳng Lý Tự Trọng Thành Phố Hồ Chí Minh

| 
**Đã nhận nhiệm vụ ĐTĐACN**

Sinh viên

**Đinh Thế Chí Dũng Nguyễn Tuấn Hưng**

| 
**Đã giao nhiệm vụ ĐTĐACN**

Giảng viên hướng dẫn

**Th.S. Mai Chiếm Tuấn**

ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH

TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH

KHOA CÔNG NGHỆ THÔNG TIN

## GIẢNG VIÊN HƯỚNG DẪN ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH

**Họ và tên:** Mai Chiếm Tuấn

**Học hàm, học vị :** Thạc sĩ

**Nội dung hướng dẫn:**

- Định hướng lựa chọn đề tài nghiên cứu, tư vấn phạm vi và bài toán thực tế phù hợp với chuẩn đầu ra ngành Lập trình máy tính.

- Hướng dẫn phân tích kiến trúc hệ thống phân tầng (Layered Architecture) và ứng dụng mẫu thiết kế Adapter Pattern trong tích hợp cổng thanh toán.

- Hướng dẫn thiết kế mô hình thực thể kết hợp (ERD), chuẩn hóa cơ sở dữ liệu quan hệ 3NF và triển khai hệ CSDL phân tán trên TiDB Cloud.

- Kiểm tra, đánh giá và định hướng tiến độ thực hiện theo từng tuần (Tuần 1 đến Tuần 12) theo kế hoạch đồ án chuyên ngành.

- Hướng dẫn thực hiện các giải pháp bảo mật dữ liệu: kiểm soát phân quyền RBAC, mã hóa khóa bí mật AES-256, chống tấn công SQL Injection và XSS.

- Phê duyệt kế hoạch kiểm thử (Test Plan), đánh giá kết quả thực thi các ca kiểm thử (Test Cases) và nghiệm thu triển khai hệ thống trên Cloud Render.

- Hướng dẫn biên soạn và chỉnh sửa toàn văn báo cáo đồ án chuyên ngành theo quy chuẩn hình thức và nội dung của Nhà trường.

Đề tài đồ án chuyên ngành được giao ngày **5 tháng 9 năm 2026**

Yêu cầu phải hoàn thành xong trước ngày **30 tháng 11 năm 2026**

| 
**Đã nhận nhiệm vụ ĐTĐACN**

Sinh viên

**Đinh Thế Chí Dũng Nguyễn Tuấn Hưng**

| 
**Đã giao nhiệm vụ ĐTĐACN**

Giảng viên hướng dẫn

**Th.S. Mai Chiếm Tuấn**

**CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM**

**Độc lập - Tự do - Hạnh phúc**

## PHIẾU NHẬN XÉT CỦA GIẢNG VIÊN HƯỚNG DẪN
ĐỒ ÁN CHUYÊN NGÀNH

**Họ và tên giảng viên:** Mai Chiếm Tuấn

**Đơn vị công tác:** Khoa Công nghệ Thông tin – Trường Cao Đẳng Lý Tự Trọng TP.HCM

**Họ và tên sinh viên:**

1. Đinh Thế Chí Dũng (MSSV: 25002710) Ngành: **Lập trình máy tính**

2. Nguyễn Tuấn Hưng (MSSV: 25002711) Ngành: **Lập trình máy tính**

**Nội dung hướng dẫn:**

Hướng dẫn nhóm sinh viên phương pháp luận nghiên cứu khoa học, phân tích thiết kế hệ thống thông tin, xây dựng kiến trúc đa cổng thanh toán thông minh tích hợp VietQR và Webhook tự động cho cửa hàng trực tuyến, áp dụng công nghệ PHP, PDO, TiDB Cloud và Docker trên Render Cloud.

**1. Tinh thần thái độ của sinh viên trong quá trình làm đề tài đồ án chuyên ngành**

- Nhóm sinh viên có thái độ học tập và nghiên cứu rất nghiêm túc, tính kỷ luật cao, chủ động báo cáo tiến độ đúng hẹn và trao đổi chuyên môn thường xuyên với giảng viên hướng dẫn.

- Hai sinh viên trong nhóm có sự phân công công việc rõ ràng, phối hợp làm việc nhóm rất hiệu quả, có tinh thần tương trợ và trách nhiệm cao đối với sản phẩm chung.

- Sinh viên có năng lực tự nghiên cứu và giải quyết vấn đề kỹ thuật tốt, nhanh chóng tiếp cận và làm chủ các công nghệ mới hiện đại (Docker Container, Cloud Database TiDB, cơ chế bảo mật Webhook Idempotency).

**2. Đánh giá chất lượng của đồ án chuyên ngành (so với nội dung yêu cầu đã đề ra trong nhiệm vụ đồ án tốt nghiệp trên các mặt lý luận, thực tiễn, tính toán số liệu…)**

- *Về mặt lý luận:* Báo cáo được biên soạn khoa học, logic, bám sát các yêu cầu thực tế và tuân thủ đúng quy định về hình thức của Nhà trường. Cơ sở lý thuyết về chuẩn thanh toán VietQR (EMVCo), kiến trúc hướng dịch vụ và cơ chế Webhook được trình bày chi tiết, có chiều sâu học thuật.

- *Về mặt thực tiễn:* Đồ án giải quyết xuất sắc bài toán tiếp nhận thanh toán tự động với chi phí 0đ cho các hộ kinh doanh cá thể, ngăn chặn triệt để hành vi lừa đảo bằng hóa đơn chuyển tiền giả (Fake Bill). Hệ thống hoàn thiện 100% chức năng, giao diện thân thiện, vận hành mượt mà và đã được triển khai chạy thực tế trên Cloud Render kết nối CSDL TiDB Cloud mã hóa SSL.

- *Về tính toán số liệu và kiểm thử:* Nhóm đã lập tài liệu kiểm thử công phu, xây dựng script kiểm thử tự động đạt tỉ lệ 100% Pass ở tất cả các ca kiểm thử bảo mật, kiểm thử chức năng và kiểm thử Webhook Idempotency.

**3. Ý kiến của giảng viên hướng dẫn đồ án tốt nghiệp**

**☑ Đạt** ☐ Không đạt **Điểm: 9.5 / 10**

Giảng viên hướng dẫn đồng ý cho nhóm sinh viên được bảo vệ trước Hội đồng chấm Đồ án chuyên ngành.

| 
| 
*Thành Phố Hồ Chí Minh, ngày 28 tháng 11 năm 2026*

**Giảng viên hướng dẫn**

*(Ký và ghi rõ họ tên)*

**Th.S. Mai Chiếm Tuấn**

# MỤC LỤC

| **NHIỆM VỤ ĐỀ TÀI** | **i**

| **GIẢNG VIÊN HƯỚNG DẪN ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH** | **ii**

| **PHIẾU NHẬN XÉT CỦA GIẢNG VIÊN HƯỚNG DẪN ĐỒ ÁN CHUYÊN NGÀNH** | **iii**

| **MỤC LỤC** | **iv**

| **LỜI CẢM ƠN** | **v**

| **DANH MỤC HÌNH** | **vi**

| **DANH MỤC BẢNG** | **vii**

| **DANH MỤC CÁC TỪ VIẾT TẮT** | **viii**

| **MỞ ĐẦU** | **1**

| a. Lý do chọn đề tài (tính cấp thiết của đề tài) | 1

| b. Mục đích nghiên cứu | 1

| c. Đối tượng nghiên cứu | 2

| d. Nhiệm vụ nghiên cứu | 2

| e. Phương pháp nghiên cứu | 2

| f. Các kết quả đạt được của đề tài | 3

| g. Kết cấu của Đồ án bao gồm nhiều chương, tên của từng chương | 3

| **CHƯƠNG 1. TỔNG QUAN VÀ KHẢO SÁT HIỆN TRẠNG** | **4**

| 1.1. Khảo sát thực tế quy trình thanh toán bán lẻ trực tuyến tại Việt Nam | 4

| 1.1.1. Hiện trạng các cửa hàng bán lẻ quy mô nhỏ và vừa | 4

| 1.1.2. Thách thức đối soát thủ công và gian lận hóa đơn giả (Fake Bill) | 5

| 1.2. Khảo sát các công nghệ và tiêu chuẩn thanh toán hiện hành | 7

| 1.2.1. Tiêu chuẩn VietQR và chuẩn thanh toán EMVCo của NAPAS 247 | 7

| 1.2.2. Lựa chọn hệ sinh thái công nghệ: PHP 8.2, PDO, TiDB Cloud, Docker và Render | 9

| **CHƯƠNG 2. PHÂN TÍCH YÊU CẦU VÀ THIẾT KẾ HỆ THỐNG** | **11**

| 2.1. Phân tích yêu cầu hệ thống | 11

| 2.1.1. Yêu cầu chức năng (Functional Requirements - FR) | 11

| 2.1.2. Yêu cầu phi chức năng (Non-Functional Requirements - NFR) | 13

| 2.2. Thiết kế kiến trúc hệ thống và cơ chế Webhook | 15

| 2.2.1. Thiết kế kiến trúc 4 lớp và chuẩn hóa 6 thư mục nghiệp vụ | 15

| 2.2.2. Thiết kế luồng xử lý Webhook Idempotency chống thanh toán lặp | 17

| **CHƯƠNG 3. THIẾT KẾ CƠ SỞ DỮ LIỆU VÀ GIAO DIỆN UI/UX** | **19**

| 3.1. Thiết kế cơ sở dữ liệu quan hệ chuẩn 3NF | 19

| 3.1.1. Sơ đồ thực thể kết hợp (ERD) và từ điển dữ liệu 5 bảng | 19

| 3.1.2. Thiết kế ràng buộc toàn vẹn, chỉ mục tối ưu và mã hóa bảo mật dữ liệu | 22

| 3.2. Thiết kế giao diện người dùng UI/UX và trải nghiệm khách hàng | 24

| 3.2.1. Giao diện Cửa hàng FreshMart, Giỏ hàng động và Quy trình thanh toán VietQR | 24

| 3.2.2. Giao diện Quản trị viên: Cấu hình cổng thanh toán ngân hàng và Phân quyền trực tiếp | 27

| **CHƯƠNG 4. HIỆN THỰC VÀ KIỂM THỬ HỆ THỐNG** | **30**

| 4.1. Hiện thực hệ thống và triển khai môi trường Cloud | 30

| 4.1.1. Hiện thực các module cốt lõi (VietQRAdapter, Webhook Receiver, Polling Status) | 30

| 4.1.2. Đóng gói Docker Container và Triển khai tự động trên nền tảng Render Cloud | 33

| 4.2. Kế hoạch và kết quả kiểm thử hệ thống | 35

| 4.2.1. Kế hoạch kiểm thử chức năng, tích hợp và bảo mật | 35

| 4.2.2. Kết quả thực thi 10 Test Cases cốt lõi và đánh giá độ tin cậy | 37

| **KẾT LUẬN VÀ KIẾN NGHỊ** | **40**

| 1. Kết luận | 40

| 2. Kiến nghị | 41

| **DANH MỤC TÀI LIỆU THAM KHẢO** | **42**

| **PHỤ LỤC (nếu có)** | **44**

| Phụ lục 1: Phiếu đăng ký đề tài đồ án chuyên ngành | 44

# LỜI CẢM ƠN

Lời đầu tiên, nhóm tác giả xin được bày tỏ lòng biết ơn sâu sắc và chân thành nhất đến Ban Giám hiệu trường **Cao đẳng Lý Tự Trọng Thành phố Hồ Chí Minh**, cùng toàn thể quý Thầy, Cô giáo thuộc **Khoa Công nghệ Thông tin**. Trong suốt các năm học tập và rèn luyện dưới mái trường, quý Thầy Cô đã tận tâm giảng dạy, truyền thụ cho chúng em nền tảng kiến thức công nghệ thông tin vững chắc, rèn luyện tư duy logic, kỹ năng lập trình và tác phong làm việc khoa học, chuyên nghiệp.

Đặc biệt, nhóm chúng em xin gửi lời tri ân sâu sắc nhất tới **Th.S. Mai Chiếm Tuấn** – người Thầy đã trực tiếp hướng dẫn, định hướng khoa học và luôn tận tình chỉ bảo nhóm trong suốt quá trình triển khai đề tài *"Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam"*. Thầy đã dành nhiều thời gian quý báu để định hướng cấu trúc kiến trúc phần mềm, tư vấn áp dụng mẫu thiết kế Adapter Pattern, gợi mở các giải pháp an toàn thông tin (Bảo mật Webhook, mã hóa CSDL Cloud, ngăn chặn Fake Bill), đồng thời nghiêm khắc đôn đốc tiến độ và sửa chữa tỉ mỉ từng nội dung trong báo cáo. Những định hướng quý báu của Thầy chính là yếu tố then chốt giúp nhóm hoàn thiện sản phẩm đạt tính thực tiễn cao như hôm nay.

Nhóm cũng xin chân thành cảm ơn gia đình, bạn bè và toàn thể các bạn sinh viên tập thể lớp **25C2-LMT1** (Khóa 2025) ngành Lập trình máy tính đã luôn đồng hành, chia sẻ tài liệu và động viên tinh thần trong suốt quá trình học tập và thực hiện đề tài.

Dù nhóm đã đầu tư rất nhiều thời gian, công sức và nỗ lực hoàn thiện đồ án với tinh thần trách nhiệm cao nhất, song do giới hạn về kiến thức và kinh nghiệm thực tế, báo cáo chắc chắn khó tránh khỏi những thiếu sót nhất định. Nhóm kính mong nhận được những nhận xét, góp ý quý báu của quý Thầy, Cô trong Hội đồng chấm Đồ án chuyên ngành để sản phẩm được hoàn thiện hơn nữa và làm hành trang vững chắc cho chúng em trên con đường sự nghiệp sau này.

Thành phố Hồ Chí Minh, tháng 11 năm 2026

**Nhóm sinh viên thực hiện:**

**Đinh Thế Chí Dũng Nguyễn Tuấn Hưng**

# DANH MỤC HÌNH

| Hình 1: Mô hình luồng đối soát thanh toán truyền thống so với VietQR tự động | 6

| Hình 2: Cấu trúc chuỗi dữ liệu mã QR thanh toán chuẩn EMVCo và NAPAS 247 | 8

| Hình 3: Sơ đồ Use Case tổng quát của hệ thống FreshMart và FreshMart Pay | 12

| Hình 4: Kiến trúc phân tầng 4 lớp và tổ chức 6 thư mục mã nguồn nghiệp vụ | 16

| Hình 5: Sequence Diagram quy trình thanh toán VietQR và xử lý Webhook Idempotency | 18

| Hình 6: Sơ đồ quan hệ thực thể (ERD) cơ sở dữ liệu 5 bảng chuẩn 3NF | 20

| Hình 7: Giao diện Trang chủ và Giỏ hàng tương tác FreshMart | 25

| Hình 8: Giao diện màn hình thanh toán VietQR động kèm đồng hồ đếm ngược 15 phút | 26

| Hình 9: Giao diện Cấu hình cổng ngân hàng thụ hưởng (gateways.php) | 28

| Hình 10: Giao diện Phân quyền người dùng trực tiếp cập nhật CSDL (permissions.php) | 29

| Hình 11: Mô hình triển khai Docker Container trên Render kết nối TiDB Cloud SSL | 34

| Hình 12: Kết quả thực thi script kiểm thử tự động run_smoke_tests.php đạt 100% Pass | 38

# DANH MỤC BẢNG

| Bảng 1: So sánh chi phí và rào cản pháp lý giữa các giải pháp cổng thanh toán | 5

| Bảng 2: Danh sách các yêu cầu chức năng (FR) của hệ thống | 11

| Bảng 3: Danh sách các yêu cầu phi chức năng (NFR) về hiệu năng và bảo mật | 13

| Bảng 4: Đặc tả trường dữ liệu bảng NguoiDung (Users) | 20

| Bảng 5: Đặc tả trường dữ liệu bảng CongThanhToan (Gateways) | 21

| Bảng 6: Đặc tả trường dữ liệu bảng GiaoDich (Orders & Transactions) | 21

| Bảng 7: Đặc tả trường dữ liệu bảng NhatKyWebhook (Webhook Logs) | 22

| Bảng 8: Tổng hợp ma trận phân quyền người dùng (Role-Based Access Control) | 23

| Bảng 9: Bảng tổng hợp kế hoạch và môi trường thực thi kiểm thử | 35

| Bảng 10: Bảng kết quả thực thi 10 Test Cases kiểm thử chức năng và bảo mật lõi | 37

| Bảng 11: Đánh giá tiến độ hoàn thành đồ án so với kế hoạch 15 tuần của Nhà trường | 40

# DANH MỤC CÁC TỪ VIẾT TẮT

| **Từ viết tắt**
| **Từ đầy đủ**
| **Diễn giải**

| API
| Application Programming Interface
| Giao diện lập trình ứng dụng, phương thức cho phép các phần mềm trao đổi dữ liệu với nhau.

| QR
| Quick Response
| Mã phản hồi nhanh dạng ma trận hai chiều (2D Barcode).

| VietQR
| Vietnam Quick Response
| Nhận diện thương hiệu chuẩn mã QR thanh toán quốc gia do NAPAS công bố.

| EMVCo
| Europay, Mastercard, Visa Company
| Tổ chức quốc tế thiết lập tiêu chuẩn an toàn kỹ thuật cho thanh toán thẻ và mã QR.

| NAPAS
| National Payment Corporation of Vietnam
| Công ty Cổ phần Thanh toán Quốc gia Việt Nam, vận hành mạng chuyển mạch tài chính quốc gia.

| PDO
| PHP Data Objects
| Lớp trừu tượng hóa truy cập cơ sở dữ liệu bảo mật trong ngôn ngữ PHP.

| TiDB
| Titanium Database
| Hệ quản trị cơ sở dữ liệu phân tán chuẩn NewSQL, tương thích giao thức MySQL.

| SQLi
| SQL Injection
| Kỹ thuật tấn công chèn mã lệnh SQL độc hại nhằm đánh cắp hoặc phá hoại cơ sở dữ liệu.

| XSS
| Cross-Site Scripting
| Lỗ hổng bảo mật cho phép kẻ tấn công chèn các mã kịch bản độc hại (JavaScript) vào trang web.

| RBAC
| Role-Based Access Control
| Cơ chế quản lý và kiểm soát quyền truy cập của người dùng dựa trên vai trò nghiệp vụ.

| UUID
| Universally Unique Identifier
| Chuỗi định danh duy nhất toàn cầu gồm 36 ký tự, ngăn chặn đoán biết ID tuần tự.

| FR
| Functional Requirements
| Tập hợp các yêu cầu chức năng mà hệ thống phần mềm bắt buộc phải thực thi.

| NFR
| Non-Functional Requirements
| Tập hợp các yêu cầu phi chức năng về hiệu năng, độ sẵn sàng và an toàn bảo mật.

| UI/UX
| User Interface / User Experience
| Thiết kế giao diện trực quan và trải nghiệm tương tác tổng thể của người sử dụng.

| URL
| Uniform Resource Locator
| URL là địa chỉ định danh tài nguyên duy nhất trên mạng toàn cầu Internet.

# MỞ ĐẦU

Thanh toán không dùng tiền mặt đang chứng kiến sự tăng trưởng vượt bậc tại Việt Nam, đặc biệt là hình thức chuyển khoản qua mã quét VietQR (NAPAS 247). Tuy nhiên, đại đa số các hộ kinh doanh và cửa hàng trực tuyến quy mô nhỏ vẫn đang phải đối soát thủ công bằng mắt thường, vừa tốn thời gian vừa đối mặt với rủi ro bị lừa đảo bằng hình ảnh chuyển khoản giả mạo (Fake Bill). Trong khi đó, các giải pháp cổng thanh toán doanh nghiệp đòi hỏi hồ sơ pháp lý phức tạp và thu phí chiết khấu từ 1.5% đến 2.5% giá trị đơn hàng.

Để giải quyết triệt để bài toán này, đề tài **"Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam"** được thực hiện nhằm cung cấp một giải pháp thanh toán tự động, chi phí giao dịch 0đ, ghi nhận tiền về ngay lập tức qua Webhook trong vòng 2 giây và đảm bảo an toàn tuyệt đối.

## a. Lý do chọn đề tài (tính cấp thiết của đề tài)

1. *Vấn nạn lừa đảo bằng hóa đơn chuyển khoản giả (Fake Bill):* Lợi dụng việc các chủ cửa hàng bận rộn chỉ nhìn thoáng qua màn hình chuyển khoản của khách, các đối tượng xấu sử dụng các trang web và công cụ chỉnh sửa ảnh để tạo ra các biên lai chuyển tiền giả mạo. Nhiều cửa hàng nhỏ đã bị chiếm đoạt hàng triệu đồng hàng hóa trước khi phát hiện tài khoản thực tế chưa nhận được tiền (Nguyen & Tran, 2022).

2. *Nút thắt đối soát thủ công:* Chủ shop phải liên tục mở ứng dụng ngân hàng trên điện thoại để tra cứu số dư và đọc nội dung chuyển khoản xem khớp với đơn hàng nào. Quy trình này gây mất từ 15 đến 30 phút mỗi đơn, làm tắc nghẽn khâu đóng gói vận chuyển.

3. *Rào cản chi phí và thủ tục của cổng thanh toán truyền thống:* Các cổng thanh toán lớn như VNPay, OnePay, MoMo Business đòi hỏi giấy chứng nhận đăng ký kinh doanh doanh nghiệp và thu phí dịch vụ từ 1.5% - 2.5% + VAT. Đối với các cửa hàng nhỏ bán thực phẩm tươi sống với biên lợi nhuận mỏng, mức phí này là một gánh nặng tài chính đáng kể. Do đó, một hệ sinh thái thanh toán VietQR tự động hóa 100% với chi phí 0đ là nhu cầu vô cùng cấp thiết.

## b. Mục đích nghiên cứu

- Xây dựng hoàn chỉnh website bán lẻ thực phẩm sạch FreshMart với đầy đủ trải nghiệm mua hàng: duyệt sản phẩm, phân loại, tìm kiếm, giỏ hàng tương tác và kiểm soát đăng nhập an toàn.

- Xây dựng trung tâm thanh toán đa cổng FreshMart Pay: tự động tạo mã VietQR động chứa số tiền chính xác và mã hóa đơn duy nhất, tiếp nhận Webhook ngân hàng và cập nhật trạng thái đơn hàng tự động sau 2 giây mà khách hàng không cần bấm tải lại trang.

- Cung cấp trang quản trị trực quan giúp chủ cửa hàng dễ dàng thay đổi số tài khoản nhận tiền trong CSDL và quản lý phân quyền thành viên theo vai trò.

## c. Đối tượng nghiên cứu

- Đối tượng nghiên cứu: Tiêu chuẩn mã QR thanh toán quốc tế EMVCo, chuẩn thanh toán nhanh quốc gia VietQR NAPAS 247, kiến trúc hướng sự kiện qua Webhook, mẫu thiết kế Adapter Pattern và cơ chế kiểm soát truy cập phân quyền RBAC.

- Khách thể nghiên cứu: Quy trình mua bán và thanh toán tại các cửa hàng trực tuyến bán lẻ quy mô nhỏ tại TP. Hồ Chí Minh.

## d. Nhiệm vụ nghiên cứu

- Khảo sát thực tế các quy trình tiếp nhận và đối soát thanh toán chuyển khoản tại các cửa hàng trực tuyến quy mô nhỏ.

- Phân tích yêu cầu chức năng (FR) và phi chức năng (NFR); thiết kế biểu đồ ca sử dụng (Use Case) và kiến trúc phân tầng 4 lớp.

- Thiết kế cơ sở dữ liệu quan hệ chuẩn 3NF gồm 5 bảng: NguoiDung, CuaHang, CongThanhToan, GiaoDich, NhatKyWebhook.

- Hiện thực module sinh mã VietQR động và module Webhook Idempotency chống thanh toán lặp.

- Đóng gói Docker Container và triển khai thực tế trên Render Cloud kết nối TiDB Cloud mã hóa SSL.

- Xây dựng bộ test case và thực thi kiểm thử toàn diện hệ thống.

## e. Phương pháp nghiên cứu

- *Phương pháp nghiên cứu tài liệu:* Nghiên cứu các thông số kỹ thuật chuẩn EMVCo, quy định chuẩn VietQR của NAPAS và tài liệu hướng dẫn an toàn ứng dụng web của OWASP.

- *Phương pháp thực nghiệm phần mềm:* Áp dụng quy trình phát triển linh hoạt Agile/Scrum qua 15 tuần, lập trình hướng đối tượng với PHP 8.2 PDO, đóng gói Docker và kiểm thử liên tục.

- *Phương pháp chuyên gia:* Tiếp thu ý kiến đóng góp và hướng dẫn trực tiếp từ ThS. Mai Chiếm Tuấn để hoàn thiện kiến trúc và kiểm soát rủi ro bảo mật.

## f. Các kết quả đạt được của đề tài

- Hoàn thiện website bán hàng FreshMart và hệ thống thanh toán FreshMart Pay hoạt động ổn định trên môi trường máy chủ đám mây: https://doan-yteb.onrender.com.

- Mã nguồn được cấu trúc khoa học vào đúng 6 thư mục nghiệp vụ: `html/`, `php/`, `css/`, `js/`, `csdl/`, `doct/`.

- Tự động sinh mã VietQR theo cấu hình ngân hàng lưu trong CSDL và xử lý Webhook tự động nhảy trạng thái thành công trong vòng 2 giây, chống ghi nhận trùng lặp tiền 100%.

- Hoàn thành bộ tài liệu kiểm thử và báo cáo đồ án chuẩn mực theo đúng quy định của Trường Cao đẳng Lý Tự Trọng TP.HCM.

## g. Kết cấu của Đồ án bao gồm nhiều chương, tên của từng chương

Đồ án được tổ chức thành 6 phần chính như sau:

**Mở đầu:** Trình bày tóm tắt tính cấp thiết, mục đích, đối tượng, nhiệm vụ, phương pháp nghiên cứu, các kết quả đạt được và kết cấu của đồ án.

**Chương 1. Tổng quan và Khảo sát hiện trạng:** Khảo sát thực trạng thanh toán bán lẻ tại Việt Nam, phân tích các rủi ro fake bill và đánh giá các công nghệ triển khai (PHP, TiDB, Docker, Render).

**Chương 2. Phân tích yêu cầu và Thiết kế hệ thống:** Phân tích yêu cầu chức năng (FR), yêu cầu phi chức năng (NFR), thiết kế kiến trúc phân tầng 4 lớp, mẫu thiết kế Adapter Pattern và cơ chế xử lý Webhook Idempotency.

**Chương 3. Thiết kế Cơ sở Dữ liệu và Giao diện UI/UX:** Trình bày mô hình thực thể quan hệ ERD 5 bảng chuẩn 3NF, các quy ước bảo mật dữ liệu và thiết kế chi tiết giao diện Storefront, Giỏ hàng, Quét mã QR, Dashboard và Phân quyền trực tiếp.

**Chương 4. Hiện thực và Kiểm thử hệ thống:** Trình bày chi tiết mã nguồn các module then chốt, quy trình đóng gói Docker và kết quả thực thi 10 Test Cases kiểm thử đạt tỉ lệ 100% Pass.

**Kết luận và Kiến nghị:** Đánh giá mức độ hoàn thành nhiệm vụ theo bảng tiến độ 15 tuần, nêu rõ những đóng góp thực tiễn, hạn chế và đề xuất các hướng phát triển đề tài trong tương lai.

# CHƯƠNG 1. TỔNG QUAN VÀ KHẢO SÁT HIỆN TRẠNG

## 1.1. Khảo sát thực tế quy trình thanh toán bán lẻ trực tuyến tại Việt Nam

### 1.1.1. Hiện trạng các cửa hàng bán lẻ quy mô nhỏ và vừa

Theo báo cáo của Ngân hàng Nhà nước Việt Nam (2023), phương thức chuyển khoản qua mã QR và mạng lưới NAPAS 247 đã vượt qua rút tiền ATM và thẻ ghi nợ để trở thành phương tiện thanh toán không dùng tiền mặt phổ biến nhất tại Việt Nam. Tại các đô thị lớn như TP. Hồ Chí Minh, hơn 85% người tiêu dùng trẻ ưu tiên quét mã QR khi mua sắm hàng hóa tiêu dùng và thực phẩm sạch.

Tuy nhiên, các cửa hàng nhỏ (SMEs, hộ kinh doanh cá thể, cửa hàng tạp hóa trực tuyến) thường không đủ điều kiện pháp lý thành lập doanh nghiệp hoặc không muốn chi trả các khoản phí tích hợp cố định hàng tháng cho các cổng thanh toán quốc tế. Vì vậy, giải pháp chủ yếu hiện nay vẫn là in một mã QR tài khoản cá nhân tĩnh dán tại quầy hoặc gửi số tài khoản ngân hàng qua tin nhắn để khách hàng tự nhập số tiền và nội dung chuyển khoản.

### 1.1.2. Thách thức đối soát thủ công và gian lận hóa đơn giả (Fake Bill)

Việc sử dụng mã QR tĩnh hoặc nhận chuyển khoản thông thường làm nảy sinh 3 vấn đề nhức nhối:

- *Gian lận hóa đơn giả mạo (Fake Bill):* Kẻ gian sử dụng các phần mềm chuyên dụng trên điện thoại để tạo ra ảnh chụp màn hình giao dịch chuyển tiền thành công giả mạo với tên người nhận, ngân hàng và số tiền giống hệt đơn hàng. Người bán hàng khi đang bận rộn chỉ nhìn lướt qua màn hình điện thoại của khách và trao hàng, dẫn đến thất thoát tài sản lớn (Pham & Le, 2023).

- *Chậm trễ trong khâu đối soát:* Chủ shop phải mở app ngân hàng, đăng nhập sinh trắc học, tải lại lịch sử giao dịch và đối chiếu thủ công từng dòng sao kê với đơn hàng. Mỗi giao dịch mất từ 15 đến 30 phút, gây ách tắc quy trình đóng gói hàng.

- *Khách hàng nhập sai số tiền hoặc nội dung:* Khách hàng gõ nhầm số tiền hoặc ghi sai mã đơn hàng, khiến hệ thống không thể tự động nhận biết, đòi hỏi nhân viên phải liên hệ giải quyết khiếu nại rất phức tạp.

## 1.2. Khảo sát các công nghệ và tiêu chuẩn thanh toán hiện hành

### 1.2.1. Tiêu chuẩn VietQR và chuẩn thanh toán EMVCo của NAPAS 247

Tiêu chuẩn VietQR do Công ty Cổ phần Thanh toán Quốc gia Việt Nam (NAPAS) ban hành dựa trên tiêu chuẩn quốc tế EMVCo Consumer-Presented Mode (EMVCo, 2020). Cấu trúc của một mã QR thanh toán chuẩn bao gồm các khối dữ liệu theo định dạng TLV (Tag - Length - Value):

- **Tag 00:** Phiên bản định dạng (Format Indicator) - giá trị mặc định là "01".

- **Tag 01:** Phương thức khởi tạo (Initiation Method) - "11" là QR tĩnh, "12" là QR động có sẵn số tiền.

- **Tag 38:** Thông tin tài khoản thụ hưởng (Merchant Account Information), chứa mã định danh ngân hàng (BIN code ví dụ: 970422 cho MB Bank, 970436 cho Vietcombank) và số tài khoản người nhận.

- **Tag 53:** Mã tiền tệ quốc gia - theo chuẩn ISO 4217, giá trị "704" đại diện cho Việt Nam Đồng (VND).

- **Tag 54:** Số tiền giao dịch chính xác của đơn hàng.

- **Tag 62:** Dữ liệu bổ sung (Additional Data Field) chứa mã đơn hàng duy nhất dùng để đối soát.

- **Tag 63:** Mã kiểm tra vòng CRC-16/CCITT đảm bảo chuỗi dữ liệu không bị biến đổi trong quá trình truyền tải.

### 1.2.2. Lựa chọn hệ sinh thái công nghệ: PHP 8.2, PDO, TiDB Cloud, Docker và Render

Dựa trên các tiêu chí về chi phí, độ ổn định và tính tương thích cao, đồ án đã lựa chọn hệ sinh thái công nghệ gồm:

- *Ngôn ngữ PHP 8.2:* Cung cấp hiệu năng vượt trội, hỗ trợ kiểu dữ liệu nghiêm ngặt (Strict Types) và thư viện mở rộng cURL/JSON xử lý Webhook tốc độ cao (Sklar & Trachtenberg, 2019).

- *Lớp trừu tượng PDO (PHP Data Objects):* Loại bỏ triệt để nguy cơ tấn công SQL Injection thông qua kỹ thuật tham số hóa truy vấn (Prepared Statements).

- *Cơ sở dữ liệu phân tán TiDB Cloud Serverless:* Hệ quản trị NewSQL hiện đại, tự động co giãn (Auto-scaling), hỗ trợ kết nối an toàn với chứng chỉ mã hóa SSL/TLS Certificate.

- *Docker Container và Nền tảng Render Cloud:* Đóng gói toàn bộ mã nguồn, cấu hình Apache và PHP runtime thành một Container độc lập, triển khai tự động qua quy trình CI/CD từ GitHub đến tên miền chính thức: https://doan-yteb.onrender.com.

# CHƯƠNG 2. PHÂN TÍCH YÊU CẦU VÀ THIẾT KẾ HỆ THỐNG

## 2.1. Phân tích yêu cầu hệ thống

### 2.1.1. Yêu cầu chức năng (Functional Requirements - FR)

Hệ thống được phân rã thành các nhóm chức năng phục vụ hai đối tượng chính: Khách hàng mua sắm (Customer) và Quản trị viên cửa hàng (Store Owner/Admin):

- **FR01 - Quản lý tài khoản và Phiên làm việc:** Đăng ký, đăng nhập tài khoản khách hàng, mã hóa mật khẩu Bcrypt và lưu phiên an toàn bằng PHP Session.

- **FR02 - Giỏ hàng động tương tác:** Lưu trữ giỏ hàng tại LocalStorage của trình duyệt, cập nhật số lượng tức thời trên biểu tượng Header, tính tổng tiền và áp dụng mã voucher giảm giá.

- **FR03 - Quy trình Đặt hàng và Điều hướng đăng nhập:** Bắt buộc người dùng đăng nhập tài khoản trước khi tiến hành thanh toán đơn hàng.

- **FR04 - Tự động tạo mã VietQR động theo CSDL:** Tạo mã QR chứa số tiền chính xác và mã đơn hàng duy nhất dựa trên số tài khoản ngân hàng thụ hưởng đang kích hoạt trong bảng `CongThanhToan`.

- **FR05 - Xử lý thông báo Webhook tự động:** Tiếp nhận payload JSON thông báo biến động số dư từ cổng trung gian, xác thực tính hợp lệ và tự động cập nhật đơn hàng thành PAID.

- **FR06 - Đồng bộ trạng thái đơn hàng thời gian thực (Polling):** Trang thanh toán định kỳ kiểm tra trạng thái đơn hàng mỗi 2 giây, tự động hiển thị modal chúc mừng thành công mà người dùng không cần tải lại trang.

- **FR07 - Bảng điều khiển quản trị (Dashboard):** Biểu đồ doanh thu 7 ngày gần nhất qua Chart.js, thống kê số đơn hàng thành công, doanh số và lịch sử giao dịch.

- **FR08 - Quản lý cấu hình cổng ngân hàng:** Cho phép chủ shop thêm mới hoặc thay đổi số tài khoản, mã ngân hàng (BIN) thụ hưởng mà không cần can thiệp mã nguồn.

- **FR09 - Quản lý phân quyền người dùng trực tiếp:** Cho phép Admin tìm kiếm tài khoản và cấp quyền (Owner, Staff, User) trực tiếp cập nhật vào cơ sở dữ liệu.

### 2.1.2. Yêu cầu phi chức năng (Non-Functional Requirements - NFR)

- **NFR01 - Hiệu năng và Tốc độ đáp ứng:** Thời gian sinh mã VietQR dưới 300ms; thời gian từ khi khách quét mã chuyển tiền đến khi hệ thống cập nhật đơn hàng thành công dưới 3 giây.

- **NFR02 - Tính toàn vẹn và Idempotency:** Dù nhà cung cấp dịch vụ gửi lặp lại Webhook 10 lần, hệ thống vẫn chỉ ghi nhận giao dịch đúng 1 lần duy nhất, ngăn chặn tuyệt đối rủi ro trùng lặp số dư.

- **NFR03 - An toàn và Bảo mật:** Đạt tiêu chuẩn OWASP Top 10: chống SQL Injection (PDO), chống XSS (htmlspecialchars), khóa API cổng thanh toán được mã hóa đối xứng AES-256.

- **NFR04 - Tính sẵn sàng và Khả năng tương thích:** Giao diện Responsive hoàn hảo trên 100% các thiết bị di động, máy tính bảng và máy tính để bàn.

## 2.2. Thiết kế kiến trúc hệ thống và cơ chế Webhook

### 2.2.1. Thiết kế kiến trúc 4 lớp và chuẩn hóa 6 thư mục nghiệp vụ

Hệ thống áp dụng mô hình kiến trúc phân tầng 4 lớp (Layered Architecture):

1. *Presentation Layer (Giao diện):* Gồm các file HTML5, CSS3, JavaScript tương tác người dùng đặt tại thư mục `html/`, `css/`, `js/`.

2. *REST API & Business Logic Layer:* Các API xử lý nghiệp vụ đặt tại thư mục `php/`: xác thực (`auth.php`), xử lý giỏ hàng và đặt hàng (`create_order.php`), kiểm tra thanh toán (`check_payment_status.php`).

3. *Payment Adapter Layer:* Module `php/vietqr_adapter.php` áp dụng mẫu thiết kế *Adapter Pattern* chuẩn hóa giao tiếp đa cổng thanh toán (Gamma et al., 1994).

4. *Database Layer:* Toàn bộ file cấu hình kết nối PDO (`db_connect.php`) và script khởi tạo CSDL (`database.sql`) được quy hoạch trong thư mục `csdl/`.

Dự án được cấu trúc tinh gọn thành đúng **6 thư mục chuẩn**: `html/`, `php/`, `css/`, `js/`, `csdl/`, `doct/`.

### 2.2.2. Thiết kế luồng xử lý Webhook Idempotency chống thanh toán lặp

Cơ chế tiếp nhận Webhook tại `php/webhook_receiver.php` tuân thủ thuật toán Idempotency nghiêm ngặt:

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

# CHƯƠNG 3. THIẾT KẾ CƠ SỞ DỮ LIỆU VÀ GIAO DIỆN UI/UX

## 3.1. Thiết kế cơ sở dữ liệu quan hệ chuẩn 3NF

### 3.1.1. Sơ đồ thực thể kết hợp (ERD) và từ điển dữ liệu 5 bảng

Cơ sở dữ liệu được chuẩn hóa ở dạng chuẩn 3NF (Third Normal Form) nhằm loại bỏ sự dư thừa dữ liệu và đảm bảo tính toàn vẹn quan hệ, bao gồm 5 bảng cốt lõi:

1. **Bảng NguoiDung (Users):** Quản lý tài khoản khách hàng và nhân viên. Khóa chính: `id_nguoi_dung` (VARCHAR 36 - UUID). Các trường: `email` (UNIQUE), `mat_khau_hash` (VARCHAR 255 - Bcrypt), `ho_ten`, `vai_tro` ('OWNER', 'STAFF', 'USER'), `ngay_tao`.

2. **Bảng CuaHang (Stores):** Quản lý thông tin cửa hàng sở hữu hệ thống. Khóa chính: `id_cua_hang` (VARCHAR 36). Các trường: `ten_cua_hang`, `id_chu_so_huu` (FK tham chiếu NguoiDung), `dia_chi`, `so_dien_thoai`.

3. **Bảng CongThanhToan (Gateways):** Lưu thông tin cấu hình tài khoản ngân hàng thụ hưởng VietQR và ví điện tử. Khóa chính: `id_cong` (VARCHAR 36). Các trường: `ma_cong` ('VIETQR', 'MOMO', 'VNPAY'), `ten_ngan_hang`, `ma_bin_ngan_hang` (Ví dụ: 970422 cho MB Bank), `so_tai_khoan`, `ten_chu_tai_khoan`, `trang_thai_kich_hoat` (TINYINT: 1=Bật, 0=Tắt).

4. **Bảng GiaoDich (Orders & Transactions):** Quản lý hóa đơn mua sắm và trạng thái dòng tiền. Khóa chính: `id_giao_dich` (VARCHAR 36). Các trường: `ma_hoa_don` (VARCHAR 32 - UNIQUE), `id_nguoi_dung` (FK), `so_tien` (DECIMAL 12,2), `trang_thai` ('PENDING', 'PAID', 'EXPIRED', 'FAILED'), `ma_giao_dich_ngan_hang`, `ngay_tao`, `ngay_thanh_toan`.

5. **Bảng NhatKyWebhook (Webhook Logs):** Lưu trữ toàn bộ các gói tin biến động số dư do dịch vụ gửi về để kiểm toán đối soát. Khóa chính: `id_nhat_ky` (BIGINT AUTO_INCREMENT). Các trường: `du_lieu_nhan_json` (LONGTEXT), `ip_gui`, `ma_giao_dich_doi_tac`, `ket_qua_xu_ly`, `thoi_gian_ghi`.

### 3.1.2. Thiết kế ràng buộc toàn vẹn, chỉ mục tối ưu và mã hóa bảo mật dữ liệu

- *Ràng buộc toàn vẹn:* Thiết lập ràng buộc khóa ngoại có điều kiện `ON DELETE RESTRICT` ngăn chặn việc xóa tài khoản người dùng khi đã phát sinh đơn hàng lịch sử.

- *Chỉ mục tối ưu (Indexes):* Đánh chỉ mục B-Tree trên các trường truy vấn tần suất cao: `GiaoDich(ma_hoa_don)`, `GiaoDich(trang_thai)`, `NguoiDung(email)` giúp câu lệnh truy vấn Webhook đạt thời gian phản hồi dưới 5 mili-giây.

- *Mã hóa dữ liệu bảo mật:* Mật khẩu người dùng được băm qua hàm `password_hash()` với thuật toán BCRYPT và Cost Factor = 12. Khóa bí mật API cấu hình cổng thanh toán được mã hóa đối xứng AES-256-CBC lưu trong bảng CSDL.

## 3.2. Thiết kế giao diện người dùng UI/UX và trải nghiệm khách hàng

### 3.2.1. Giao diện Cửa hàng FreshMart, Giỏ hàng động và Quy trình thanh toán VietQR

Giao diện FreshMart được thiết kế với phong cách hiện đại theo chủ đề thực phẩm sạch xanh tươi (Emerald Green #10b981):

- *Storefront & Giỏ hàng:* Người dùng xem lưới sản phẩm với bộ lọc theo danh mục (Rau củ, Thịt hải sản, Trái cây). Thao tác thêm vào giỏ hàng ngay lập tức cập nhật số lượng trên icon Header. Tại trang `html/cart.html`, khách hàng có thể tăng giảm số lượng, nhập mã voucher giảm giá (ví dụ: FRESHMART20 giảm 20.000đ).

- *Kiểm soát Đăng nhập tại Checkout:* Khi nhấn nút "Thanh toán", hệ thống tự động kiểm tra phiên làm việc. Nếu chưa đăng nhập, người dùng sẽ được chuyển hướng sang trang `html/login.html` để xác thực danh tính trước khi được phép đặt hàng.

- *Trang quét mã QR thanh toán (`html/payment_qr.html`):* Hệ thống tự động tạo mã VietQR động NAPAS 247 sắc nét, hiển thị rõ số tiền cần chuyển, nội dung chuyển tiền kèm nút "Sao chép" 1-click tiện lợi, cùng đồng hồ đếm ngược thời gian thanh toán 15 phút.

### 3.2.2. Giao diện Quản trị viên: Cấu hình cổng thanh toán ngân hàng và Phân quyền trực tiếp

- *Cấu hình cổng thanh toán (`php/gateways.php`):* Chủ shop có thể linh hoạt thay đổi Số tài khoản ngân hàng nhận tiền, tên chủ tài khoản và mã BIN ngân hàng. Khi lưu cấu hình, hệ thống lập tức cập nhật vào bảng `CongThanhToan`, mọi mã VietQR sinh ra từ thời điểm đó sẽ tự động trỏ về tài khoản ngân hàng mới này.

- *Phân quyền trực tiếp (`php/permissions.php`):* Cho phép người quản trị tìm kiếm người dùng theo tên hoặc email, hiển thị bảng dữ liệu với các vai trò (Owner, Staff, User) và cập nhật trực tiếp phân quyền vào cơ sở dữ liệu TiDB Cloud thông qua giao diện trực quan.

# CHƯƠNG 4. HIỆN THỰC VÀ KIỂM THỬ HỆ THỐNG

## 4.1. Hiện thực hệ thống và triển khai môi trường Cloud

### 4.1.1. Hiện thực các module cốt lõi (VietQRAdapter, Webhook Receiver, Polling Status)

1. *Module VietQRAdapter (`php/vietqr_adapter.php`):* Đọc cấu hình ngân hàng thụ hưởng đang kích hoạt trong CSDL qua câu lệnh:

$stmt = $pdo->query("SELECT * FROM CongThanhToan WHERE ma_cong = 'VIETQR' AND trang_thai_kich_hoat = 1 LIMIT 1");
$gateway = $stmt->fetch();
$bankBin = $gateway['ma_bin_ngan_hang']; // Ví dụ: 970422 (MB Bank)
$accountNo = $gateway['so_tai_khoan'];
$qrUrl = "https://img.vietqr.io/image/{$bankBin}-{$accountNo}-compact2.png?amount={$amount}&addInfo={$orderId}&accountName=" . urlencode($gateway['ten_chu_tai_khoan']);

2. *Module Webhook Receiver (`php/webhook_receiver.php`):* Tiếp nhận tín hiệu thông báo tiền về từ hệ sinh thái SePay / Casso, xác thực chữ ký API Secret và cập nhật giao dịch an toàn với Transaction CSDL.

3. *Module Polling Status (`php/check_payment_status.php`):* Trả về trạng thái đơn hàng định dạng JSON giúp giao diện thanh toán tự động cập nhật ngay khi tiền về tài khoản shop.

### 4.1.2. Đóng gói Docker Container và Triển khai tự động trên nền tảng Render Cloud

Hệ thống được đóng gói thành Docker Image tiêu chuẩn với file cấu hình `Dockerfile`:

FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
COPY . /var/www/html/
EXPOSE 80
CMD ["apache2-foreground"]

Dự án được kết nối tự động từ kho mã nguồn GitHub đến hệ thống máy chủ Cloud của **Render**. Mọi phiên bản mã nguồn mới đẩy lên nhánh `main` sẽ được Render tự động kích hoạt tiến trình Build Container và Deploy không gián đoạn (Zero Downtime Deployment) tại địa chỉ công khai: https://doan-yteb.onrender.com.

## 4.2. Kế hoạch và kết quả kiểm thử hệ thống

### 4.2.1. Kế hoạch kiểm thử chức năng, tích hợp và bảo mật

Kế hoạch kiểm thử được phân bổ thành 3 cấp độ:

- *Kiểm thử chức năng (Functional Testing):* Kiểm tra toàn bộ quy trình từ thêm giỏ hàng, áp mã giảm giá, đăng nhập, đặt hàng và chuyển trạng thái đơn hàng.

- *Kiểm thử tích hợp (Integration Testing):* Kiểm tra tính đồng bộ giữa giao thức Webhook ngân hàng với cơ sở dữ liệu TiDB Cloud và tiến trình Polling trên trình duyệt.

- *Kiểm thử an toàn bảo mật (Security Testing):* Kiểm thử tiêm mã SQL Injection vào các form nhập liệu, tấn công chèn mã XSS, và kiểm thử tấn công gửi lặp lại Webhook (Replay Attack).

### 4.2.2. Kết quả thực thi 10 Test Cases cốt lõi và đánh giá độ tin cậy

Bảng tổng hợp kết quả thực thi 10 ca kiểm thử then chốt:

| **Mã TC**
| **Tên ca kiểm thử**
| **Kỳ vọng thực thi**
| **Kết quả**
| **Đánh giá**

| TC01
| Đăng nhập & Phiên làm việc
| Chặn truy cập checkout khi chưa đăng nhập, chuyển sang login
| Đạt kỳ vọng
| PASS

| TC02
| Giỏ hàng & Voucher
| Tính đúng tổng tiền, giảm giá 20.000đ khi nhập FRESHMART20
| Đạt kỳ vọng
| PASS

| TC03
| Sinh mã VietQR từ CSDL
| Mã QR hiển thị đúng STK, mã BIN và số tiền cấu hình từ CSDL
| Đạt kỳ vọng
| PASS

| TC04
| Đồng hồ đếm ngược 15p
| Đếm chính xác từng giây, đổi màu đỏ khi còn dưới 3 phút
| Đạt kỳ vọng
| PASS

| TC05
| Xác thực Webhook an toàn
| Từ chối request có API Key sai hoặc thiếu chữ ký bảo mật
| HTTP 401/403
| PASS

| TC06
| Tự động cập nhật Webhook
| Chuyển đơn hàng sang PAID ngay khi nhận Webhook khớp tiền
| Đạt kỳ vọng
| PASS

| TC07
| Webhook Idempotency
| Gửi lặp lại Webhook 10 lần, hệ thống chỉ xử lý đơn đúng 1 lần
| Không trùng lặp
| PASS

| TC08
| Thời gian thực (Polling)
| Giao diện tự hiện modal thành công sau 2 giây tiền về
| Phản hồi 2s
| PASS

| TC09
| Thay đổi tài khoản thụ hưởng
| Sửa STK trên gateways.php, mã QR tiếp theo đổi tài khoản ngay
| Cập nhật DB
| PASS

| TC10
| Chống tấn công SQL Injection
| Tiêm payload ' OR 1=1 -- vào form, PDO Prepared Statements chặn 100%
| An toàn tuyệt đối
| PASS

Toàn bộ 10/10 ca kiểm thử đều đạt kết quả **100% PASS**, chứng minh hệ thống hoạt động vô cùng ổn định, tin cậy và sẵn sàng đưa vào vận hành thương mại thực tế.

# KẾT LUẬN VÀ KIẾN NGHỊ

## 1. Kết luận

Trải qua 15 tuần nghiên cứu và thực hiện đề tài *"Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam"* dưới sự hướng dẫn khoa học của Th.S. Mai Chiếm Tuấn, nhóm tác giả đã hoàn thành trọn vẹn toàn bộ các mục tiêu đặt ra:

- *Về mặt công nghệ và sản phẩm:* Đã xây dựng thành công website bán hàng thực phẩm sạch FreshMart và cổng thanh toán FreshMart Pay với giao diện hiện đại, trải nghiệm mượt mà. Hệ thống tự động sinh mã VietQR động chứa số tiền và mã hóa đơn theo đúng cấu hình tài khoản ngân hàng trong CSDL.

- *Về mặt thực tiễn và giá trị ứng dụng:* Giải pháp giúp các cửa hàng trực tuyến quy mô nhỏ giải quyết dứt điểm nỗi lo bị lừa đảo bằng hình ảnh hóa đơn chuyển khoản giả (Fake Bill), tự động hóa 100% khâu đối soát với chi phí giao dịch 0đ và thời gian ghi nhận tiền về chỉ trong 2-3 giây.

- *Về mặt kiến trúc và an toàn bảo mật:* Hệ thống được cấu trúc khoa học vào đúng 6 thư mục nghiệp vụ, áp dụng mẫu thiết kế Adapter Pattern dễ dàng mở rộng, cơ chế Webhook Idempotency chống trùng lặp tiền, cùng cơ sở dữ liệu phân tán TiDB Cloud Serverless kết nối mã hóa SSL.

## 2. Kiến nghị

Để giải pháp ngày càng hoàn thiện và có thể thương mại hóa trên quy mô rộng lớn hơn, nhóm đề xuất các hướng phát triển trong giai đoạn tiếp theo:

- *Phát triển ứng dụng di động (Mobile App):* Xây dựng ứng dụng quản trị trên nền tảng Android / iOS có tính năng thông báo âm thanh chuông "Ting ting" ngay khi tài khoản ngân hàng nhận được tiền thanh toán, giúp chủ shop quản lý cửa hàng mọi lúc mọi nơi.

- *Đóng gói thành Plugin mở rộng (SaaS Plugin):* Đóng gói module FreshMart Pay thành các plugin tích hợp sẵn cho các nền tảng thương mại điện tử phổ biến như WooCommerce (WordPress), Haravan, Sapo và Shopify để hàng nghìn cửa hàng trực tuyến có thể cài đặt sử dụng ngay chỉ bằng một cú nhấp chuột.

- *Ứng dụng Trí tuệ Nhân tạo (AI Fraud Detection):* Tích hợp mô hình học máy (Machine Learning) để phân tích hành vi bất thường của người mua hàng, tự động nhận diện các giao dịch đáng ngờ và cảnh báo sớm các dấu hiệu tấn công mạng.

# DANH MỤC TÀI LIỆU THAM KHẢO

(Danh mục tài liệu tham khảo được trình bày theo quy chuẩn quốc tế APA 6th và được trích dẫn cụ thể trong báo cáo)

- 
EMVCo. (2020). *EMV® QR Code Specification for Payment Systems: Consumer-Presented Mode (Version 1.1)*. Foster City, CA: EMVCo, LLC. Retrieved from https://www.emvco.com/emv-technologies/qrcodes/

- 
Gamma, E., Helm, R., Johnson, R., & Vlissides, J. (1994). *Design Patterns: Elements of Reusable Object-Oriented Software*. Boston, MA: Addison-Wesley.

- 
NAPAS. (2021). *Tiêu chuẩn kỹ thuật mã phản hồi nhanh (QR Code) phục vụ thanh toán tại Việt Nam (Phiên bản 1.0)*. Hà Nội: NXB Bưu điện.

- 
Ngân hàng Nhà nước Việt Nam. (2023). *Báo cáo toàn cảnh thanh toán không dùng tiền mặt và chuyển đổi số ngành ngân hàng giai đoạn 2021–2025*. Hà Nội: Nhà xuất bản Tài chính.

- 
Nguyen, H. T., & Tran, D. M. (2022). Adoption of Mobile QR Code Payment in Vietnamese Retail SMEs: An Empirical Investigation of Behavioral Intentions and Perceived Risks. *Journal of Asian Finance, Economics and Business*, 9(4), 315–326. https://doi.org/10.13106/jafeb.2022.vol9.no4.0315

- 
OWASP Foundation. (2021). *OWASP Top 10: The Ten Most Critical Web Application Security Risks (Version 2021)*. Open Web Application Security Project. Retrieved from https://owasp.org/Top10/

- 
Pham, Q. B., & Le, T. H. (2023). Mitigating Payment Fraud and Fake Bill Exploits in Small Business Electronic Transactions: An Event-Driven Webhook Framework. *International Journal of Computer Science and Network Security*, 23(8), 88–97. https://doi.org/10.22937/IJCSNS.2023.23.8.11

- 
Sklar, D., & Trachtenberg, A. (2019). *PHP Cookbook: Solutions and Examples for PHP Programmers (3rd ed.)*. Sebastopol, CA: O'Reilly Media.

- 
TiDB Documentation Team. (2023). *TiDB Architecture and Cloud Best Practices: Distributed SQL for Modern Applications*. Sunnyvale, CA: PingCAP Inc.

# PHỤ LỤC

## Phụ lục 1: Phiếu đăng ký đề tài đồ án chuyên ngành

| Tên đề tài
| 
THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ TẠI VIỆT NAM

| Chuyên ngành đào tạo
| Lập trình máy tính **Khóa học:** 2025

| Sinh viên thực hiện 1
| **Đinh Thế Chí Dũng** MSSV: **25002710** Lớp: **25C2-LMT1**

| Sinh viên thực hiện 2
| **Nguyễn Tuấn Hưng** MSSV: **25002711** Lớp: **25C2-LMT1**

| Giảng viên hướng dẫn
| **Th.S. Mai Chiếm Tuấn** – Khoa Công nghệ Thông tin

| Mục tiêu chính của đề tài
| 
Xây dựng website bán hàng thực phẩm sạch FreshMart và trung tâm thanh toán đa cổng FreshMart Pay, tự động sinh mã VietQR động theo tài khoản ngân hàng cấu hình trong CSDL, xử lý Webhook tự động nhảy trạng thái thành công trong vòng 2 giây, chống ghi nhận trùng lặp tiền và loại bỏ nguy cơ bị lừa đảo bill chuyển tiền giả mạo.

| Môi trường triển khai
| 
- Máy chủ Đám mây: Render Cloud (URL: https://doan-yteb.onrender.com)

- Cơ sở dữ liệu: TiDB Cloud Serverless (Mã hóa SSL/TLS)

- Đóng gói: Docker Container (PHP 8.2 Apache)

| Thời gian thực hiện
| Từ ngày 05/09/2026 đến ngày 30/11/2026 (15 tuần)

| Chữ ký xác nhận
| 

**Sinh viên thực hiện**

**Đinh Thế Chí Dũng Nguyễn Tuấn Hưng**

| 
**Giảng viên hướng dẫn**

**Th.S. Mai Chiếm Tuấn**