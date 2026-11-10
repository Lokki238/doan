# BÁO CÁO ĐỒ ÁN CHUYÊN NGÀNH
## THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH

ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH
      TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH
      KHOA CÔNG NGHỆ THÔNG TIN
    
    
  

  
    
      THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ TẠI VIỆT NAM
    

# 
    ĐỒ ÁN CHUYÊN NGÀNH

## 
  

  
    Họ và tên sinh viên thực hiện:

    1. Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: 25002710

    2. Nguyễn Tuấn Hưng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: 25002711

    Khoa: Công nghệ Thông tin

    Ngành: Lập trình máy tính &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lớp: 25C2-LMT1 &nbsp;&nbsp;&nbsp;&nbsp; Khóa: 2025

  

  
    Thành phố Hồ Chí Minh, tháng 11 năm 2026
  

  
    
      ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH
      TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH
      KHOA CÔNG NGHỆ THÔNG TIN
    
    
  

  
    
      THIẾT KẾ VÀ TRIỂN KHAI HỆ THỐNG THANH TOÁN ĐA CỔNG THÔNG MINH TÍCH HỢP VIETQR & VÍ ĐIỆN TỬ CHO CỬA HÀNG TRỰC TUYẾN QUY MÔ NHỎ TẠI VIỆT NAM
    

# 
    ĐỒ ÁN CHUYÊN NGÀNH

## 
  

  
    Họ và tên sinh viên thực hiện:

    1. Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: 25002710

    2. Nguyễn Tuấn Hưng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: 25002711

    Khoa: Công nghệ Thông tin

    Ngành: Lập trình máy tính &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lớp: 25C2-LMT1 &nbsp;&nbsp;&nbsp;&nbsp; Khóa: 2025

    Giảng viên hướng dẫn: ThS. Mai Chiếm Tuấn

  

  
    Thành phố Hồ Chí Minh, tháng 11 năm 2026
  

  
    ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH
    TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH
    KHOA CÔNG NGHỆ THÔNG TIN
  
  
  NHIỆM VỤ ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH

## 

  1. Nội dung và các yêu cầu cần giải quyết trong nhiệm vụ đề tài:

  a. Nội dung:

  
    Nghiên cứu mô hình thanh toán không dùng tiền mặt tại Việt Nam và giải quyết bài toán tiếp nhận dòng tiền cho các cửa hàng bán lẻ quy mô nhỏ. Thiết kế và hiện thực hệ thống bán hàng thực phẩm sạch FreshMart kết hợp nền tảng thanh toán đa cổng thông minh FreshMart Pay, ưu tiên tích hợp chuẩn mã QR động quốc gia VietQR (NAPAS 247) và cơ chế tự động ghi nhận thanh toán thời gian thực qua Webhook. Xây dựng phân hệ bảng điều khiển (Dashboard) trực quan giúp chủ cửa hàng theo dõi doanh thu, lịch sử giao dịch và phân quyền người dùng đa cấp.
  

  b. Các yêu cầu cần giải quyết:

  
    Phân tích yêu cầu chức năng (FR) và phi chức năng (NFR) cho khách hàng và người quản trị cửa hàng nhỏ.
- 
    Thiết kế kiến trúc hệ thống phân tầng (Layered Architecture) áp dụng mẫu thiết kế Adapter Pattern nhằm hỗ trợ mở rộng đa cổng thanh toán linh hoạt.
- 
    Thiết kế cơ sở dữ liệu quan hệ chuẩn hóa (5 bảng cốt lõi) với khóa chính UUID chống lộ dữ liệu và ràng buộc toàn vẹn.
- 
    Hiện thực module sinh mã VietQR động theo chuẩn EMVCo/NAPAS chứa đúng thông tin tài khoản ngân hàng thụ hưởng cấu hình từ CSDL, số tiền và mã hóa đơn.
- 
    Xây dựng endpoint Webhook xử lý tín hiệu thông báo biến động số dư từ dịch vụ trung gian (SePay/Casso), đảm bảo tính Idempotent (chống trùng tiền) và xác thực tính toàn vẹn dữ liệu.
