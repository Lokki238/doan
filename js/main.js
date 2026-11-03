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
});