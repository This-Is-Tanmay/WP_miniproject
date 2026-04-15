/**
 * main.js - Global JavaScript Utilities
 * - AJAX helper
 * - Toast notifications
 * - Navbar scroll effect
 * - Hamburger menu
 */

'use strict';

/* =============================================
   AJAX HELPER
   ============================================= */
const Ajax = {
  /**
   * Send an AJAX request
   * @param {string} url
   * @param {Object} options: { method, data, onSuccess, onError, btnId, btnText }
   */
  send(url, { method = 'POST', data = null, onSuccess, onError, btn = null, btnText = null, spinner = null } = {}) {
    let body = null;

    if (data) {
      if (data instanceof FormData) {
        body = data;
      } else {
        const fd = new FormData();
        Object.entries(data).forEach(([k, v]) => fd.append(k, v));
        body = fd;
      }
    }

    // Show loading state
    if (btn) {
      btn.disabled = true;
      if (btnText) btn.querySelector('#' + btnText.replace('#', ''))?.style && (btn.querySelector('[id$="Text"]').style.opacity = '0.5');
    }
    if (spinner) spinner.style.display = 'inline-block';

    fetch(url, {
      method,
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body
    })
    .then(r => r.json())
    .then(data => {
      if (onSuccess) onSuccess(data);
    })
    .catch(err => {
      console.error('AJAX Error:', err);
      if (onError) onError({ success: false, message: 'Network error. Please check your connection.' });
      else Toast.error('Network error. Please try again.');
    })
    .finally(() => {
      if (btn) btn.disabled = false;
      if (spinner) spinner.style.display = 'none';
    });
  },

  get(url, onSuccess, onError) {
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(data => { if (onSuccess) onSuccess(data); })
    .catch(err => {
      if (onError) onError(err);
      else Toast.error('Failed to load data.');
    });
  }
};

/* =============================================
   TOAST NOTIFICATIONS
   ============================================= */
const Toast = {
  container: null,

  init() {
    this.container = document.getElementById('toastContainer');
    if (!this.container) {
      this.container = document.createElement('div');
      this.container.className = 'toast-container';
      this.container.id = 'toastContainer';
      document.body.appendChild(this.container);
    }
  },

  show(message, type = 'info', duration = 4000) {
    if (!this.container) this.init();
    const icons = { success: String.fromCodePoint(0x2705), error: String.fromCodePoint(0x274C), info: String.fromCodePoint(0x2139, 0xFE0F), warning: String.fromCodePoint(0x26A0, 0xFE0F) };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type === 'error' ? 'error' : type === 'success' ? 'success' : 'info'}`;
    toast.innerHTML = `<span>${icons[type] || icons.info}</span><span>${message}</span>`;
    this.container.appendChild(toast);
    setTimeout(() => {
      toast.classList.add('hide');
      setTimeout(() => toast.remove(), 300);
    }, duration);
  },

  success(msg, d) { this.show(msg, 'success', d); },
  error(msg, d)   { this.show(msg, 'error', d); },
  info(msg, d)    { this.show(msg, 'info', d); }
};

/* =============================================
   ALERT BOX
   ============================================= */
const AlertBox = {
  show(containerId, message, type = 'danger') {
    const el = document.getElementById(containerId);
    if (!el) return;
    const iconMap = { success: String.fromCodePoint(0x2705), danger: String.fromCodePoint(0x274C), info: String.fromCodePoint(0x2139, 0xFE0F) };
    const icon = iconMap[type] || iconMap.info;
    el.innerHTML = `<div class="alert alert-${type}">
      <span>${icon}</span>
      <span>${message}</span>
    </div>`;
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  },
  clear(containerId) {
    const el = document.getElementById(containerId);
    if (el) el.innerHTML = '';
  }
};

/* =============================================
   CAR CARD BUILDER
   ============================================= */
function buildCarCard(car, showBookBtn = false) {
  const available = car.available == 1;
  const seatIcon = String.fromCodePoint(0x1F465);
  const fuelIcon = String.fromCodePoint(0x26FD);
  const gearIcon = String.fromCodePoint(0x2699, 0xFE0F);
  const checkIcon = String.fromCodePoint(0x2705);
  const crossIcon = String.fromCodePoint(0x274C);

  return `
    <div class="car-card" data-car-id="${car.id}" data-car-price="${car.price_per_day}">
      <div class="car-card-image">
        <img src="${car.image_url || '/car_rental/assets/images/cars/' + car.image}"
             alt="${car.brand} ${car.name}"
             onerror="this.src='/car_rental/assets/images/cars/placeholder.svg'">
        <span class="car-type-badge">${car.type}</span>
        <span class="car-avail-badge badge ${available ? 'badge-success' : 'badge-danger'}">
          ${available ? checkIcon + ' Available' : crossIcon + ' Booked'}
        </span>
      </div>
      <div class="car-card-body">
        <div class="car-brand">${car.brand}</div>
        <div class="car-name">${car.name}</div>
        <div class="car-specs">
          <span class="car-spec">${seatIcon} ${car.seats} Seats</span>
          <span class="car-spec">${fuelIcon} ${car.fuel_type}</span>
          <span class="car-spec">${gearIcon} ${car.transmission}</span>
        </div>
        ${car.description ? `<p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:8px;line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">${car.description}</p>` : ''}
        <div class="car-card-footer">
          <div>
            <div class="car-price">${car.price_formatted || 'Rs. ' + Number(car.price_per_day).toLocaleString('en-IN')}</div>
            <div class="car-price-label">per day</div>
          </div>
          ${showBookBtn && available
            ? `<button class="btn btn-primary btn-sm book-car-btn"
                       data-car-id="${car.id}"
                       data-car-name="${car.brand} ${car.name}"
                       data-car-price="${car.price_per_day}"
                       data-car-image="${car.image_url || ''}">
                 Book Now
               </button>`
            : available
              ? `<a href="/car_rental/pages/login.php" class="btn btn-primary btn-sm">Login to Book</a>`
              : `<span class="badge badge-danger">Unavailable</span>`
          }
        </div>
      </div>
    </div>`;
}

/* =============================================
   NAVBAR - Scroll + Mobile
   ============================================= */
document.addEventListener('DOMContentLoaded', () => {
  Toast.init();

  // Navbar scroll
  const navbar = document.getElementById('navbar');
  if (navbar) {
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });

    // Active nav link on scroll
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');
    window.addEventListener('scroll', () => {
      let current = '';
      sections.forEach(sec => {
        if (window.scrollY >= sec.offsetTop - 100) current = sec.id;
      });
      navLinks.forEach(a => {
        a.classList.remove('active');
        if (a.getAttribute('href') === '#' + current) a.classList.add('active');
      });
    }, { passive: true });
  }

  // Hamburger
  const hamburger = document.getElementById('hamburger');
  const navLinks  = document.getElementById('navLinks');
  if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      hamburger.classList.toggle('open');
    });
    document.addEventListener('click', e => {
      if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
        navLinks.classList.remove('open');
      }
    });
  }

  // Sidebar toggle (dashboard)
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar       = document.getElementById('sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
    document.addEventListener('click', e => {
      if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    });
  }

  // Animate on scroll (simple version)
  const aoEls = document.querySelectorAll('[data-aos]');
  if (aoEls.length) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('animate-fadeInUp');
          observer.unobserve(e.target);
        }
      });
    }, { threshold: 0.1 });
    aoEls.forEach(el => observer.observe(el));
  }
});

/* Export */
window.Ajax    = Ajax;
window.Toast   = Toast;
window.AlertBox = AlertBox;
window.buildCarCard = buildCarCard;