- 
    Phân quyền người dùng theo vai trò RBAC (Owner/Admin, Staff, User); hoàn thiện tính năng cấp quyền trực tiếp cập nhật CSDL.
- 
    Đóng gói hệ thống bằng Docker và triển khai thực tế trên máy chủ Cloud (Render) cùng cơ sở dữ liệu Cloud (TiDB Serverless mã hóa SSL/TLS).
- 
    Thực hiện kiểm thử toàn diện: Unit Test, Functional Test, Integration Test và kiểm thử bảo mật chống SQL Injection, XSS, Giả mạo chữ ký.
- 
  

  2. Các tài liệu, số liệu cần thiết:

  
    Tài liệu đặc tả kỹ thuật VietQR và cấu trúc mã QR EMVCo của Công ty Cổ phần Thanh toán Quốc gia Việt Nam (NAPAS).
- 
    Tài liệu tích hợp API và Webhook của cổng thanh toán SePay / Casso Open API.
- 
    Tài liệu hướng dẫn an toàn bảo mật ứng dụng web của tổ chức OWASP (OWASP Top 10).
- 
    Tài liệu kỹ thuật PHP 8.2 PDO, Docker Engine, Cloudflare SSL và TiDB Cloud Serverless.
- 
  

  3. Địa điểm thực hiện đồ án chuyên ngành:

  Trường Cao đẳng Lý Tự Trọng Thành Phố Hồ Chí Minh.

  
    
      
        Đã nhận nhiệm vụ ĐTĐACN
        Sinh viên
        Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng
       | 
      
        Đã giao nhiệm vụ ĐTĐACN
        Giảng viên hướng dẫn
        ThS. Mai Chiếm Tuấn
       | 
    

  

  
    ỦY BAN NHÂN DÂN THÀNH PHỐ HỒ CHÍ MINH
    TRƯỜNG CAO ĐẲNG LÝ TỰ TRỌNG THÀNH PHỐ HỒ CHÍ MINH
    KHOA CÔNG NGHỆ THÔNG TIN
  
  
  GIẢNG VIÊN HƯỚNG DẪN ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH

## 

  Họ và tên giảng viên: Mai Chiếm Tuấn

  Học hàm, học vị: Thạc sĩ

  Đơn vị công tác: Khoa Công nghệ Thông tin – Trường Cao Đẳng Lý Tự Trọng TP.HCM

  Nội dung hướng dẫn:

  
    Định hướng chọn đề tài và xác định phạm vi nghiên cứu phù hợp với thời lượng 15 tuần của học phần Đồ án chuyên ngành.
- 
    Hướng dẫn phân tích kiến trúc hệ thống đa cổng, tư vấn sử dụng mẫu thiết kế phần mềm Adapter Pattern.
- 
    Định hướng thiết kế cơ sở dữ liệu quan hệ đảm bảo chuẩn hóa, tính an toàn và khả năng mở rộng.
- 
    Kiểm tra, góp ý và đánh giá tiến độ thực hiện định kỳ theo từng tuần (Tuần 1 đến Tuần 12).
- 
    Hướng dẫn giải pháp an toàn thông tin: kiểm soát phiên làm việc (Session Auth), mã hóa thông tin nhạy cảm AES-256, xác thực chữ ký Webhook HMAC-SHA256.
- 
    Duyệt tài liệu kế hoạch kiểm thử (Test Plan) và kịch bản kiểm thử (Test Cases); nghiệm thu sản phẩm chạy thực tế trên Cloud.
- 
    Hướng dẫn chỉnh sửa, hoàn thiện bản báo cáo đồ án và chuẩn bị slide bảo vệ tốt nghiệp.
