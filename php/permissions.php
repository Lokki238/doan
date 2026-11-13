<?php
/**
 * =====================================================
 * FILE: permissions.php
 * Mục đích: Trang Cấp quyền và Quản lý phân cấp vai trò tài khoản
 * Phân cấp thẩm quyền:
 * - Quản trị viên (OWNER): Điều chỉnh tất cả tài khoản
 * - Quản lý Cửa hàng (MANAGER): Điều chỉnh Nhân viên (STAFF) và Khách hàng (USER)
 * - Nhân viên (STAFF): Chỉ điều chỉnh Khách hàng (USER)
 * =====================================================
 */
require_once __DIR__ . '/includes/auth.php';
requireLogin('/html/login.html');
if (!isPrivileged()) {
    header('Location: /html/account.html');
    exit;
}

$user = getCurrentUser();
$myRole = strtoupper($user['vai_tro'] ?? 'USER');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cấp quyền tài khoản | FreshMart Pay</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/all.min.css">
  <style>
    body { background: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
    .sidebar { width: 250px; min-height: 100vh; background: #0f172a; color: #fff; flex-shrink: 0; }
    .sidebar a { color: #94a3b8; text-decoration: none; display: flex; align-items: center; padding: 12px 20px; transition: all .2s; }
    .sidebar a:hover, .sidebar a.active { background: #1e293b; color: #fff; }
    .sidebar a.active { border-left: 4px solid #10b981; color: #10b981; font-weight: 600; background: #1e293b; }
    .freshmart-home { background: rgba(16, 185, 129, 0.15) !important; color: #10b981 !important; font-weight: 600; border-radius: 8px; margin: 8px 12px; }
    .content { flex-grow: 1; padding: 30px; }
    .card-custom { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.04); background: #fff; }
    .badge-owner { background: #fef3c7; color: #92400e; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
    .badge-manager { background: #ede9fe; color: #6b21a8; font-weight: 600; padding: 6px 12px; border-radius: 6px; border: 1px solid #ddd6fe; }
    .badge-staff { background: #e0f2fe; color: #0369a1; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
    .badge-user { background: #dcfce7; color: #166534; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
    .user-avatar-circle {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.2rem;
    }
    .search-input-group {
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      border-radius: 10px;
      overflow: hidden;
    }
    .search-input-group input {
      border: 1px solid #cbd5e1;
      padding: 14px 18px;
      font-size: 1.05rem;
    }
    .search-input-group input:focus {
      box-shadow: none;
      border-color: #10b981;
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
        <a href="reports.php"><i class="fas fa-chart-bar me-2"></i> Báo cáo</a>
        <a href="permissions.php" class="active"><i class="fas fa-user-shield me-2"></i> Cấp quyền</a>
        <hr class="border-secondary mx-3 my-2">
        <!-- Nút về trang chủ FreshMart -->
        <a href="../html/index.html" class="freshmart-home">
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
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="mb-1 fw-bold"><i class="fas fa-user-shield text-success me-2"></i>Cấp quyền tài khoản</h4>
          <p class="text-muted small mb-0">Tìm kiếm người dùng và phân cấp quản trị hệ thống (OWNER, MANAGER, STAFF, USER)</p>
        </div>
        <div>
          <button class="btn btn-outline-secondary btn-sm" onclick="loadUsers('')">
            <i class="fas fa-sync-alt me-1"></i> Làm mới danh sách
          </button>
        </div>
      </div>

      <!-- Banner thông tin vai trò và thẩm quyền của người đang đăng nhập -->
      <div class="card card-custom p-3 mb-4 bg-white border-start border-4 <?= (in_array($myRole, ['OWNER', 'ADMIN', 'QUAN_TRI']) ? 'border-warning' : ($myRole === 'MANAGER' ? 'border-primary' : 'border-info')) ?>">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div>
            <span class="text-muted small">Tài khoản đang đăng nhập:</span>
            <strong><?= htmlspecialchars($user['ho_ten'] ?: $user['email']) ?></strong>
            <span class="ms-2">
              <?php if (in_array($myRole, ['OWNER', 'ADMIN', 'QUAN_TRI'])): ?>
                <span class="badge-owner"><i class="fas fa-crown me-1 text-warning"></i> Quản trị viên (OWNER)</span>
              <?php elseif ($myRole === 'MANAGER'): ?>
                <span class="badge-manager"><i class="fas fa-user-shield me-1"></i> Quản lý Cửa hàng (MANAGER)</span>
              <?php elseif ($myRole === 'STAFF'): ?>
                <span class="badge-staff"><i class="fas fa-user-tie me-1 text-primary"></i> Nhân viên (STAFF)</span>
              <?php else: ?>
                <span class="badge-user"><i class="fas fa-user me-1 text-success"></i> Khách hàng (USER)</span>
              <?php endif; ?>
            </span>
          </div>
          <div class="small">
            <?php if (in_array($myRole, ['OWNER', 'ADMIN', 'QUAN_TRI'])): ?>
              <span class="text-success fw-bold"><i class="fas fa-check-double me-1"></i> Thẩm quyền:</span> Điều chỉnh tất cả mọi tài khoản trong hệ thống
            <?php elseif ($myRole === 'MANAGER'): ?>
              <span class="text-primary fw-bold"><i class="fas fa-shield-alt me-1"></i> Thẩm quyền:</span> Điều chỉnh tài khoản Nhân viên và Khách hàng
            <?php elseif ($myRole === 'STAFF'): ?>
              <span class="text-info fw-bold"><i class="fas fa-user-check me-1"></i> Thẩm quyền:</span> Chỉ điều chỉnh tài khoản Khách hàng
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Alert Notification -->
      <div id="globalAlert" class="alert d-none shadow-sm mb-4" role="alert"></div>

      <!-- Card Thanh Tìm Kiếm -->
      <div class="card card-custom p-4 mb-4">
        <h6 class="fw-bold mb-3"><i class="fas fa-search me-2 text-primary"></i>Tìm kiếm tài khoản cần cấp quyền</h6>
        <div class="row g-2">
          <div class="col-lg-9 col-md-8">
            <div class="input-group search-input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user"></i></span>
              <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Nhập email, họ tên hoặc mã tài khoản... (nhấn Enter hoặc bấm nút Tìm kiếm)" autofocus>
            </div>
          </div>
          <div class="col-lg-3 col-md-4 d-flex gap-2">
            <button class="btn btn-primary px-4 flex-grow-1" id="btnSearch" onclick="handleSearch()">
              <i class="fas fa-search me-1"></i> Tìm kiếm
            </button>
            <button class="btn btn-outline-secondary" onclick="document.getElementById('searchInput').value=''; loadUsers('');" title="Xem tất cả">
              Tất cả
            </button>
          </div>
        </div>
        <small class="text-muted mt-2 d-block">
          <i class="fas fa-info-circle me-1 text-info"></i> Mẹo: Bạn có thể nhập email (ví dụ: <code>admin</code>, <code>khachhang</code>) hoặc họ tên rồi nhấn <strong>Enter</strong> để tra cứu ngay.
        </small>
      </div>

      <!-- Vùng Kết Quả Tìm Kiếm / Danh Sách Tài Khoản -->
      <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold mb-0" id="resultTitle">
            <i class="fas fa-users me-2 text-success"></i>Kết quả tìm kiếm
          </h6>
          <span class="badge bg-light text-dark border" id="resultCount">0 tài khoản</span>
        </div>

        <!-- Bảng danh sách kết quả -->
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 60px;">Avatar</th>
                <th>Họ và tên</th>
                <th>Email tài khoản</th>
                <th>Vai trò hiện tại</th>
                <th style="width: 270px;">Chọn quyền mới</th>
                <th style="width: 170px;" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody id="userTableBody">
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="fas fa-spinner fa-spin me-2"></i> Đang tải dữ liệu...
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <script src="../js/bootstrap.bundle.min.js"></script>
  <script>
    let currentViewerRole = "<?= $myRole ?>";

    // Bắt sự kiện phím Enter trên ô tìm kiếm
    document.getElementById('searchInput').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        handleSearch();
      }
    });

    function handleSearch() {
      const q = document.getElementById('searchInput').value.trim();
      loadUsers(q);
    }

    // Hiển thị thông báo Alert
    function showAlert(type, message) {
      const alertBox = document.getElementById('globalAlert');
      alertBox.className = `alert alert-${type} shadow-sm mb-4 d-flex align-items-center justify-content-between`;
      alertBox.innerHTML = `
        <div>
          <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} me-2"></i>
          ${message}
        </div>
        <button type="button" class="btn-close" onclick="this.parentElement.classList.add('d-none')"></button>
      `;
      alertBox.classList.remove('d-none');
      window.scrollTo({ top: 0, behavior: 'smooth' });

      setTimeout(() => {
        alertBox.classList.add('d-none');
      }, 5000);
    }

    function getRoleBadge(role) {
      const r = (role || 'USER').toUpperCase();
      if (r === 'OWNER' || r === 'ADMIN' || r === 'QUAN_TRI') {
        return '<span class="badge-owner"><i class="fas fa-crown me-1 text-warning"></i> Quản trị viên (OWNER)</span>';
      } else if (r === 'MANAGER') {
        return '<span class="badge-manager"><i class="fas fa-user-shield me-1"></i> Quản lý Cửa hàng (MANAGER)</span>';
      } else if (r === 'STAFF') {
        return '<span class="badge-staff"><i class="fas fa-user-tie me-1 text-primary"></i> Nhân viên (STAFF)</span>';
      } else {
        return '<span class="badge-user"><i class="fas fa-user me-1 text-success"></i> Khách hàng (USER)</span>';
      }
    }

    function getRoleLabel(role) {
      const r = (role || 'USER').toUpperCase();
      if (r === 'OWNER' || r === 'ADMIN' || r === 'QUAN_TRI') return 'Quản trị viên tối cao (OWNER)';
      if (r === 'MANAGER') return 'Quản lý Cửa hàng (MANAGER)';
      if (r === 'STAFF') return 'Nhân viên quản lý (STAFF)';
      return 'Khách hàng cá nhân (USER)';
    }

    // Load danh sách người dùng từ API
    async function loadUsers(query = '') {
      const tbody = document.getElementById('userTableBody');
      const countBadge = document.getElementById('resultCount');
      const title = document.getElementById('resultTitle');

      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="text-center py-5 text-muted">
            <i class="fas fa-spinner fa-spin me-2"></i> Đang tìm kiếm tài khoản...
          </td>
        </tr>
      `;

      try {
        const url = query ? `api/permissions.php?q=${encodeURIComponent(query)}` : 'api/permissions.php';
        const res = await fetch(url);
        const data = await res.json();

        if (!data.success) {
          tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">${data.message}</td></tr>`;
          return;
        }

        if (data.data.current_user && data.data.current_user.vai_tro) {
          currentViewerRole = data.data.current_user.vai_tro.toUpperCase();
        }

        const users = data.data.users || [];
        countBadge.textContent = `${users.length} tài khoản`;
        title.innerHTML = query 
          ? `<i class="fas fa-search me-2 text-primary"></i>Kết quả tìm kiếm cho: <strong>"${escapeHtml(query)}"</strong>`
          : `<i class="fas fa-users me-2 text-success"></i>Tất cả tài khoản trong hệ thống`;

        if (users.length === 0) {
          tbody.innerHTML = `
            <tr>
              <td colspan="6" class="text-center py-5">
                <i class="fas fa-user-slash text-muted fs-1 mb-3 d-block"></i>
                <h6 class="text-muted fw-bold">Không tìm thấy tài khoản nào</h6>
                <p class="small text-muted mb-0">Không có người dùng nào khớp với từ khóa "<strong>${escapeHtml(query)}</strong>". Vui lòng kiểm tra lại email hoặc họ tên.</p>
              </td>
            </tr>
          `;
          return;
        }

        tbody.innerHTML = users.map(u => {
          const targetRole = (u.VaiTro || 'USER').toUpperCase();
          const roleBadge = getRoleBadge(targetRole);
          const initials = (u.HoTen ? u.HoTen.charAt(0) : (u.Email ? u.Email.charAt(0) : 'U')).toUpperCase();

          let canEdit = false;
          let disabledReason = '';
          let availableOptions = [];

          // QUY TẮC PHÂN CẤP ĐIỀU CHỈNH:
          // 1. Quản trị viên điều chỉnh tất cả
          if (currentViewerRole === 'OWNER' || currentViewerRole === 'ADMIN' || currentViewerRole === 'QUAN_TRI') {
            canEdit = true;
            availableOptions = [
              { value: 'USER', label: 'Khách hàng cá nhân (USER)' },
              { value: 'STAFF', label: 'Nhân viên quản lý (STAFF)' },
              { value: 'MANAGER', label: 'Quản lý Cửa hàng (MANAGER)' },
              { value: 'OWNER', label: 'Quản trị viên tối cao (OWNER)' }
            ];
          }
          // 2. Quản lý Cửa hàng điều chỉnh Nhân viên và Khách hàng
          else if (currentViewerRole === 'MANAGER') {
            if (targetRole === 'STAFF' || targetRole === 'USER') {
              canEdit = true;
              availableOptions = [
                { value: 'USER', label: 'Khách hàng cá nhân (USER)' },
                { value: 'STAFF', label: 'Nhân viên quản lý (STAFF)' }
              ];
            } else {
              canEdit = false;
              disabledReason = 'Chỉ Quản trị viên điều chỉnh';
            }
          }
          // 3. Nhân viên chỉ điều chỉnh được Khách hàng
          else if (currentViewerRole === 'STAFF') {
            if (targetRole === 'USER') {
              canEdit = true;
              availableOptions = [
                { value: 'USER', label: 'Khách hàng cá nhân (USER)' }
              ];
            } else {
              canEdit = false;
              disabledReason = 'Chỉ Quản lý / Admin điều chỉnh';
            }
          }

          let selectRoleHtml = '';
          let actionBtnHtml = '';

          if (canEdit) {
            selectRoleHtml = `
              <select class="form-select form-select-sm" id="select-role-${u.ID}">
                ${availableOptions.map(opt => `<option value="${opt.value}" ${targetRole === opt.value ? 'selected' : ''}>${opt.label}</option>`).join('')}
              </select>
            `;
            actionBtnHtml = `
              <button class="btn btn-success btn-sm px-3" id="btn-save-${u.ID}" onclick="updateRole('${u.ID}', '${escapeHtml(u.Email)}')">
                <i class="fas fa-save me-1"></i> Lưu quyền
              </button>
            `;
          } else {
            selectRoleHtml = `
              <select class="form-select form-select-sm bg-light text-muted" id="select-role-${u.ID}" disabled title="${disabledReason}">
                <option selected>${getRoleLabel(targetRole)}</option>
              </select>
            `;
            actionBtnHtml = `
              <span class="badge bg-light text-muted border py-2 px-2 d-inline-flex align-items-center" title="${disabledReason}">
                <i class="fas fa-lock me-1 text-secondary"></i> ${disabledReason}
              </span>
            `;
          }

          return `
            <tr id="row-${u.ID}">
              <td>
                <div class="user-avatar-circle">${escapeHtml(initials)}</div>
              </td>
              <td>
                <div class="fw-bold text-dark">${escapeHtml(u.HoTen || 'Chưa cập nhật')}</div>
                <small class="text-muted"><i class="far fa-clock me-1"></i>Tạo ngày: ${u.ThoiGianTao ? u.ThoiGianTao.substring(0, 10) : '-'}</small>
              </td>
              <td>
                <span class="fw-semibold text-primary">${escapeHtml(u.Email)}</span>
                ${u.TenCH ? `<br><small class="text-muted"><i class="fas fa-store me-1"></i>${escapeHtml(u.TenCH)}</small>` : ''}
              </td>
              <td id="badge-container-${u.ID}">
                ${roleBadge}
              </td>
              <td>
                ${selectRoleHtml}
              </td>
              <td class="text-center">
                ${actionBtnHtml}
              </td>
            </tr>
          `;
        }).join('');

      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4">Lỗi kết nối khi tải dữ liệu người dùng.</td></tr>`;
      }
    }

    // Cập nhật vai trò vào Database
    async function updateRole(userId, email) {
      const select = document.getElementById(`select-role-${userId}`);
      const btn = document.getElementById(`btn-save-${userId}`);
      if (!select || !btn) return;

      const newRole = select.value;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang lưu...';

      try {
        const res = await fetch('api/permissions.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            user_id: userId,
            vai_tro: newRole
          })
        });
        const data = await res.json();

        if (data.success) {
          showAlert('success', data.message);

          // Cập nhật lại giao diện và quyền mới ngay lập tức
          const q = document.getElementById('searchInput').value.trim();
          loadUsers(q);
        } else {
          showAlert('danger', data.message || 'Cập nhật quyền thất bại.');
          btn.disabled = false;
          btn.innerHTML = '<i class="fas fa-save me-1"></i> Lưu quyền';
        }
      } catch (err) {
        showAlert('danger', 'Lỗi kết nối máy chủ khi cập nhật quyền.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> Lưu quyền';
      }
    }

    function escapeHtml(text) {
      if (!text) return '';
      return String(text).replace(/[&<>"']/g, function (m) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
      });
    }

    // Tự động load danh sách ban đầu khi vào trang
    loadUsers('');
  </script>
  <script src="../js/admin_notifications.js"></script>
</body>
</html>
