<?php
/**
 * =====================================================
 * FILE: dashboard.php
 * Mục đích: Bảng điều khiển đầy đủ - thống kê + danh sách giao dịch + biểu đồ
 * Tuần 9 - Hệ thống thanh toán đa cổng FreshMart
 * =====================================================
 */
require_once __DIR__ . '/includes/auth.php';
requireLogin('html/login.html');
if (!isPrivileged()) {
    header('Location: html/account.html');
    exit;
}

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | FreshMart Payment</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/all.min.css">
  <script src="js/chart.umd.min.js"></script>
  <style>
    body { background: #f5f7fa; }
    .sidebar { width: 250px; min-height: 100vh; background: #1e293b; color: #fff; position: fixed; }
    .sidebar a { color: #cbd5e1; text-decoration: none; display: block; padding: 12px 20px; }
    .sidebar a:hover, .sidebar a.active { background: #334155; color: #fff; }
    .content { margin-left: 250px; padding: 30px; }
    .card-stat { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); transition: .2s; }
    .card-stat:hover { transform: translateY(-2px); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
    .badge-pending { background: #f59e0b; }
    .badge-success { background: #10b981; }
    .badge-failed { background: #ef4444; }
    .table th { font-weight: 600; font-size: 0.85rem; color: #64748b; }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="p-3 border-bottom border-secondary">
      <h5 class="mb-0"><i class="fas fa-leaf me-2 text-success"></i>FreshMart Pay</h5>
      <small class="text-muted"><?= htmlspecialchars($user['ten_ch'] ?: 'Cửa hàng') ?></small>
    </div>
    <nav class="mt-3">
      <a href="dashboard.php" class="active"><i class="fas fa-home me-2"></i> Tổng quan</a>
      <a href="admin/gateways.php"><i class="fas fa-credit-card me-2"></i> Cổng thanh toán</a>
      <a href="transactions.php"><i class="fas fa-exchange-alt me-2"></i> Giao dịch</a>
      <a href="reports.php"><i class="fas fa-chart-bar me-2"></i> Báo cáo</a>
      <hr class="border-secondary mx-3 my-2">
      <a href="html/index.html" style="background: rgba(16, 185, 129, 0.15); color: #10b981; font-weight: 600; border-radius: 8px; margin: 8px 12px; display: flex; align-items: center; padding: 10px 16px; text-decoration: none;">
        <i class="fas fa-shopping-basket me-2"></i> FreshMart (Trang chủ)
      </a>
      <a href="api/logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a>
    </nav>
  </div>

  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="mb-1">Xin chào, <?= htmlspecialchars($user['ho_ten']) ?>!</h4>
        <p class="text-muted mb-0">Vai trò: <strong><?= htmlspecialchars($user['vai_tro']) ?></strong></p>
      </div>
      <button class="btn btn-outline-primary btn-sm" onclick="loadData()">
        <i class="fas fa-sync-alt me-1"></i> Làm mới
      </button>
    </div>

    <!-- Thống kê nhanh -->
    <div class="row g-3 mb-4" id="statsRow">
      <div class="col-md-3">
        <div class="card card-stat p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-coins"></i></div>
            <div>
              <div class="text-muted small">Doanh thu hôm nay</div>
              <h4 class="mb-0" id="statToday">0 ₫</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-stat p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-check-circle"></i></div>
            <div>
              <div class="text-muted small">Thành công</div>
              <h4 class="mb-0" id="statSuccess">0</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-stat p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-clock"></i></div>
            <div>
              <div class="text-muted small">Đang chờ</div>
              <h4 class="mb-0" id="statPending">0</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-stat p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="fas fa-times-circle"></i></div>
            <div>
              <div class="text-muted small">Thất bại</div>
              <h4 class="mb-0" id="statFailed">0</h4>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Biểu đồ + Bộ lọc -->
    <div class="row g-3 mb-4">
      <div class="col-lg-8">
        <div class="card card-stat p-4">
          <h6 class="mb-3">Doanh thu 7 ngày gần nhất</h6>
          <canvas id="revenueChart" height="120"></canvas>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card card-stat p-4 h-100">
          <h6 class="mb-3">Bộ lọc giao dịch</h6>
          <div class="mb-2">
            <label class="form-label small">Trạng thái</label>
            <select id="filterStatus" class="form-select form-select-sm">
              <option value="all">Tất cả</option>
              <option value="PENDING">Đang chờ</option>
              <option value="SUCCESS">Thành công</option>
              <option value="FAILED">Thất bại</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label small">Cổng thanh toán</label>
            <select id="filterGateway" class="form-select form-select-sm">
              <option value="all">Tất cả</option>
              <option value="VIETQR">VietQR</option>
              <option value="MOMO">MoMo</option>
              <option value="VNPAY">VNPay</option>
              <option value="ZALOPAY">ZaloPay</option>
            </select>
          </div>
          <button class="btn btn-primary btn-sm w-100 mt-2" onclick="loadData()">
            <i class="fas fa-filter me-1"></i> Lọc
          </button>
        </div>
      </div>
    </div>

    <!-- Danh sách giao dịch -->
    <div class="card card-stat p-4" id="transactions">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Danh sách giao dịch</h6>
        <span class="text-muted small" id="totalCount">0 giao dịch</span>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Mã đơn</th>
              <th>Số tiền</th>
              <th>Cổng</th>
              <th>Trạng thái</th>
              <th>Thời gian tạo</th>
              <th>Thanh toán lúc</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="paymentTable">
            <tr><td colspan="7" class="text-center text-muted">Đang tải...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Form giả lập webhook (hữu ích khi demo) -->
    <div class="card card-stat p-4 mt-4">
      <h6 class="mb-3"><i class="fas fa-flask me-2 text-warning"></i>Giả lập Webhook (Test)</h6>
      <p class="text-muted small">Dùng khi muốn test cập nhật trạng thái mà không cần webhook thật từ ngân hàng.</p>
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" id="simOrderId" class="form-control form-control-sm" placeholder="Mã đơn hàng (FM...)">
        </div>
        <div class="col-md-3">
          <input type="number" id="simAmount" class="form-control form-control-sm" placeholder="Số tiền">
        </div>
        <div class="col-md-3">
          <select id="simStatus" class="form-select form-select-sm">
            <option value="SUCCESS">SUCCESS</option>
            <option value="FAILED">FAILED</option>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-warning btn-sm w-100" onclick="simulateWebhook()">
            Gửi Webhook
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let chart = null;

    // Format tiền VND
    function formatMoney(n) {
      return Number(n).toLocaleString('vi-VN') + ' ₫';
    }

    // Badge trạng thái
    function statusBadge(st) {
      const map = {
        'PENDING': '<span class="badge badge-pending">PENDING</span>',
        'SUCCESS': '<span class="badge badge-success">SUCCESS</span>',
        'FAILED':  '<span class="badge badge-failed">FAILED</span>'
      };
      return map[st] || `<span class="badge bg-secondary">${st}</span>`;
    }

    // Load dữ liệu từ API
    async function loadData() {
      const status = document.getElementById('filterStatus').value;
      const gateway = document.getElementById('filterGateway').value;

      const res = await fetch(`api/payments.php?trang_thai=${status}&loai_cong=${gateway}&limit=30`);
      const json = await res.json();

      if (!json.success) {
        alert(json.message);
        return;
      }

      const { stats, chart: chartData, total, payments } = json.data;

      // Cập nhật thống kê
      document.getElementById('statToday').textContent = formatMoney(stats.doanh_thu_hom_nay);
      document.getElementById('statSuccess').textContent = stats.thanh_cong;
      document.getElementById('statPending').textContent = stats.dang_cho;
      document.getElementById('statFailed').textContent = stats.that_bai;
      document.getElementById('totalCount').textContent = total + ' giao dịch';

      // Cập nhật bảng
      const tbody = document.getElementById('paymentTable');
      if (payments.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Chưa có giao dịch nào</td></tr>';
      } else {
        tbody.innerHTML = payments.map(p => `
          <tr>
            <td><code>${p.MaDonHang}</code></td>
            <td class="fw-semibold">${formatMoney(p.SoTien)}</td>
            <td>${p.LoaiCongThanhToan}</td>
            <td>${statusBadge(p.TrangThai)}</td>
            <td class="small">${p.ThoiGianTao}</td>
            <td class="small">${p.ThoiGianThanhToan || '-'}</td>
            <td>
              ${p.TrangThai === 'PENDING' ? `
                <button class="btn btn-outline-success btn-sm" 
                  onclick="document.getElementById('simOrderId').value='${p.MaDonHang}';
                           document.getElementById('simAmount').value='${p.SoTien}';
                           document.getElementById('simStatus').value='SUCCESS';">
                  Xác nhận
                </button>` : ''}
            </td>
          </tr>
        `).join('');
      }

      // Cập nhật biểu đồ
      const labels = chartData.map(d => d.label);
      const values = chartData.map(d => d.doanh_thu);

      if (chart) chart.destroy();
      chart = new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Doanh thu (₫)',
            data: values,
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('vi-VN') } }
          }
        }
      });
    }

    // Giả lập webhook
    async function simulateWebhook() {
      const orderId = document.getElementById('simOrderId').value.trim();
      const amount  = document.getElementById('simAmount').value;
      const status  = document.getElementById('simStatus').value;

      if (!orderId) {
        alert('Vui lòng nhập mã đơn hàng');
        return;
      }

      const res = await fetch('api/simulate_webhook.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ma_don_hang: orderId, so_tien: amount, status })
      });
      const json = await res.json();
      alert(json.message);
      if (json.success) loadData();
    }

    // Load lần đầu
    loadData();
  </script>
</body>
</html>