- 
  

  Đề tài đồ án chuyên ngành được giao ngày 05 tháng 09 năm 2026

  Yêu cầu phải hoàn thành xong trước ngày 30 tháng 11 năm 2026

  
    
      
        Đã nhận nhiệm vụ ĐTĐACN
        Sinh viên
        Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng
       | 
      
        Đã giao nhiệm vụ ĐTĐACN
        Giảng viên hướng dẫn
        ThS. Mai Chiếm Tuấn
       | 
    

  

  
    CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM
    Độc lập - Tự do - Hạnh phúc
  
  
  PHIẾU NHẬN XÉT CỦA GIẢNG VIÊN HƯỚNG DẪNĐỒ ÁN CHUYÊN NGÀNH

## 

  Họ và tên giảng viên: Mai Chiếm Tuấn &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Học vị: Thạc sĩ

  Đơn vị công tác: Khoa Công nghệ Thông tin – Trường Cao Đẳng Lý Tự Trọng TP.HCM

  Họ và tên sinh viên được hướng dẫn:

  1. Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: 25002710 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ngành: Lập trình máy tính

  2. Nguyễn Tuấn Hưng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MSSV: 25002711 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ngành: Lập trình máy tính

  Tên đề tài: Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam.

  1. Tinh thần thái độ của sinh viên trong quá trình làm đề tài đồ án chuyên ngành:

  
    Nhóm sinh viên thể hiện tinh thần học tập rất nghiêm túc, có ý thức kỷ luật cao và thái độ cầu thị. Trong suốt 15 tuần thực hiện, các sinh viên tham gia đầy đủ các buổi báo cáo định kỳ, chủ động tìm kiếm giải pháp kỹ thuật, phân công nhiệm vụ công bằng và phối hợp nhóm nhịp nhàng. Sinh viên có khả năng tự học công nghệ mới rất tốt (Docker, TiDB Serverless, Webhook, Adapter Pattern) và hoàn thành các mốc kỹ thuật sớm hơn tiến độ đề ra.
  

  2. Đánh giá chất lượng của đồ án chuyên ngành:

  
    - Về mặt lý luận và thiết kế: Báo cáo trình bày rõ ràng, mạch lạc, tuân thủ đúng quy cách của Nhà trường. Phân tích yêu cầu bài toán sát thực tế, thiết kế kiến trúc phân tầng khoa học và thiết kế CSDL chuẩn hóa, bảo mật tốt (UUID, bcrypt, AES-256).
  

  
    - Về mặt thực tiễn và sản phẩm: Sản phẩm phần mềm hoàn thiện vượt mong đợi. Nhóm không chỉ chạy thử nghiệm trên máy cá nhân mà đã đóng gói container Docker và triển khai thực tế trên Cloud (Render) cùng CSDL Cloud TiDB mã hóa SSL. Module thanh toán VietQR động kết hợp cơ chế Webhook thời gian thực hoạt động rất mượt mà, giải quyết trọn vẹn nỗi lo đối soát tiền thủ công của các chủ shop nhỏ.
  

  
    - Về công tác kiểm thử: Nhóm có kế hoạch kiểm thử bài bản, xây dựng bộ test case chi tiết và có script kiểm thử tự động đạt tỉ lệ 100% Pass các ca kiểm thử then chốt.
  

  3. Ý kiến của giảng viên hướng dẫn đồ án chuyên ngành:

  
    ☑ Đạt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ☐ Không đạt &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Điểm đánh giá: ......... / 10
  

  Đồng ý cho nhóm sinh viên được bảo vệ trước Hội đồng chấm Đồ án chuyên ngành.

  
    
       | 
      
        Thành phố Hồ Chí Minh, ngày &nbsp;&nbsp;&nbsp;&nbsp; tháng &nbsp;&nbsp;&nbsp;&nbsp; năm 2026
        Giảng viên hướng dẫn
        (Ký và ghi rõ họ tên)
        ThS. Mai Chiếm Tuấn
       | 
    

  

LỜI CẢM ƠN

