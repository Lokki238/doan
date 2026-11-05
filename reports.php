<?php
/**
 * =====================================================
 * FILE: reports.php
 * Mục đích: Trang Báo cáo & Phân tích Doanh thu đa cổng
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
  <title>Báo cáo & Thống kê | FreshMart Pay</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/all.min.css">
  <style>
    body { background: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
    .sidebar { width: 250px; min-height: 100vh; background: #0f172a; color: #fff; flex-shrink: 0; }
    .sidebar a { color: #94a3b8; text-decoration: none; display: flex; align-items: center; padding: 12px 20px; transition: all .2s; }
    .sidebar a:hover, .sidebar a.active { background: #1e293b; color: #fff; }
    .sidebar a.freshmart-home { background: rgba(16, 185, 129, 0.1); color: #10b981; font-weight: 600; border-radius: 8px; margin: 8px 12px; }
    .sidebar a.freshmart-home:hover { background: #10b981; color: #fff; }
    .content { flex: 1; padding: 25px 35px; overflow-x: auto; }
    .card-stat { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.05); background: #fff; }
    .kpi-title { font-size: 0.85rem; color: #64748b; font-weight: 500; }
    .kpi-value { font-size: 1.5rem; font-weight: 700; color: #0f172a; }
    @media print {
      .sidebar, .no-print { display: none !important; }
      .content { padding: 0 !important; }
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
      <div class="p-3 border-bottom border-secondary">
        <h5 class="mb-0"><i class="fas fa-leaf me-2 text-success"></i>FreshMart Pay</h5>
        <small class="text-muted"><?= htmlspecialchars($user['ten_ch'] ?? 'Cửa hàng') ?></small>
      </div>
      <nav class="mt-3 flex-grow-1">
        <a href="dashboard.php"><i class="fas fa-home me-2"></i> Tổng quan</a>
        <a href="admin/gateways.php"><i class="fas fa-credit-card me-2"></i> Cổng thanh toán</a>
        <a href="transactions.php"><i class="fas fa-exchange-alt me-2"></i> Giao dịch</a>
        <a href="reports.php" class="active"><i class="fas fa-chart-bar me-2"></i> Báo cáo</a>
        <a href="permissions.php"><i class="fas fa-user-shield me-2"></i> Cấp quyền</a>
        <hr class="border-secondary mx-3 my-2">
        <!-- Nút về trang chủ FreshMart -->
        <a href="html/index.html" class="freshmart-home">
          <i class="fas fa-shopping-basket me-2"></i> FreshMart (Trang chủ)
        </a>
      </nav>
      <div class="p-3 border-top border-secondary">
        <div class="small text-muted mb-1"><?= htmlspecialchars($user['ho_ten'] ?? $user['email']) ?></div>
        <a href="api/logout.php" class="p-0 text-danger"><i class="fas fa-sign-out-alt me-1"></i> Đăng xuất</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="content">
      <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
          <h4 class="fw-bold mb-1">Báo cáo & Phân tích Doanh thu</h4>
          <p class="text-muted small mb-0">Theo dõi hiệu suất kinh doanh và dòng tiền thanh toán trực tuyến</p>
        </div>
        <div>
          <button class="btn btn-outline-secondary btn-sm me-2" onclick="window.print()">
            <i class="fas fa-print me-1"></i> In báo cáo
          </button>
        </div>
      </div>

      <!-- Thanh chọn mốc thời gian -->
      <div class="card card-stat p-3 mb-4 no-print">
        <div class="d-flex flex-wrap gap-2 align-items-center">
          <span class="small fw-semibold text-muted me-2">Thời gian:</span>
          <button class="btn btn-sm btn-outline-primary active time-btn" data-range="7days" onclick="selectRange('7days', this)">7 ngày qua</button>
          <button class="btn btn-sm btn-outline-primary time-btn" data-range="today" onclick="selectRange('today', this)">Hôm nay</button>
          <button class="btn btn-sm btn-outline-primary time-btn" data-range="30days" onclick="selectRange('30days', this)">30 ngày qua</button>
          <button class="btn btn-sm btn-outline-primary time-btn" data-range="this_month" onclick="selectRange('this_month', this)">Tháng này</button>
          <button class="btn btn-sm btn-outline-primary time-btn" data-range="last_month" onclick="selectRange('last_month', this)">Tháng trước</button>
          <button class="btn btn-sm btn-outline-primary time-btn" data-range="this_year" onclick="selectRange('this_year', this)">Năm nay</button>
        </div>
      </div>

      <!-- 4 Thẻ KPI nổi bật -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="kpi-title">TỔNG DOANH THU</div>
                <div class="kpi-value text-success mt-1" id="kpiRevenue">0 ₫</div>
              </div>
              <div class="p-3 bg-success bg-opacity-10 text-success rounded-3"><i class="fas fa-wallet fa-lg"></i></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="kpi-title">ĐƠN THÀNH CÔNG</div>
                <div class="kpi-value mt-1" id="kpiSuccessOrders">0</div>
              </div>
              <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3"><i class="fas fa-check-double fa-lg"></i></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="kpi-title">TỶ LỆ THÀNH CÔNG</div>
                <div class="kpi-value text-info mt-1" id="kpiSuccessRate">0%</div>
              </div>
              <div class="p-3 bg-info bg-opacity-10 text-info rounded-3"><i class="fas fa-percentage fa-lg"></i></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="kpi-title">GIÁ TRỊ ĐƠN TB (AOV)</div>
                <div class="kpi-value mt-1" id="kpiAOV">0 ₫</div>
              </div>
              <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3"><i class="fas fa-receipt fa-lg"></i></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Biểu đồ phân tích -->
      <div class="row g-3 mb-4">
        <!-- Biểu đồ doanh thu theo thời gian -->
        <div class="col-lg-8">
          <div class="card card-stat p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold mb-0">Xu hướng Doanh thu</h6>
              <small class="text-muted" id="timelineRangeText"></small>
            </div>
            <canvas id="timelineChart" height="130"></canvas>
          </div>
        </div>

        <!-- Biểu đồ tròn cơ cấu cổng thanh toán -->
        <div class="col-lg-4">
          <div class="card card-stat p-4 h-100">
            <h6 class="fw-bold mb-3">Tỷ trọng Cổng thanh toán</h6>
            <div style="max-height: 220px; position: relative;">
              <canvas id="gatewayChart"></canvas>
            </div>
            <div id="gatewayLegend" class="mt-3 small"></div>
          </div>
        </div>
      </div>

      <!-- Bảng thống kê chi tiết theo ngày -->
      <div class="card card-stat p-4">
        <h6 class="fw-bold mb-3">Chi tiết doanh thu theo từng ngày</h6>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr class="small text-muted">
                <th>NGÀY</th>
                <th>SỐ ĐƠN TẠO</th>
                <th>ĐƠN THÀNH CÔNG</th>
                <th>TỶ LỆ</th>
                <th class="text-end">DOANH THU THỰC NHẬN</th>
              </tr>
            </thead>
            <tbody id="dailyTableBody">
              <tr><td colspan="5" class="text-center py-3 text-muted">Đang tải số liệu...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="js/chart.umd.min.js"></script>
  <script>
    let currentRange = '7days';
    let timelineChartInstance = null;
    let gatewayChartInstance = null;

    function formatMoney(n) {
      return Number(n || 0).toLocaleString('vi-VN') + ' ₫';
    }

    function selectRange(range, btn) {
      document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentRange = range;
      loadReportData();
    }

    async function loadReportData() {
      try {
        const res = await fetch(`api/reports.php?range=${currentRange}`);
        const json = await res.json();

        if (!json.success) {
          alert(json.message);
          return;
        }

        const data = json.data;
        const kpi = data.kpi;

        // Điền KPIs
        document.getElementById('kpiRevenue').textContent = formatMoney(kpi.tong_doanh_thu);
        document.getElementById('kpiSuccessOrders').textContent = `${kpi.don_thanh_cong} / ${kpi.tong_don} đơn`;
        document.getElementById('kpiSuccessRate').textContent = `${kpi.ty_le_thanh_cong}%`;
        document.getElementById('kpiAOV').textContent = formatMoney(kpi.gia_tri_trung_binh);
        document.getElementById('timelineRangeText').textContent = `${data.start_date} đến ${data.end_date}`;

        // 1. Vẽ Biểu đồ Doanh thu (Timeline Chart)
        const labels = data.timeline.map(t => t.label);
        const revenues = data.timeline.map(t => t.doanh_thu);

        if (timelineChartInstance) timelineChartInstance.destroy();
        timelineChartInstance = new Chart(document.getElementById('timelineChart'), {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Doanh thu (₫)',
              data: revenues,
              backgroundColor: 'rgba(16, 185, 129, 0.75)',
              borderColor: '#10b981',
              borderWidth: 1,
              borderRadius: 6
            }]
          },
          options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
              y: {
                beginAtZero: true,
                ticks: { callback: v => Number(v).toLocaleString('vi-VN') + ' ₫' }
              }
            }
          }
        });

        // 2. Vẽ Biểu đồ Cổng thanh toán (Gateway Donut Chart)
        const gwLabels = data.gateways.length > 0 ? data.gateways.map(g => g.loai_cong) : ['Chưa có'];
        const gwRevenues = data.gateways.length > 0 ? data.gateways.map(g => g.doanh_thu) : [1];
        const gwColors = ['#10b981', '#3b82f6', '#f59e0b', '#ec4899'];

        if (gatewayChartInstance) gatewayChartInstance.destroy();
        gatewayChartInstance = new Chart(document.getElementById('gatewayChart'), {
          type: 'doughnut',
          data: {
            labels: gwLabels,
            datasets: [{
              data: gwRevenues,
              backgroundColor: gwColors,
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
          }
        });

        // 3. Render Bảng chi tiết theo ngày
        const dailyTbody = document.getElementById('dailyTableBody');
        const reversedTimeline = [...data.timeline].reverse();

        if (reversedTimeline.length === 0) {
          dailyTbody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-muted">Không có dữ liệu trong khoảng thời gian này</td></tr>';
        } else {
          dailyTbody.innerHTML = reversedTimeline.map(row => {
            const rate = row.tong_don > 0 ? Math.round((row.thanh_cong / row.tong_don) * 100) : 0;
            return `
              <tr>
                <td><b>${row.ngay}</b> (${row.label})</td>
                <td>${row.tong_don}</td>
                <td><span class="badge bg-success bg-opacity-10 text-success">${row.thanh_cong}</span></td>
                <td>${rate}%</td>
                <td class="text-end fw-bold text-dark">${formatMoney(row.doanh_thu)}</td>
              </tr>
            `;
          }).join('');
        }

      } catch (err) {
        console.error('Lỗi tải báo cáo:', err);
      }
    }

    loadReportData();
  </script>
</body>
</html>
