<?php
/**
 * =====================================================
 * FILE: admin/gateways.php
 * Mục đích: Trang quản trị cấu hình cổng thanh toán
 * Tuần 8 - Chỉ Owner mới được vào
 * =====================================================
 */
require_once __DIR__ . '/../includes/auth.php';
requireOwner('../html/login.html'); // Chỉ Owner

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cấu hình cổng thanh toán | FreshMart</title>
  <link href="../../css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/all.min.css">
  <style>
    body { background: #f5f7fa; }
    .sidebar { width: 250px; min-height: 100vh; background: #1e293b; color: #fff; }
    .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: 12px 20px; }
    .sidebar a:hover, .sidebar a.active { background: #334155; color: #fff; }
    .content { flex: 1; padding: 30px; }
    .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
  </style>
</head>
<body>
  <div class="d-flex">
    <div class="sidebar">
      <div class="p-3 border-bottom border-secondary">
        <h5 class="mb-0"><i class="fas fa-leaf me-2 text-success"></i>FreshMart Pay</h5>
        <small class="text-muted"><?= htmlspecialchars($user['ten_ch']) ?></small>
      </div>
      <nav class="mt-3">
        <a href="../dashboard.php"><i class="fas fa-home me-2"></i> Tổng quan</a>
        <a href="gateways.php" class="active"><i class="fas fa-credit-card me-2"></i> Cổng thanh toán</a>
        <a href="../transactions.php"><i class="fas fa-exchange-alt me-2"></i> Giao dịch</a>
        <a href="../reports.php"><i class="fas fa-chart-bar me-2"></i> Báo cáo</a>
        <a href="../permissions.php"><i class="fas fa-user-shield me-2"></i> Cấp quyền</a>
        <hr class="border-secondary mx-3 my-2">
        <a href="../../html/index.html" style="background: rgba(16, 185, 129, 0.15); color: #10b981; font-weight: 600; border-radius: 8px; margin: 8px 12px; display: flex; align-items: center; padding: 10px 16px; text-decoration: none;">
          <i class="fas fa-shopping-basket me-2"></i> FreshMart (Trang chủ)
        </a>
        <a href="../api/logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a>
      </nav>
    </div>

    <div class="content">
      <h4 class="mb-4">Cấu hình cổng thanh toán</h4>

      <div id="alertBox" class="alert d-none"></div>

      <!-- Form cấu hình VietQR (ưu tiên) -->
      <div class="card p-4 mb-4">
        <h5 class="mb-3"><i class="fas fa-qrcode me-2 text-primary"></i>VietQR (Ưu tiên)</h5>
        <form id="formVietQR">
          <input type="hidden" name="loai_cong" value="VIETQR">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Số tài khoản nhận tiền *</label>
              <input type="text" name="ma_nguoi_ban" class="form-control" placeholder="VD: 0123456789" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Mã ngân hàng (BIN / Code)</label>
              <select name="bank_code" class="form-select">
                <option value="VCB">Vietcombank (VCB)</option>
                <option value="TCB">Techcombank (TCB)</option>
                <option value="MB">MB Bank</option>
                <option value="ACB">ACB</option>
                <option value="BIDV">BIDV</option>
                <option value="VPB">VPBank</option>
                <option value="TPB">TPBank</option>
                <option value="STB">Sacombank</option>
                <option value="VIB">VIB</option>
                <option value="MSB">MSB</option>
                <option value="VBA">Agribank</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tên chủ tài khoản</label>
              <input type="text" name="account_name" class="form-control" placeholder="NGUYEN VAN A">
            </div>
            <div class="col-md-6">
              <label class="form-label">Trạng thái</label>
              <select name="trang_thai" class="form-select">
                <option value="1">Đang hoạt động</option>
                <option value="0">Tạm tắt</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary mt-3">
            <i class="fas fa-save me-1"></i> Lưu cấu hình VietQR
          </button>
        </form>
      </div>

      <!-- Form tạo thanh toán thử nghiệm -->
      <div class="card p-4">
        <h5 class="mb-3"><i class="fas fa-flask me-2 text-warning"></i>Tạo thanh toán thử (Test)</h5>
        <form id="formCreatePayment">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Số tiền (VND)</label>
              <input type="number" name="so_tien" class="form-control" value="50000" min="1000" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Mã đơn hàng (Tiền tố FM theo SePay)</label>
              <input type="text" name="ma_don_hang" id="maDonHangInput" class="form-control" value="FM<?= date('dHis') . rand(10, 99) ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Mô tả</label>
              <input type="text" name="mo_ta" class="form-control" placeholder="Thanh toan don hang">
            </div>
          </div>
          <input type="hidden" name="loai_cong" value="VIETQR">
          <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-qrcode me-1"></i> Tạo mã VietQR
          </button>
        </form>

        <!-- Kết quả QR sẽ hiện ở đây -->
        <div id="qrResult" class="mt-4 d-none">
          <hr>
          <h6>Kết quả:</h6>
          <div id="qrInfo" class="mb-3"></div>
          <div id="qrcode" class="d-inline-block p-3 bg-white border rounded"></div>
          <p class="text-muted small mt-2">Quét mã bằng app ngân hàng để test (sandbox/real tùy cấu hình TK)</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Thư viện vẽ QR code phía client -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
    // ===== Lưu cấu hình VietQR =====
    document.getElementById('formVietQR').addEventListener('submit', async function(e) {
      e.preventDefault();
      const form = this;
      const data = {
        loai_cong: form.loai_cong.value,
        ma_nguoi_ban: form.ma_nguoi_ban.value,
        trang_thai: form.trang_thai.value,
        cau_hinh_them: {
          bank_code: form.bank_code.value,
          account_name: form.account_name.value
        }
      };

      const res = await fetch('../api/gateways.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const json = await res.json();
      showAlert(json.success, json.message);
    });

    // ===== Tạo thanh toán thử =====
    document.getElementById('formCreatePayment').addEventListener('submit', async function(e) {
      e.preventDefault();
      const form = this;
      const data = {
        so_tien: form.so_tien.value,
        ma_don_hang: form.ma_don_hang.value,
        mo_ta: form.mo_ta.value,
        loai_cong: 'VIETQR'
      };

      const res = await fetch('../api/create_payment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const json = await res.json();

      if (json.success) {
        const formQR = document.getElementById('formVietQR');
        const selectedBankText = formQR.bank_code.options[formQR.bank_code.selectedIndex].text;
        const info = json.data.adapter_info || {};

        document.getElementById('qrResult').classList.remove('d-none');
        document.getElementById('qrInfo').innerHTML = `
          <div class="alert alert-info py-2 mb-2">
            <div><strong>Ngân hàng nhận:</strong> <span class="text-primary fw-bold">${selectedBankText}</span></div>
            <div><strong>Chủ tài khoản:</strong> <b>${info.account_name || formQR.account_name.value}</b></div>
            <div><strong>Số tài khoản:</strong> <code>${info.account_no || formQR.ma_nguoi_ban.value}</code></div>
            <div><strong>Mã đơn hàng:</strong> <code>${json.data.ma_don_hang}</code></div>
            <div><strong>Số tiền:</strong> ${Number(json.data.so_tien).toLocaleString()} ₫</div>
            <div><strong>Cổng:</strong> VietQR (NAPAS)</div>
            <div><strong>Trạng thái:</strong> <span class="badge bg-warning text-dark">PENDING (Chờ thanh toán)</span></div>
          </div>
        `;
        // Vẽ QR
        document.getElementById('qrcode').innerHTML = '';
        new QRCode(document.getElementById('qrcode'), {
          text: json.data.qr_data,
          width: 220,
          height: 220
        });
        // Tự động sinh mã mới cho lần tạo kế tiếp để tránh trùng lặp
        const now = new Date();
        const pad = (n) => String(n).padStart(2, '0');
        const dHis = pad(now.getDate()) + pad(now.getHours()) + pad(now.getMinutes()) + pad(now.getSeconds());
        const rand = Math.floor(10 + Math.random() * 90);
        form.ma_don_hang.value = 'FM' + dHis + rand;

        showAlert(true, 'Tạo mã QR thành công!');
      } else {
        showAlert(false, json.message);
      }
    });

    // ===== Tự động tải cấu hình cổng đã lưu khi mở trang =====
    async function loadCurrentGateway() {
      try {
        const res = await fetch('../api/gateways.php');
        const json = await res.json();
        const list = (json.data && json.data.gateways) || (Array.isArray(json.data) ? json.data : []);
        if (json.success && list && list.length > 0) {
          const vietqr = list.find(g => g.LoaiCongThanhToan === 'VIETQR');
          if (vietqr) {
            const form = document.getElementById('formVietQR');
            if (vietqr.MaNguoiBan) form.ma_nguoi_ban.value = vietqr.MaNguoiBan;
            if (vietqr.TrangThaiHoatDong !== undefined) form.trang_thai.value = String(vietqr.TrangThaiHoatDong);
            if (vietqr.CauHinhThem) {
              if (vietqr.CauHinhThem.bank_code) form.bank_code.value = vietqr.CauHinhThem.bank_code;
              if (vietqr.CauHinhThem.account_name) form.account_name.value = vietqr.CauHinhThem.account_name;
            }
          }
        }
      } catch (err) {
        console.error('Lỗi tải cấu hình cổng:', err);
      }
    }
    loadCurrentGateway();

    function showAlert(success, message) {
      const box = document.getElementById('alertBox');
      box.className = 'alert ' + (success ? 'alert-success' : 'alert-danger');
      box.textContent = message;
      box.classList.remove('d-none');
      setTimeout(() => box.classList.add('d-none'), 4000);
    }
  </script>
</body>
</html>