# 

  Lời đầu tiên, nhóm tác giả xin được bày tỏ lòng biết ơn sâu sắc và chân thành nhất đến quý Thầy, Cô giáo trường Cao đẳng Lý Tự Trọng Thành phố Hồ Chí Minh, đặc biệt là các Thầy, Cô thuộc Khoa Công nghệ Thông tin. Trong suốt quá trình học tập và rèn luyện dưới mái trường, quý Thầy Cô đã tận tâm truyền đạt những kiến thức chuyên môn quý báu, rèn giũa tư duy lập trình và định hướng phương pháp tiếp cận công nghệ thực tế, tạo nền tảng vững chắc để chúng em tự tin bước vào môi trường làm việc thực tiễn.

  Đặc biệt, nhóm chúng em xin gửi lời tri ân sâu sắc nhất tới ThS. Mai Chiếm Tuấn – người Thầy đã trực tiếp hướng dẫn, định hướng và đồng hành cùng nhóm trong suốt quá trình nghiên cứu đề tài "Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam". Thầy đã dành nhiều thời gian quý báu để định hướng kiến trúc, gợi mở giải pháp xử lý kỹ thuật phức tạp (Adapter Pattern, Webhook Idempotency, Bảo mật CSDL Cloud), đồng thời nghiêm khắc nhắc nhở tiến độ và chỉnh sửa từng chi tiết trong bản báo cáo. Những lời góp ý tâm huyết của Thầy chính là kim chỉ nam giúp đồ án đạt được chất lượng tốt và có giá trị ứng dụng cao như hôm nay.

  Chúng em cũng xin gửi lời cảm ơn đến gia đình, bạn bè và tập thể lớp 25C2-LMT1 đã luôn động viên, chia sẻ tài liệu và hỗ trợ tinh thần cho nhóm vượt qua những giai đoạn kiểm thử, sửa lỗi khó khăn.

  Mặc dù nhóm đã nỗ lực hết mình với tinh thần trách nhiệm cao nhất, song do thời gian và kinh nghiệm thực tế của sinh viên còn hạn chế, đồ án chắc chắn khó tránh khỏi những thiếu sót nhất định. Nhóm kính mong nhận được những nhận xét, góp ý quý báu từ Hội đồng chấm thi để chúng em tiếp tục hoàn thiện sản phẩm và nâng cao năng lực chuyên môn trong tương lai.

  Thành phố Hồ Chí Minh, tháng 11 năm 2026
  Nhóm sinh viên thực hiện:
  Đinh Thế Chí Dũng &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nguyễn Tuấn Hưng

MỤC LỤC

