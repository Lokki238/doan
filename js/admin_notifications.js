/**
 * ====================================================================
 * FRESHMART NOTIFICATIONS - THÔNG BÁO ĐƠN CHỜ THANH TOÁN THỜI GIAN THỰC
 * Dành cho toàn bộ tài khoản được cấp quyền từ Nhân viên trở lên (STAFF, OWNER, ADMIN)
 * ====================================================================
 */

(function () {
  'use strict';

  // Xác định đường dẫn gốc API tương ứng với vị trí trang hiện tại
  function getApiUrl() {
    const path = window.location.pathname;
    if (path.includes('/admin/')) {
      return '../api/notifications.php';
    } else if (path.includes('/php/')) {
      return 'api/notifications.php';
    } else {
      return '../php/api/notifications.php';
    }
  }

  // Khởi tạo âm thanh thông báo chuông "Ting!" nhẹ nhàng qua Web Audio API
  function playNotificationSound() {
    try {
      const AudioCtx = window.AudioContext || window.webkitAudioContext;
      if (!AudioCtx) return;
      const ctx = new AudioCtx();
      
      // Tone 1 (E5 - 659.25Hz)
      const osc1 = ctx.createOscillator();
      const gain1 = ctx.createGain();
      osc1.connect(gain1);
      gain1.connect(ctx.destination);
      osc1.type = 'sine';
      osc1.frequency.setValueAtTime(659.25, ctx.currentTime);
      gain1.gain.setValueAtTime(0.12, ctx.currentTime);
      gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
      osc1.start(ctx.currentTime);
      osc1.stop(ctx.currentTime + 0.35);

      // Tone 2 (A5 - 880Hz)
      const osc2 = ctx.createOscillator();
      const gain2 = ctx.createGain();
      osc2.connect(gain2);
      gain2.connect(ctx.destination);
      osc2.type = 'sine';
      osc2.frequency.setValueAtTime(880, ctx.currentTime + 0.12);
      gain2.gain.setValueAtTime(0.15, ctx.currentTime + 0.12);
      gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.55);
      osc2.start(ctx.currentTime + 0.12);
      osc2.stop(ctx.currentTime + 0.55);
    } catch (e) {
      // Trình duyệt có thể cần tương tác chuột trước khi phát audio
    }
  }

  // Chèn CSS giao diện thông báo & chuông vào thẻ <head>
  function injectStyles() {
    if (document.getElementById('fm-notification-styles')) return;
    const style = document.createElement('style');
    style.id = 'fm-notification-styles';
    style.textContent = `
      /* Khung chứa các Toast nổi góc trên bên phải */
      #fm-toast-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 999999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        pointer-events: none;
      }
      .fm-toast-card {
        pointer-events: auto;
        width: 360px;
        max-width: 90vw;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.18), 0 2px 8px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.35);
        border-left: 6px solid #f59e0b;
        padding: 16px;
        animation: fmSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
        font-family: system-ui, -apple-system, sans-serif;
      }
      .fm-toast-card.success-toast {
        border-left-color: #10b981;
        border-color: rgba(16, 185, 129, 0.35);
      }
      @keyframes fmSlideIn {
        from { opacity: 0; transform: translateX(50px) scale(0.96); }
        to { opacity: 1; transform: translateX(0) scale(1); }
      }
      .fm-toast-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
      }
      .fm-toast-badge {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 3px 8px;
        border-radius: 50px;
        background: #fef3c7;
        color: #d97706;
        display: inline-flex;
        align-items: center;
        gap: 4px;
      }
      .fm-toast-badge.success-badge {
        background: #d1fae5;
        color: #059669;
      }
      .fm-toast-close {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.1rem;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
      }
      .fm-toast-close:hover { color: #0f172a; }
      .fm-toast-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
      }
      .fm-toast-body {
        font-size: 0.86rem;
        color: #475569;
        line-height: 1.45;
        margin-bottom: 12px;
      }
      .fm-toast-amount {
        font-size: 1.05rem;
        font-weight: 800;
        color: #059669;
      }
      .fm-toast-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
      }
      .fm-toast-btn {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        transition: .15s;
        border: none;
      }
      .fm-toast-btn-primary {
        background: #10b981;
        color: #fff;
      }
      .fm-toast-btn-primary:hover {
        background: #059669;
        color: #fff;
      }

      /* Chuông thông báo trên Header */
      .fm-bell-container {
        position: relative;
        display: inline-flex;
        align-items: center;
        margin-right: 12px;
      }
      .fm-bell-btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #334155;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        transition: all .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.05);
      }
      .fm-bell-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
      }
      .fm-bell-btn.has-new {
        animation: fmBellShake 0.6s ease infinite;
      }
      @keyframes fmBellShake {
        0%, 100% { transform: rotate(0); }
        20% { transform: rotate(15deg); }
        40% { transform: rotate(-15deg); }
        60% { transform: rotate(10deg); }
        80% { transform: rotate(-10deg); }
      }
      .fm-bell-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: #ffffff;
        font-size: 0.7rem;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.4);
      }
      .fm-bell-badge.empty { display: none; }

      /* Dropdown danh sách đơn chờ */
      .fm-dropdown-menu {
        position: absolute;
        top: 48px;
        right: 0;
        width: 340px;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.2);
        border: 1px solid #e2e8f0;
        padding: 0;
        display: none;
        z-index: 100000;
        animation: fmDropIn 0.2s ease-out;
      }
      .fm-dropdown-menu.show { display: block; }
      @keyframes fmDropIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
      }
      .fm-dropdown-header {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .fm-dropdown-list {
        max-height: 320px;
        overflow-y: auto;
        padding: 6px 0;
      }
      .fm-dropdown-item {
        padding: 10px 16px;
        border-bottom: 1px solid #f8fafc;
        display: block;
        text-decoration: none;
        color: #334155;
        transition: background .15s;
      }
      .fm-dropdown-item:hover {
        background: #f8fafc;
      }
      .fm-dropdown-footer {
        padding: 10px 16px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        text-align: center;
        border-radius: 0 0 14px 14px;
      }
      .fm-dropdown-footer a {
        font-size: 0.82rem;
        font-weight: 600;
        color: #10b981;
        text-decoration: none;
      }
      .fm-dropdown-footer a:hover { text-decoration: underline; }
    `;
    document.head.appendChild(style);
  }

  // Khởi tạo container thông báo
  function initContainer() {
    let container = document.getElementById('fm-toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'fm-toast-container';
      document.body.appendChild(container);
    }
    return container;
  }

  // Hiển thị Toast nổi khi có đơn hàng mới
  function showPendingToast(order) {
    const container = initContainer();
    const card = document.createElement('div');
    card.className = 'fm-toast-card';

    const orderId = order.ma_don_hang || 'FM...';
    const amountStr = order.so_tien_format || (Number(order.so_tien || 0).toLocaleString('vi-VN') + ' ₫');
    const customerNote = order.mo_ta || 'Khách hàng vừa tạo mã QR';
    const timeAgo = order.thoi_gian_truoc || 'Vừa xong';

    card.innerHTML = `
      <div class="fm-toast-header">
        <span class="fm-toast-badge">
          <i class="fas fa-clock"></i> ĐƠN CHỜ THANH TOÁN
        </span>
        <button class="fm-toast-close" title="Đóng">&times;</button>
      </div>
      <div class="fm-toast-title">
        <i class="fas fa-qrcode text-warning"></i> Đơn hàng mới: <strong>#${orderId}</strong>
      </div>
      <div class="fm-toast-body">
        Số tiền cần thu: <span class="fm-toast-amount">${amountStr}</span><br>
        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>${customerNote}</small><br>
        <small class="text-muted"><i class="far fa-clock me-1"></i>${timeAgo} · Phương thức: VietQR</small>
      </div>
      <div class="fm-toast-actions">
        <a href="${window.location.pathname.includes('/php/') ? 'transactions.php' : '../php/transactions.php'}" class="fm-toast-btn fm-toast-btn-primary">
          <i class="fas fa-external-link-alt me-1"></i> Xem đơn hàng
        </a>
      </div>
    `;

    // Nút đóng
    card.querySelector('.fm-toast-close').addEventListener('click', function () {
      card.remove();
    });

    container.appendChild(card);
    playNotificationSound();

    // Tự động ẩn sau 10 giây
    setTimeout(function () {
      if (card.parentNode) {
        card.style.opacity = '0';
        card.style.transform = 'translateX(50px)';
        setTimeout(() => card.remove(), 300);
      }
    }, 10000);
  }

  // Quản lý danh sách ID đơn đã thông báo để không bị thông báo lặp lại
  const knownOrderIds = new Set();
  let isFirstLoad = true;

  // Lắp đặt Quả chuông thông báo (Bell) vào Header
  function renderBellUI(data) {
    let bellContainer = document.getElementById('fm-bell-container');
    if (!bellContainer) {
      // Tìm vị trí thích hợp trên thanh header
      const headerRight = document.querySelector('.content .d-flex.justify-content-between') ||
                          document.querySelector('.navbar .ms-auto') ||
                          document.querySelector('.topbar');

      bellContainer = document.createElement('div');
      bellContainer.id = 'fm-bell-container';
      bellContainer.className = 'fm-bell-container';
      bellContainer.innerHTML = `
        <button class="fm-bell-btn" id="fm-bell-btn" title="Thông báo đơn chờ thanh toán (Realtime)">
          <i class="fas fa-bell"></i>
          <span class="fm-bell-badge empty" id="fm-bell-badge">0</span>
        </button>
        <div class="fm-dropdown-menu" id="fm-dropdown-menu">
          <div class="fm-dropdown-header">
            <h6 class="mb-0 fw-bold"><i class="fas fa-bell text-warning me-2"></i>Đơn chờ thanh toán</h6>
            <span class="badge bg-warning text-dark" id="fm-dropdown-count">0</span>
          </div>
          <div class="fm-dropdown-list" id="fm-dropdown-list">
            <div class="p-3 text-center text-muted small">Đang kiểm tra...</div>
          </div>
          <div class="fm-dropdown-footer">
            <a href="${window.location.pathname.includes('/php/') ? 'transactions.php' : '../php/transactions.php'}">
              Xem tất cả giao dịch <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      `;

      if (headerRight) {
        // Chèn vào trước nút làm mới nếu có
        const refreshBtn = headerRight.querySelector('button, .btn');
        if (refreshBtn) {
          headerRight.insertBefore(bellContainer, refreshBtn);
        } else {
          headerRight.appendChild(bellContainer);
        }
      } else {
        // Nếu không có header cố định, treo nổi ở góc trên
        bellContainer.style.position = 'fixed';
        bellContainer.style.top = '18px';
        bellContainer.style.right = '24px';
        bellContainer.style.zIndex = '99998';
        document.body.appendChild(bellContainer);
      }

      // Xử lý toggle menu dropdown
      const btn = document.getElementById('fm-bell-btn');
      const menu = document.getElementById('fm-dropdown-menu');
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.toggle('show');
        btn.classList.remove('has-new');
      });

      document.addEventListener('click', function (e) {
        if (!bellContainer.contains(e.target)) {
          menu.classList.remove('show');
        }
      });
    }

    // Cập nhật số lượng và danh sách đơn trên Menu
    const badge = document.getElementById('fm-bell-badge');
    const dropCount = document.getElementById('fm-dropdown-count');
    const dropList = document.getElementById('fm-dropdown-list');
    const totalPending = data.total_pending || 0;
    const orders = data.orders || [];

    if (totalPending > 0) {
      badge.textContent = totalPending > 99 ? '99+' : totalPending;
      badge.classList.remove('empty');
      dropCount.textContent = totalPending + ' đơn';

      dropList.innerHTML = orders.map(function (o) {
        const transLink = window.location.pathname.includes('/php/') ? 'transactions.php' : '../php/transactions.php';
        return `
          <a href="${transLink}" class="fm-dropdown-item">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <strong class="text-primary small">#${o.ma_don_hang}</strong>
              <span class="badge bg-warning bg-opacity-25 text-warning small">${o.thoi_gian_truoc}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-truncate small text-muted" style="max-width: 190px;">${o.mo_ta}</span>
              <strong class="text-success small">${o.so_tien_format}</strong>
            </div>
          </a>
        `;
      }).join('');
    } else {
      badge.textContent = '0';
      badge.classList.add('empty');
      dropCount.textContent = '0 đơn';
      dropList.innerHTML = '<div class="p-4 text-center text-muted small"><i class="fas fa-check-circle text-success mb-2 d-block" style="font-size: 1.5rem;"></i>Không có đơn nào đang chờ thanh toán</div>';
    }
  }

  // Tiến trình kiểm tra đơn hàng PENDING theo chu kỳ (Polling)
  async function checkNotifications() {
    try {
      const apiUrl = getApiUrl();
      const res = await fetch(apiUrl, { cache: 'no-store' });
      if (res.status === 401 || res.status === 403) {
        // Tài khoản không có quyền nhân viên -> Dừng kiểm tra
        return;
      }
      const json = await res.json();
      if (!json || !json.success || !json.data) return;

      const data = json.data;
      if (!data.is_privileged) return;

      renderBellUI(data);

      const orders = data.orders || [];

      // Kiểm tra có đơn PENDING mới xuất hiện không
      let hasNewOrder = false;
      let newestOrder = null;

      orders.forEach(function (order) {
        if (!knownOrderIds.has(order.ma_don_hang)) {
          if (!isFirstLoad) {
            hasNewOrder = true;
            if (!newestOrder) newestOrder = order;
          }
          knownOrderIds.add(order.ma_don_hang);
        }
      });

      if (hasNewOrder && newestOrder) {
        // Bật Toast nổi và rung chuông
        showPendingToast(newestOrder);
        const bellBtn = document.getElementById('fm-bell-btn');
        if (bellBtn) bellBtn.classList.add('has-new');

        // Tự động làm mới dữ liệu Dashboard nếu người dùng đang đứng ở dashboard.php
        if (typeof window.loadData === 'function') {
          window.loadData();
        }
        // Tự động làm mới bảng giao dịch nếu đang đứng ở transactions.php
        if (typeof window.loadTransactions === 'function') {
          window.loadTransactions();
        }
      }

      isFirstLoad = false;
    } catch (e) {
      // Bỏ qua lỗi mạng chập chờn
    }
  }

  // Khởi động khi tải xong DOM
  function init() {
    injectStyles();
    checkNotifications();
    // Định kỳ kiểm tra mỗi 3.5 giây để đảm bảo tính tức thì
    setInterval(checkNotifications, 3500);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
