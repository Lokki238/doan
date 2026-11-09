/**
 * FreshMart - Main JavaScript
 * Basic interactions (effects will be enhanced later)
 */

// =====================================================
// FRESHMART CART - Quản lý giỏ hàng chuẩn LocalStorage
// =====================================================
window.FreshMartCart = {
  STORAGE_KEY: 'freshmart_cart',

  getCart: function () {
    try {
      const data = localStorage.getItem(this.STORAGE_KEY);
      return data ? JSON.parse(data) : [];
    } catch (e) {
      return [];
    }
  },

  saveCart: function (cart) {
    try {
      localStorage.setItem(this.STORAGE_KEY, JSON.stringify(cart));
      this.updateBadges();
      window.dispatchEvent(new CustomEvent('cartUpdated', { detail: cart }));
    } catch (e) {}
  },

  addItem: function (item) {
    const cart = this.getCart();
    const existing = cart.find(p => p.name === item.name);
    if (existing) {
      existing.qty = (parseInt(existing.qty) || 1) + (parseInt(item.qty) || 1);
      if (item.price) existing.price = parseInt(item.price);
      if (item.image && !existing.image) existing.image = item.image;
    } else {
      cart.push({
        id: item.id || ('prod_' + Date.now() + '_' + Math.floor(Math.random() * 1000)),
        name: item.name,
        price: parseInt(item.price) || 0,
        image: item.image || 'https://images.unsplash.com/photo-1619566636858-adf3ef4644b9?w=400&q=80',
        unit: item.unit || '500g',
        qty: parseInt(item.qty) || 1
      });
    }
    this.saveCart(cart);
  },

  updateQty: function (productId, qty) {
    const cart = this.getCart();
    const target = cart.find(p => p.id === productId || p.name === productId);
    if (target) {
      target.qty = Math.max(1, parseInt(qty) || 1);
      this.saveCart(cart);
    }
  },

  removeItem: function (productId) {
    let cart = this.getCart();
    cart = cart.filter(p => p.id !== productId && p.name !== productId);
    this.saveCart(cart);
  },

  clearCart: function () {
    this.saveCart([]);
  },

  getTotalCount: function () {
    const cart = this.getCart();
    return cart.reduce((sum, item) => sum + (parseInt(item.qty) || 1), 0);
  },

  getSubtotal: function () {
    const cart = this.getCart();
    return cart.reduce((sum, item) => sum + ((parseInt(item.price) || 0) * (parseInt(item.qty) || 1)), 0);
  },

  updateBadges: function () {
    const count = this.getTotalCount();
    const badges = document.querySelectorAll('.cart-btn .badge-count, #headerCartCount, a[href*="cart.html"] .badge-count');
    badges.forEach(b => {
      b.textContent = count;
    });
  }
};

// Cập nhật ngay khi file được thực thi
if (window.FreshMartCart) {
  window.FreshMartCart.updateBadges();
}

// Cập nhật khi trang hiển thị lại từ bfcache (quay lại / chuyển trang)
window.addEventListener('pageshow', function () {
  if (window.FreshMartCart) {
    window.FreshMartCart.updateBadges();
  }
});

// Đồng bộ tự động giữa các tab
window.addEventListener('storage', function (e) {
  if (e.key === 'freshmart_cart' && window.FreshMartCart) {
    window.FreshMartCart.updateBadges();
  }
});

document.addEventListener('DOMContentLoaded', function () {
  // Cập nhật số đếm trên giỏ hàng ban đầu
  if (window.FreshMartCart) {
    window.FreshMartCart.updateBadges();
  }

  // ---------- Add to Cart (Lưu vào Giỏ hàng thật) ----------
  const addToCartBtns = document.querySelectorAll('.add-to-cart');
  addToCartBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const card = btn.closest('.product-card') || btn.closest('.card') || btn.closest('.product-item');
      let name = 'Sản phẩm FreshMart';
      let price = 32000;
      let image = '';
      let unit = '500g / gói';

      if (card) {
        const nameEl = card.querySelector('.product-name a, .product-name, h3, h4');
        if (nameEl) name = nameEl.textContent.trim();

        const catEl = card.querySelector('.product-cat');
        if (catEl) unit = catEl.textContent.trim();

        const priceEl = card.querySelector('.price-new, .product-price');
        if (priceEl) {
          const rawPrice = priceEl.textContent.replace(/[^\d]/g, '');
          if (rawPrice) price = parseInt(rawPrice);
        }

        const imgEl = card.querySelector('img');
        if (imgEl && imgEl.src) image = imgEl.src;
      }

      // Lưu vào giỏ hàng
      window.FreshMartCart.addItem({
        name: name,
        price: price,
        image: image,
        unit: unit,
        qty: 1
      });

      const original = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-check me-1"></i> Đã thêm';
      btn.disabled = true;
      btn.style.background = '#059669';
      btn.style.borderColor = '#059669';

      setTimeout(function () {
        btn.innerHTML = original;
        btn.disabled = false;
        btn.style.background = '';
        btn.style.borderColor = '';
      }, 1200);
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