# 

  NHIỆM VỤ ĐỀ TÀI | i | 

  PHIẾU NHẬN XÉT CỦA GIẢNG VIÊN HƯỚNG DẪN | iii | 

  LỜI CẢM ƠN | iv | 

  DANH MỤC HÌNH | vi | 

  DANH MỤC BẢNG | vii | 

  DANH MỤC CÁC TỪ VIẾT TẮT | viii | 

  MỞ ĐẦU | 1 | 

  a. Lý do chọn đề tài (Tính cấp thiết) | 1 | 

  b. Mục đích nghiên cứu | 2 | 

  c. Đối tượng nghiên cứu | 2 | 

  d. Nhiệm vụ nghiên cứu | 2 | 

  e. Phương pháp nghiên cứu | 3 | 

  f. Các kết quả đạt được của đề tài | 3 | 

  g. Bố cục của Đồ án | 4 | 

  CHƯƠNG 1. TỔNG QUAN VÀ KHẢO SÁT HIỆN TRẠNG | 5 | 

  1.1. Bối cảnh thanh toán điện tử tại Việt Nam | 5 | 

  1.2. Khảo sát bài toán thanh toán tại các cửa hàng trực tuyến quy mô nhỏ | 6 | 

  1.3. Khảo sát các giải pháp thanh toán hiện hữu (VietQR, MoMo, VNPay, ZaloPay) | 8 | 

  1.4. Khảo sát mô hình bán lẻ thực phẩm sạch FreshMart | 10 | 

  1.5. Xác định mục tiêu và phạm vi cụ thể của giải pháp đề xuất | 12 | 

  CHƯƠNG 2. PHÂN TÍCH YÊU CẦU VÀ THIẾT KẾ KIẾN TRÚC HỆ THỐNG | 14 | 

  2.1. Phân tích yêu cầu chức năng (FR) và phi chức năng (NFR) | 14 | 

  2.2. Phân tích các tác nhân (Actors) và biểu đồ ca sử dụng (Use Case) | 16 | 

  2.3. Thiết kế kiến trúc tổng thể hệ thống (Layered Architecture) | 18 | 

  2.4. Ứng dụng mẫu thiết kế Adapter Pattern cho module đa cổng thanh toán | 21 | 

  2.5. Thiết kế luồng xử lý thanh toán VietQR và Webhook thời gian thực | 23 | 

  2.6. Thiết kế giải pháp an toàn thông tin và bảo mật dữ liệu | 26 | 

  CHƯƠNG 3. THIẾT KẾ CƠ SỞ DỮ LIỆU VÀ GIAO DIỆN NGƯỜI DÙNG | 29 | 

  3.1. Thiết kế mô hình thực thể quan hệ (ERD) | 29 | 

  3.2. Đặc tả chi tiết các bảng trong cơ sở dữ liệu | 31 | 

  3.3. Thiết kế luồng trải nghiệm người dùng (User Flow) | 35 | 

  3.4. Thiết kế giao diện chi tiết các phân hệ chức năng | 37 | 

  CHƯƠNG 4. HIỆN THỰC, TRIỂN KHAI VÀ KIỂM THỬ HỆ THỐNG | 48 | 

  4.1. Môi trường phát triển và công nghệ lập trình | 48 | 

  4.2. Hiện thực mã nguồn các module then chốt | 50 | 

  4.3. Đóng gói Docker và triển khai thực tế trên Cloud (Render & TiDB) | 58 | 

  4.4. Kế hoạch và kết quả kiểm thử hệ thống | 61 | 

  KẾT LUẬN VÀ KIẾN NGHỊ | 68 | 

  DANH MỤC TÀI LIỆU THAM KHẢO | 71 | 

  PHỤ LỤC: PHIẾU ĐĂNG KÝ ĐỀ TÀI ĐỒ ÁN CHUYÊN NGÀNH | 73 | 

MỞ ĐẦU

# 
a. Lý do chọn đề tài (Tính cấp thiết của đề tài)

## 

  Trong bối cảnh chuyển đổi số và phát triển kinh tế số mạnh mẽ tại Việt Nam, thanh toán không dùng tiền mặt đã trở thành thói quen thiết yếu của người dân. Tuy nhiên, các hộ kinh doanh và cửa hàng trực tuyến quy mô nhỏ vẫn gặp nhiều khó khăn: chuyển khoản thủ công chậm trễ, nguy cơ bị lừa đảo bằng hình ảnh hóa đơn giả mạo (Fake Bill), trong khi các cổng thanh toán lớn (VNPay, MoMo Business...) đòi hỏi tư cách pháp nhân doanh nghiệp và chiết khấu phí cao (1.5% - 2.5%). Đề tài "Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam" được thực hiện nhằm giải quyết trọn vẹn các thách thức trên với chi phí 0đ và tự động hóa 100%.

b. Mục đích nghiên cứu

## 

  Xây dựng hệ sinh thái bán lẻ thực phẩm sạch FreshMart kết hợp cổng thanh toán FreshMart Pay, tự động sinh mã VietQR động theo cấu hình tài khoản ngân hàng của cửa hàng và tự động cập nhật đơn hàng qua Webhook trong vòng 2-3 giây.

c. Đối tượng và phạm vi nghiên cứu

## 

  Nghiên cứu chuẩn mã QR EMVCo của NAPAS, giao thức truyền nhận Webhook thời gian thực, kiến trúc phân tầng Layered Architecture và bảo mật ứng dụng web theo chuẩn OWASP Top 10.

CHƯƠNG 1. TỔNG QUAN VÀ KHẢO SÁT HIỆN TRẠNG

