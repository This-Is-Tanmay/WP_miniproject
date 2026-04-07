/**
 * admin.js â€” Admin Panel Logic
 */
'use strict';

const AdminApp = {
  currentTab: 'overview',
  deleteTargetId: null,

  init() {
    this.initTabs();
    this.loadStats();
    this.loadRecentBookings();
    this.initCarModal();
    this.initDeleteConfirm();
  },

  initTabs() {
    document.querySelectorAll('.sidebar-link[data-tab]').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        this.switchTab(link.dataset.tab);
      });
    });
  },

  switchTab(tab) {
    this.currentTab = tab;
    document.querySelectorAll('.admin-tab').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
    const target = document.getElementById('tab-' + tab);
    if (target) target.style.display = 'block';
    document.querySelector(`.sidebar-link[data-tab="${tab}"]`)?.classList.add('active');
    if (tab === 'cars')     this.loadCarsTable();
    if (tab === 'bookings') this.loadBookingsTable();
    if (tab === 'users')    this.loadUsersTable();
    if (tab === 'overview') { this.loadStats(); this.loadRecentBookings(); }
  },

  /* ===== STATS ===== */
  loadStats() {
    Ajax.get('/car_rental/ajax/admin_cars.php?action=stats', data => {
      if (!data.success) return;
      const s = data.data;
      document.getElementById('statsGrid').innerHTML = `
        <div class="stat-card"><div class="stat-card-icon">ðŸš—</div><div class="stat-card-value">${s.total_cars}</div><div class="stat-card-label">Total Cars</div></div>
        <div class="stat-card"><div class="stat-card-icon">âœ…</div><div class="stat-card-value">${s.available_cars}</div><div class="stat-card-label">Available</div></div>
        <div class="stat-card"><div class="stat-card-icon">ðŸ“‹</div><div class="stat-card-value">${s.total_bookings}</div><div class="stat-card-label">Bookings</div></div>
        <div class="stat-card"><div class="stat-card-icon">ðŸ‘¥</div><div class="stat-card-value">${s.total_users}</div><div class="stat-card-label">Users</div></div>
        <div class="stat-card"><div class="stat-card-icon">ðŸ’°</div><div class="stat-card-value" style="font-size:1.1rem">${s.total_revenue_fmt}</div><div class="stat-card-label">Revenue</div></div>`;
    });
  },

  /* ===== RECENT BOOKINGS ===== */
  loadRecentBookings() {
    Ajax.get('/car_rental/ajax/admin_cars.php?action=all_bookings', data => {
      if (!data.success) return;
      const recent = data.data.slice(0, 5);
      const sc = { confirmed:'success', pending:'warning', cancelled:'danger', completed:'primary' };
      document.getElementById('recentBookings').innerHTML = recent.length ? `
        <div class="table-wrap">
          <table><thead><tr><th>User</th><th>Car</th><th>Dates</th><th>Total</th><th>Status</th></tr></thead>
          <tbody>${recent.map(b => `<tr>
            <td><strong>${b.user_name}</strong><br><small style="color:var(--text-muted)">${b.user_email}</small></td>
            <td>${b.car_brand} ${b.car_name}</td>
            <td>${b.start_fmt} â†’ ${b.end_fmt}</td>
            <td><strong style="color:var(--primary)">${b.total_price_fmt}</strong></td>
            <td><span class="badge badge-${sc[b.status]||'muted'}">${b.status}</span></td>
          </tr>`).join('')}</tbody></table>
        </div>` : `<div class="empty-state"><div class="empty-icon">ðŸ“‹</div><p>No bookings yet.</p></div>`;
    });
  },

  /* ===== CARS TABLE ===== */
  loadCarsTable() {
    const el = document.getElementById('adminCarsTable');
    el.innerHTML = '<div class="cars-loading"><div class="spinner spinner-dark"></div></div>';
    Ajax.get('/car_rental/ajax/admin_cars.php?action=list', data => {
      if (!data.success || !data.data.length) {
        el.innerHTML = `<div class="empty-state"><div class="empty-icon">ðŸš—</div><p>No cars found. Add one!</p></div>`;
        return;
      }
      el.innerHTML = `
        <table><thead><tr><th>Image</th><th>Car</th><th>Type</th><th>Seats</th><th>Price/Day</th><th>Fuel</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>${data.data.map(c => `<tr>
          <td><img class="admin-car-thumb" src="${c.image_url}" alt="${c.name}"
               onerror="this.src='/car_rental/assets/images/cars/placeholder.svg'"></td>
          <td><strong>${c.brand} ${c.name}</strong></td>
          <td><span class="badge badge-primary">${c.type}</span></td>
          <td>ðŸ‘¥ ${c.seats}</td>
          <td><strong style="color:var(--primary)">â‚¹${Number(c.price_per_day).toLocaleString('en-IN')}</strong></td>
          <td>${c.fuel_type}</td>
          <td>
            <span class="badge badge-${c.available ? 'success' : 'danger'}" style="cursor:pointer" onclick="AdminApp.toggleAvailability(${c.id}, this)">
              ${c.available ? 'âœ… Available' : 'âŒ Unavailable'}
            </span>
          </td>
          <td><div class="action-btns">
            <button class="btn btn-success btn-sm" onclick="AdminApp.openEditModal(${c.id})">âœï¸ Edit</button>
            <button class="btn btn-danger  btn-sm" onclick="AdminApp.openDeleteConfirm(${c.id})">ðŸ—‘ï¸ Del</button>
          </div></td>
        </tr>`).join('')}</tbody></table>`;
    });
  },

  /* ===== BOOKINGS TABLE ===== */
  loadBookingsTable() {
    const el = document.getElementById('adminBookingsTable');
    el.innerHTML = '<div class="cars-loading"><div class="spinner spinner-dark"></div></div>';
    Ajax.get('/car_rental/ajax/admin_cars.php?action=all_bookings', data => {
      if (!data.success || !data.data.length) {
        el.innerHTML = `<div class="empty-state"><div class="empty-icon">ðŸ“‹</div><p>No bookings yet.</p></div>`;
        return;
      }
      const sc = { confirmed:'success', pending:'warning', cancelled:'danger', completed:'primary' };
      el.innerHTML = `
        <table><thead><tr><th>#</th><th>User</th><th>Car</th><th>Dates</th><th>Days</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>${data.data.map(b => `<tr>
          <td>#${b.id}</td>
          <td><strong>${b.user_name}</strong><br><small style="color:var(--text-muted)">${b.user_email}</small></td>
          <td>${b.car_brand} ${b.car_name} <small class="badge badge-primary">${b.car_type}</small></td>
          <td>${b.start_fmt} â†’ ${b.end_fmt}</td>
          <td>${b.total_days} day(s)</td>
          <td><strong style="color:var(--primary)">${b.total_price_fmt}</strong></td>
          <td><span class="badge badge-${sc[b.status]||'muted'}">${b.status}</span></td>
          <td>
            <select class="form-control" style="padding:6px;font-size:0.8rem;min-width:130px" onchange="AdminApp.updateBookingStatus(${b.id}, this.value)">
              ${['pending','confirmed','cancelled','completed'].map(s => `<option value="${s}" ${b.status===s?'selected':''}>${s}</option>`).join('')}
            </select>
          </td>
        </tr>`).join('')}</tbody></table>`;
    });
  },

  /* ===== USERS TABLE ===== */
  loadUsersTable() {
    fetch('/car_rental/ajax/admin_cars.php?action=stats', { headers: {'X-Requested-With':'XMLHttpRequest'} });
    const el = document.getElementById('adminUsersTable');
    el.innerHTML = '<div class="cars-loading"><div class="spinner spinner-dark"></div></div>';
    // We'll use a separate endpoint concept â€” for now query via admin
    fetch('/car_rental/ajax/admin_cars.php?action=all_bookings', { headers: {'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json())
    .then(data => {
      // Build unique users from bookings
      const userMap = {};
      (data.data || []).forEach(b => {
        if (!userMap[b.user_id || b.user_email]) {
          userMap[b.user_email] = { name: b.user_name, email: b.user_email, bookings: 0 };
        }
        userMap[b.user_email].bookings++;
      });
      const users = Object.values(userMap);
      if (!users.length) {
        el.innerHTML = `<div class="empty-state"><div class="empty-icon">ðŸ‘¥</div><p>No users with bookings yet.</p></div>`;
        return;
      }
      el.innerHTML = `
        <table><thead><tr><th>#</th><th>Name</th><th>Email</th><th>Total Bookings</th></tr></thead>
        <tbody>${users.map((u, i) => `<tr>
          <td>${i + 1}</td>
          <td><div class="su-avatar" style="display:inline-flex;width:32px;height:32px;font-size:.8rem;margin-right:8px">${u.name.charAt(0).toUpperCase()}</div><strong>${u.name}</strong></td>
          <td>${u.email}</td>
          <td><span class="badge badge-primary">${u.bookings} booking(s)</span></td>
        </tr>`).join('')}</tbody></table>`;
    });
  },

  /* ===== TOGGLE AVAILABILITY ===== */
  toggleAvailability(id, el) {
    Ajax.send('/car_rental/ajax/admin_cars.php', {
      data: { action: 'toggle', id },
      onSuccess: data => {
        if (data.success) {
          el.className = `badge badge-${data.available ? 'success' : 'danger'}`;
          el.innerHTML = data.available ? 'âœ… Available' : 'âŒ Unavailable';
          Toast.success(data.message);
        } else Toast.error(data.message);
      }
    });
  },

  /* ===== UPDATE BOOKING STATUS ===== */
  updateBookingStatus(id, status) {
    Ajax.send('/car_rental/ajax/admin_cars.php', {
      data: { action: 'update_booking', id, status },
      onSuccess: data => {
        if (data.success) Toast.success(data.message);
        else Toast.error(data.message);
      }
    });
  },

  /* ===== CAR MODAL ===== */
  initCarModal() {
    document.getElementById('openAddCarModal')?.addEventListener('click', () => this.openAddModal());
    document.getElementById('closeCarModal')?.addEventListener('click', () => this.closeCarModal());
    document.getElementById('cancelCarModal')?.addEventListener('click', () => this.closeCarModal());
    document.getElementById('carModal')?.addEventListener('click', e => { if (e.target.id === 'carModal') this.closeCarModal(); });
    document.getElementById('carForm')?.addEventListener('submit', e => { e.preventDefault(); this.saveCar(); });
  },

  openAddModal() {
    document.getElementById('carModalTitle').textContent = 'ðŸš— Add New Car';
    document.getElementById('carForm').reset();
    document.getElementById('carId').value = '';
    document.getElementById('carAvailable').value = '1';
    AlertBox.clear('carModalAlert');
    document.getElementById('carModal').classList.add('active');
  },

  openEditModal(id) {
    Ajax.get(`/car_rental/ajax/admin_cars.php?action=list`, data => {
      const car = (data.data || []).find(c => c.id == id);
      if (!car) { Toast.error('Car not found.'); return; }
      document.getElementById('carModalTitle').textContent = 'âœï¸ Edit Car';
      document.getElementById('carId').value           = car.id;
      document.getElementById('carName').value         = car.name;
      document.getElementById('carBrand').value        = car.brand;
      document.getElementById('carType').value         = car.type;
      document.getElementById('carSeats').value        = car.seats;
      document.getElementById('carPrice').value        = car.price_per_day;
      document.getElementById('carFuel').value         = car.fuel_type;
      document.getElementById('carTransmission').value = car.transmission;
      document.getElementById('carAvailable').value    = car.available;
      document.getElementById('carDesc').value         = car.description || '';
      AlertBox.clear('carModalAlert');
      document.getElementById('carModal').classList.add('active');
    });
  },

  closeCarModal() {
    document.getElementById('carModal').classList.remove('active');
  },

  saveCar() {
    const id       = document.getElementById('carId').value;
    const btn      = document.getElementById('saveCarBtn');
    const btnTxt   = document.getElementById('saveBtnText');
    const sp       = document.getElementById('saveSpinner');
    const action   = id ? 'edit' : 'add';

    btn.disabled = true;
    btnTxt.textContent = 'Saving...';
    sp.style.display   = 'inline-block';

    const fd = new FormData(document.getElementById('carForm'));
    fd.set('action', action);

    fetch('/car_rental/ajax/admin_cars.php', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: fd
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        Toast.success(data.message);
        this.closeCarModal();
        this.loadCarsTable();
        this.loadStats();
      } else {
        AlertBox.show('carModalAlert', data.message, 'danger');
      }
    })
    .catch(() => AlertBox.show('carModalAlert', 'Network error.', 'danger'))
    .finally(() => {
      btn.disabled = false;
      btnTxt.textContent = 'Save Car';
      sp.style.display = 'none';
    });
  },

  /* ===== DELETE CONFIRM ===== */
  initDeleteConfirm() {
    document.getElementById('confirmDeleteBtn')?.addEventListener('click', () => {
      if (!this.deleteTargetId) return;
      Ajax.send('/car_rental/ajax/admin_cars.php', {
        data: { action: 'delete', id: this.deleteTargetId },
        onSuccess: data => {
          document.getElementById('confirmModal').classList.remove('active');
          if (data.success) {
            Toast.success(data.message);
            this.loadCarsTable();
            this.loadStats();
          } else Toast.error(data.message);
          this.deleteTargetId = null;
        }
      });
    });
    document.getElementById('cancelDeleteBtn')?.addEventListener('click', () => {
      document.getElementById('confirmModal').classList.remove('active');
      this.deleteTargetId = null;
    });
  },

  openDeleteConfirm(id) {
    this.deleteTargetId = id;
    document.getElementById('confirmModal').classList.add('active');
  }
};

document.addEventListener('DOMContentLoaded', () => AdminApp.init());

