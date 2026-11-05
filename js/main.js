/**
 * FreshMart - Main JavaScript
 * Basic interactions (effects will be enhanced later)
 */

document.addEventListener('DOMContentLoaded', function () {
  // ---------- Search Toggle ----------
  const searchToggle = document.getElementById('searchToggle');
  const searchOverlay = document.getElementById('searchOverlay');
  const searchClose = document.getElementById('searchClose');

  if (searchToggle && searchOverlay) {
    searchToggle.addEventListener('click', function () {
      searchOverlay.classList.toggle('active');
      if (searchOverlay.classList.contains('active')) {
        const input = searchOverlay.querySelector('.search-input');
        if (input) setTimeout(() => input.focus(), 100);
      }
    });
  }

  if (searchClose) {
    searchClose.addEventListener('click', function () {
      searchOverlay.classList.remove('active');
    });
  }

  // ---------- Mobile Menu ----------
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const mobileMenuClose = document.getElementById('mobileMenuClose');
  const overlay = document.getElementById('overlay');

  function openMobileMenu() {
    if (mobileMenu) mobileMenu.classList.add('active');
    if (overlay) overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeMobileMenu() {
    if (mobileMenu) mobileMenu.classList.remove('active');
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
  if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeMobileMenu);
  if (overlay) overlay.addEventListener('click', closeMobileMenu);

  // ---------- Product Dropdown ----------
  const dropdowns = document.querySelectorAll('.has-dropdown');

  dropdowns.forEach(function (dropdown) {
    const link = dropdown.querySelector('.nav-link');
    if (!link) return;

    link.addEventListener('click', function (event) {
      if (window.innerWidth >= 992) return;
      event.preventDefault();
      const isOpen = dropdown.classList.contains('active');
      dropdowns.forEach(function (item) {
        item.classList.remove('active');
      });
      if (!isOpen) dropdown.classList.add('active');
    });
  });

  document.addEventListener('click', function (event) {
    if (!event.target.closest('.has-dropdown')) {
      dropdowns.forEach(function (dropdown) {
        dropdown.classList.remove('active');
      });
    }
  });

  // ---------- Back to Top ----------
  const backToTop = document.getElementById('backToTop');

  window.addEventListener('scroll', function () {
    if (backToTop) {
      if (window.scrollY > 400) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    }
  });

  if (backToTop) {
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ---------- Add to Cart (basic feedback) ----------
  const addToCartBtns = document.querySelectorAll('.add-to-cart');
  addToCartBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const original = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-check me-1"></i> Đã thêm';
      btn.disabled = true;
      btn.style.background = '#40916c';

      // Update cart badge
      const badge = document.querySelector('.cart-btn .badge-count');
      if (badge) {
        const current = parseInt(badge.textContent) || 0;
        badge.textContent = current + 1;
      }

      setTimeout(function () {
        btn.innerHTML = original;
        btn.disabled = false;
        btn.style.background = '';
      }, 1500);
    });
  });

  // ---------- Newsletter form ----------
  const newsletterForm = document.querySelector('.newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const input = newsletterForm.querySelector('input');
      if (input && input.value) {
        alert('Cảm ơn bạn đã đăng ký! Email: ' + input.value);
        input.value = '';
      }
    });
  }

  // ---------- User Authentication & Dynamic Top-Bar ----------
  function initUserAuth() {
    const isInsideHtml = window.location.pathname.includes('/html/') || window.location.pathname.endsWith('.html');
    const apiMeUrl = isInsideHtml ? '../api/me.php' : 'api/me.php';
    const dashboardUrl = isInsideHtml ? '../dashboard.php' : 'dashboard.php';
    const accountUrl = isInsideHtml ? 'account.html' : 'html/account.html';
    const logoutUrl = isInsideHtml ? '../api/logout.php' : 'api/logout.php';

    fetch(apiMeUrl, { credentials: 'same-origin' })
      .then(res => res.json())
      .then(res => {
        if (!res || !res.success || !res.data || !res.data.logged_in) {
          return;
        }

        const user = res.data;
        const privilegedRoles = ['OWNER', 'STAFF', 'ADMIN', 'QUAN_TRI'];
        const isPrivileged = privilegedRoles.includes((user.vai_tro || '').toUpperCase());
        const targetUrl = isPrivileged ? dashboardUrl : accountUrl;
        const displayName = user.ho_ten || (user.email ? user.email.split('@')[0] : 'Tài khoản');

        // 1. Cập nhật Top-Bar trên máy tính (Desktop)
        const topBarRight = document.querySelector('.top-bar-right');
        if (topBarRight) {
          const links = Array.from(topBarRight.querySelectorAll('a'));
          const loginLink = links.find(a => {
            const href = a.getAttribute('href') || '';
            const text = a.textContent.trim().toLowerCase();
            return href.includes('login.html') || text.includes('đăng nhập');
          });

          if (loginLink) {
            loginLink.setAttribute('href', targetUrl);
            loginLink.setAttribute('title', isPrivileged ? 'Đến Bảng điều khiển Quản trị (Dashboard)' : 'Trang thông tin tài khoản');
            loginLink.classList.add('fw-semibold', 'user-nav-link');

            if (isPrivileged) {
              loginLink.innerHTML = `<i class="fas fa-user-shield text-warning me-1"></i><span class="user-name">${escapeHtml(displayName)}</span> <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem;padding:2px 5px;vertical-align:middle;">Admin</span>`;
            } else {
              loginLink.innerHTML = `<i class="fas fa-user-circle text-success me-1"></i><span class="user-name">${escapeHtml(displayName)}</span>`;
            }

            // Nút đăng xuất nhanh trên Top Bar
            if (!topBarRight.querySelector('.topbar-logout-btn')) {
              const logoutA = document.createElement('a');
              logoutA.href = logoutUrl;
              logoutA.className = 'topbar-logout-btn ms-3 text-white-50';
              logoutA.title = 'Đăng xuất tài khoản';
              logoutA.innerHTML = '<i class="fas fa-sign-out-alt me-1"></i>Thoát';
              logoutA.style.textDecoration = 'none';
              logoutA.addEventListener('mouseenter', () => logoutA.classList.replace('text-white-50', 'text-warning'));
              logoutA.addEventListener('mouseleave', () => logoutA.classList.replace('text-warning', 'text-white-50'));
              loginLink.after(logoutA);
            }
          }
        }

        // 2. Cập nhật Menu trên thiết bị di động (Mobile Menu)
        const mobileNav = document.querySelector('.mobile-nav');
        if (mobileNav) {
          const links = Array.from(mobileNav.querySelectorAll('a'));
          const mobileLoginLink = links.find(a => {
            const href = a.getAttribute('href') || '';
            const text = a.textContent.trim().toLowerCase();
            return href.includes('login.html') || text.includes('đăng nhập');
          });

          if (mobileLoginLink) {
            mobileLoginLink.setAttribute('href', targetUrl);
            if (isPrivileged) {
              mobileLoginLink.innerHTML = `<i class="fas fa-user-shield text-warning me-2"></i>${escapeHtml(displayName)} <span class="badge bg-warning text-dark ms-1">Quản trị</span>`;
            } else {
              mobileLoginLink.innerHTML = `<i class="fas fa-user-circle text-success me-2"></i>${escapeHtml(displayName)}`;
            }

            const parentLi = mobileLoginLink.closest('li');
            if (parentLi && !mobileNav.querySelector('.mobile-logout-li')) {
              const logoutLi = document.createElement('li');
              logoutLi.className = 'mobile-logout-li';
              logoutLi.innerHTML = `<a href="${logoutUrl}" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a>`;
              parentLi.after(logoutLi);
            }
          }
        }
      })
      .catch(() => {
        // Bỏ qua lỗi kết nối
      });
  }

  function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/[&<>"']/g, function (m) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
    });
  }

  // Khởi động kiểm tra trạng thái người dùng
  initUserAuth();
});