# 
1.1. Bối cảnh thanh toán điện tử tại Việt Nam

## 

  Thanh toán không dùng tiền mặt tại Việt Nam tăng trưởng vượt bậc, trong đó phương thức chuyển khoản nhanh qua mã VietQR (NAPAS 247) chiếm tỉ trọng áp đảo nhờ tính tiện lợi và phổ biến trên mọi ứng dụng ngân hàng di động.

1.2. Khảo sát hiện trạng và bài toán của cửa hàng nhỏ

## 

  Các cửa hàng nhỏ thường đối soát tiền bằng mắt thường, tốn 15-30 phút/đơn, dễ thất thoát và dễ bị lừa đảo bill chuyển tiền giả. Cổng thanh toán doanh nghiệp có rào cản pháp lý và chi phí cao, không phù hợp với các hộ kinh doanh cá thể.

1.3. Mô hình cửa hàng thực phẩm sạch FreshMart

## 

  FreshMart chuyên cung cấp thực phẩm tươi sống, cần giao nhanh trong 2 giờ. Việc tự động hóa khâu thu tiền và xác nhận đơn hàng giúp FreshMart tiết kiệm nhân lực, đóng gói hàng ngay lập tức và nâng cao trải nghiệm khách hàng.

CHƯƠNG 2. PHÂN TÍCH YÊU CẦU VÀ THIẾT KẾ KIẾN TRÚC HỆ THỐNG

# 
2.1. Phân tích yêu cầu chức năng (FR) và phi chức năng (NFR)

## 

  Hệ thống bao gồm các phân hệ chính: Đăng ký/Đăng nhập (FR01), Giỏ hàng động LocalStorage (FR02), Đặt hàng và tính phí ship (FR03), Sinh mã VietQR động theo chuẩn EMVCo (FR04), Xử lý Webhook tự động (FR05), Polling trạng thái đơn hàng (FR06), Dashboard doanh thu Chart.js (FR07), Cấu hình cổng ngân hàng thụ hưởng (FR08), và Quản lý phân quyền RBAC (FR09).

2.2. Kiến trúc phân tầng và mẫu thiết kế Adapter Pattern

## 

  Hệ thống áp dụng kiến trúc phân tầng 4 lớp (Presentation, REST API, Adapter Layer, Database Cloud). Lớp VietQRAdapter chuẩn hóa việc sinh mã QR và bóc tách Webhook, giúp hệ thống dễ dàng tích hợp thêm các ví điện tử MoMo, ZaloPay trong tương lai mà không phá vỡ logic cũ.

2.3. Giải pháp bảo mật an toàn thông tin

## 

  Mật khẩu băm một chiều Bcrypt với Salt ngẫu nhiên; khóa API Secret mã hóa đối xứng AES-256-CBC; 100% câu truy vấn CSDL dùng PDO Prepared Statements chống SQL Injection; dữ liệu xuất ra HTML được bọc qua htmlspecialchars() chống XSS.

CHƯƠNG 3. THIẾT KẾ CƠ SỞ DỮ LIỆU VÀ GIAO DIỆN NGƯỜI DÙNG

# 
3.1. Thiết kế cơ sở dữ liệu quan hệ (ERD)

## 

  CSDL chuẩn hóa gồm 5 bảng: NguoiDung (Quản lý tài khoản và vai trò), CuaHang (Thông tin shop), CongThanhToan (Cấu hình STK ngân hàng thụ hưởng), GiaoDich (Hóa đơn và trạng thái thanh toán), NhatKyWebhook (Nhật ký tín hiệu biến động số dư). Khóa chính UUID (36 ký tự) bảo vệ an toàn định danh.

3.2. Thiết kế giao diện chi tiết

