<?php
/**
 * =====================================================
 * FILE: transactions.php
 * Mục đích: Quản lý danh sách giao dịch, lọc, tìm kiếm và chi tiết
 * =====================================================
 */
require_once __DIR__ . '/includes/auth.php';
requireLogin('html/login.html');

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Giao dịch | FreshMart Pay</title>
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
    .badge-pending { background: #fef3c7; color: #d97706; font-weight: 500; padding: 6px 10px; }
    .badge-success { background: #d1fae5; color: #059669; font-weight: 500; padding: 6px 10px; }
    .badge-failed { background: #fee2e2; color: #dc2626; font-weight: 500; padding: 6px 10px; }
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
        <a href="transactions.php" class="active"><i class="fas fa-exchange-alt me-2"></i> Giao dịch</a>
        <a href="reports.php"><i class="fas fa-chart-bar me-2"></i> Báo cáo</a>
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
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="fw-bold mb-1">Quản lý Giao dịch</h4>
          <p class="text-muted small mb-0">Theo dõi toàn bộ lịch sử thanh toán đa cổng của cửa hàng</p>
        </div>
        <div>
          <button class="btn btn-outline-success btn-sm me-2" onclick="exportCSV()">
            <i class="fas fa-file-excel me-1"></i> Xuất CSV
          </button>
          <a href="admin/gateways.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tạo giao dịch mới
          </a>
        </div>
      </div>

      <!-- Thống kê nhanh 4 thẻ -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <span class="text-muted small">Tổng giao dịch</span>
            <h4 class="fw-bold mb-0 mt-1" id="statTotalCount">0</h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <span class="text-muted small">Doanh thu thành công</span>
            <h4 class="fw-bold text-success mb-0 mt-1" id="statTotalRevenue">0 ₫</h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <span class="text-muted small">Đang chờ (PENDING)</span>
            <h4 class="fw-bold text-warning mb-0 mt-1" id="statPendingCount">0</h4>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card card-stat p-3">
            <span class="text-muted small">Thất bại / Hủy</span>
            <h4 class="fw-bold text-danger mb-0 mt-1" id="statFailedCount">0</h4>
          </div>
        </div>
      </div>

      <!-- Thanh công cụ lọc & tìm kiếm -->
      <div class="card card-stat p-3 mb-4">
        <div class="row g-2 align-items-center">
          <div class="col-md-3">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
              <input type="text" id="searchInput" class="form-control" placeholder="Tìm mã đơn (FM...), mô tả...">
            </div>
          </div>
          <div class="col-md-2">
            <select id="filterStatus" class="form-select form-select-sm">
              <option value="all">Tất cả trạng thái</option>
              <option value="SUCCESS">Thành công (SUCCESS)</option>
              <option value="PENDING">Đang chờ (PENDING)</option>
              <option value="FAILED">Thất bại (FAILED)</option>
            </select>
          </div>
          <div class="col-md-2">
            <select id="filterGateway" class="form-select form-select-sm">
              <option value="all">Tất cả cổng</option>
              <option value="VIETQR">VietQR</option>
              <option value="MOMO">MoMo</option>
              <option value="VNPAY">VNPay</option>
              <option value="ZALOPAY">ZaloPay</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="date" id="filterFromDate" class="form-control form-control-sm" title="Từ ngày">
          </div>
          <div class="col-md-2">
            <input type="date" id="filterToDate" class="form-control form-control-sm" title="Đến ngày">
          </div>
          <div class="col-md-1">
            <button class="btn btn-primary btn-sm w-100" onclick="loadTransactions(0)">
              Lọc
            </button>
          </div>
        </div>
      </div>

      <!-- Bảng giao dịch -->
      <div class="card card-stat p-4">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr class="small text-muted">
                <th>MÃ ĐƠN HÀNG</th>
                <th>SỐ TIỀN</th>
                <th>CỔNG THANH TOÁN</th>
                <th>TRẠNG THÁI</th>
                <th>MÃ ĐỐI SOÁT</th>
                <th>THỜI GIAN TẠO</th>
                <th>THANH TOÁN LÚC</th>
                <th class="text-end">THAO TÁC</th>
              </tr>
            </thead>
            <tbody id="transactionTbody">
              <tr><td colspan="8" class="text-center py-4 text-muted">Đang tải dữ liệu...</td></tr>
            </tbody>
          </table>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
          <small class="text-muted" id="pageInfo">Hiển thị 0 - 0 của 0 giao dịch</small>
          <div class="btn-group btn-group-sm" id="paginationBtns">
            <button class="btn btn-outline-secondary" id="btnPrev" onclick="changePage(-1)" disabled>Trước</button>
            <button class="btn btn-outline-secondary" id="btnNext" onclick="changePage(1)" disabled>Sau</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Chi tiết giao dịch -->
  <div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Chi tiết giao dịch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalDetailContent">
          Đang tải...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script>
    let currentOffset = 0;
    const limit = 20;
    let allCurrentList = [];

    function formatMoney(n) {
      return Number(n || 0).toLocaleString('vi-VN') + ' ₫';
    }

    function statusBadge(st) {
      if (st === 'SUCCESS') return '<span class="badge badge-success"><i class="fas fa-check-circle me-1"></i>SUCCESS</span>';
      if (st === 'PENDING') return '<span class="badge badge-pending"><i class="fas fa-clock me-1"></i>PENDING</span>';
      return '<span class="badge badge-failed"><i class="fas fa-times-circle me-1"></i>' + st + '</span>';
    }

    async function loadTransactions(offset = 0) {
      currentOffset = offset;
      const search = document.getElementById('searchInput').value.trim();
      const status = document.getElementById('filterStatus').value;
      const gateway = document.getElementById('filterGateway').value;
      const fromDate = document.getElementById('filterFromDate').value;
      const toDate = document.getElementById('filterToDate').value;

      const params = new URLSearchParams({
        search: search,
        trang_thai: status,
        loai_cong: gateway,
        tu_ngay: fromDate,
        den_ngay: toDate,
        limit: limit,
        offset: currentOffset
      });

      try {
        const res = await fetch(`api/payments.php?${params.toString()}`);
        const json = await res.json();

        if (!json.success) {
          alert(json.message);
          return;
        }

        const { stats, total, payments } = json.data;
        allCurrentList = payments;

        // Cập nhật thống kê
        document.getElementById('statTotalCount').textContent = stats.tong_giao_dich || 0;
        document.getElementById('statTotalRevenue').textContent = formatMoney(stats.tong_doanh_thu);
        document.getElementById('statPendingCount').textContent = stats.dang_cho || 0;
        document.getElementById('statFailedCount').textContent = stats.that_bai || 0;

        // Render bảng
        const tbody = document.getElementById('transactionTbody');
        if (payments.length === 0) {
          tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Không tìm thấy giao dịch nào</td></tr>';
        } else {
          tbody.innerHTML = payments.map(p => `
            <tr>
              <td><code class="fw-bold text-primary">${p.MaDonHang}</code></td>
              <td class="fw-bold text-dark">${formatMoney(p.SoTien)}</td>
              <td><span class="badge bg-light text-dark border">${p.LoaiCongThanhToan}</span></td>
              <td>${statusBadge(p.TrangThai)}</td>
              <td class="small text-muted font-monospace">${p.MaGiaoDichCongThanhToan || '-'}</td>
              <td class="small text-muted">${p.ThoiGianTao}</td>
              <td class="small text-muted">${p.ThoiGianThanhToan || '-'}</td>
              <td class="text-end">
                <button class="btn btn-outline-primary btn-sm py-0 px-2" onclick="viewDetail('${p.ID}')">
                  Chi tiết
                </button>
                ${p.TrangThai === 'PENDING' ? `
                  <button class="btn btn-outline-success btn-sm py-0 px-2 ms-1" onclick="confirmPayment('${p.MaDonHang}', ${p.SoTien})">
                    Duyệt
                  </button>
                ` : ''}
              </td>
            </tr>
          `).join('');
        }

        // Cập nhật phân trang
        const start = total === 0 ? 0 : currentOffset + 1;
        const end = Math.min(currentOffset + limit, total);
        document.getElementById('pageInfo').textContent = `Hiển thị ${start} - ${end} của ${total} giao dịch`;
        document.getElementById('btnPrev').disabled = currentOffset <= 0;
        document.getElementById('btnNext').disabled = end >= total;

      } catch (err) {
        console.error(err);
      }
    }

    function changePage(direction) {
      const newOffset = currentOffset + (direction * limit);
      if (newOffset >= 0) {
        loadTransactions(newOffset);
      }
    }

    function viewDetail(id) {
      const item = allCurrentList.find(x => x.ID === id);
      if (!item) return;

      const html = `
        <table class="table table-bordered table-sm mb-0">
          <tr><th style="width: 40%">Mã đơn hàng</th><td><code class="fw-bold">${item.MaDonHang}</code></td></tr>
          <tr><th>Số tiền</th><td class="fw-bold text-success">${formatMoney(item.SoTien)}</td></tr>
          <tr><th>Cổng thanh toán</th><td>${item.LoaiCongThanhToan}</td></tr>
          <tr><th>Trạng thái</th><td>${statusBadge(item.TrangThai)}</td></tr>
          <tr><th>Mã đối soát (Bank/SePay)</th><td class="font-monospace">${item.MaGiaoDichCongThanhToan || 'Chưa có'}</td></tr>
          <tr><th>Mô tả</th><td>${item.MoTa || 'Không có'}</td></tr>
          <tr><th>Thời gian tạo</th><td>${item.ThoiGianTao}</td></tr>
          <tr><th>Thời gian thanh toán</th><td>${item.ThoiGianThanhToan || 'Chưa thanh toán'}</td></tr>
          <tr><th>ID giao dịch</th><td class="small font-monospace text-muted">${item.ID}</td></tr>
        </table>
      `;
      document.getElementById('modalDetailContent').innerHTML = html;
      new bootstrap.Modal(document.getElementById('detailModal')).show();
    }

    async function confirmPayment(orderId, amount) {
      if (!confirm(`Xác nhận đánh dấu đơn hàng ${orderId} thành công?`)) return;

      const res = await fetch('api/simulate_webhook.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ma_don_hang: orderId, so_tien: amount, status: 'SUCCESS' })
      });
      const json = await res.json();
      alert(json.message);
      if (json.success) loadTransactions(currentOffset);
    }

    function exportCSV() {
      if (!allCurrentList || allCurrentList.length === 0) {
        alert('Không có dữ liệu để xuất!');
        return;
      }
      let csv = "\uFEFFMã đơn,Số tiền,Cổng,Trạng thái,Mã đối soát,Thời gian tạo,Thanh toán lúc\n";
      allCurrentList.forEach(p => {
        csv += `"${p.MaDonHang}","${p.SoTien}","${p.LoaiCongThanhToan}","${p.TrangThai}","${p.MaGiaoDichCongThanhToan || ''}","${p.ThoiGianTao}","${p.ThoiGianThanhToan || ''}"\n`;
      });
      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `Danh_sach_giao_dich_${new Date().toISOString().slice(0,10)}.csv`;
      a.click();
    }

    // Enter để tìm kiếm
    document.getElementById('searchInput').addEventListener('keyup', e => {
      if (e.key === 'Enter') loadTransactions(0);
    });

    // Khởi chạy
    loadTransactions(0);
  </script>
</body>
</html>