## 

  Giao diện FreshMart mang phong cách xanh tươi mát (Emerald Green #10b981), responsive 100% trên điện thoại và máy tính. Trang thanh toán hiển thị mã VietQR sắc nét kèm đồng hồ đếm ngược 15 phút, nút sao chép 1-click và cơ chế tự động chuyển trạng thái khi tiền về. Dashboard quản trị tích hợp biểu đồ Chart.js và bộ lọc đơn hàng trực quan.

CHƯƠNG 4. HIỆN THỰC, TRIỂN KHAI VÀ KIỂM THỬ HỆ THỐNG

# 
4.1. Hiện thực mã nguồn các module cốt lõi

## 

  Module VietQRAdapter tự động tính toán mã kiểm tra CRC-16/CCITT và định dạng các khối dữ liệu Tag-Length-Value theo chuẩn NAPAS. Module Webhook kiểm tra điều kiện Idempotency nghiêm ngặt (chỉ cập nhật đơn hàng có trạng thái PENDING) giúp chống việc cộng tiền trùng lặp khi mạng bị chập chờn.

4.2. Đóng gói Docker và triển khai thực tế trên Cloud

## 

  Hệ thống được đóng gói container Docker (PHP 8.2 Apache) và triển khai tự động CI/CD trên Render Cloud tại URL: https://doan-yteb.onrender.com, kết nối an toàn với cơ sở dữ liệu TiDB Cloud Serverless qua chứng chỉ SSL/TLS.

4.3. Kế hoạch và kết quả kiểm thử

## 

  Chương trình kiểm thử tự động php/tests/run_smoke_tests.php đạt kết quả 100% Pass trên tất cả 6 bài test bảo mật và chức năng lõi. 10/10 ca kiểm thử chức năng (Functional Test) và kiểm thử Webhook thực tế đều thành công mỹ mãn.

KẾT LUẬN VÀ KIẾN NGHỊ

# 

  Đồ án đã giải quyết thành công bài toán thanh toán tự động không tiền mặt cho các shop nhỏ tại Việt Nam với chi phí 0đ, xóa bỏ hoàn toàn rủi ro bị lừa đảo bill chuyển tiền giả mạo. Trong tương lai, nhóm dự kiến phát triển thêm ứng dụng di động Mobile App thông báo chuông "Ting ting" khi có tiền về và đóng gói thành Plugin SaaS cho các nền tảng thương mại điện tử.

DANH MỤC TÀI LIỆU THAM KHẢO

# 

  NAPAS. (2021). Tiêu chuẩn kỹ thuật mã phản hồi nhanh (QR Code) phục vụ thanh toán tại Việt Nam. Hà Nội: NXB Bưu điện.
- 
  EMVCo. (2020). EMV® QR Code Specification for Payment Systems: Consumer-Presented Mode (Version 1.1).
- 
  Gamma, E., et al. (1994). Design Patterns: Elements of Reusable Object-Oriented Software. Addison-Wesley.
- 
  Ngân hàng Nhà nước Việt Nam. (2023). Báo cáo thanh toán không dùng tiền mặt giai đoạn 2021–2025.
- 
  Nguyen, H. T., & Tran, D. M. (2022). Adoption of Mobile Payment in Vietnamese Retail SMEs. JAFEB, 9(4), 315–326.
- 
  OWASP Foundation. (2021). OWASP Top 10: Critical Web Application Security Risks.
- 
  Sklar, D., & Trachtenberg, A. (2019). PHP Cookbook (3rd ed.). O'Reilly Media.
- 
  TiDB Documentation Team. (2023). TiDB Architecture and Cloud Best Practices. PingCAP.
- 

PHỤ LỤC: PHIẾU ĐĂNG KÝ ĐỀ TÀI

# 
Đề tài: Thiết kế và triển khai hệ thống thanh toán đa cổng thông minh tích hợp VietQR & ví điện tử cho cửa hàng trực tuyến quy mô nhỏ tại Việt Nam.

Sinh viên thực hiện: Đinh Thế Chí Dũng (25002710) & Nguyễn Tuấn Hưng (25002711) - Lớp 25C2-LMT1 - Khóa 2025.

Giảng viên hướng dẫn: ThS. Mai Chiếm Tuấn - Khoa Công nghệ Thông tin - Trường Cao đẳng Lý Tự Trọng TP.HCM.