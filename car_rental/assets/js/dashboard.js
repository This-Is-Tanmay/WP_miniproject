/**
 * dashboard.js â€” User Dashboard Logic
 */
'use strict';

const DashApp = {
  currentSection: 'browse',
  selectedCar: null,

  init() {
    this.initNav();
    this.loadCars('All');
    this.initFilters();
    this.initBookingModal();
  },

  initNav() {
    document.querySelectorAll('.sidebar-link[data-section]').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        this.switchSection(link.dataset.section);
      });
    });
  },

  switchSection(section) {
    this.currentSection = section;
    // Hide all sections
    document.querySelectorAll('.dash-section').forEach(s => s.style.display = 'none');
    document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));

    // Show target
    const target = document.getElementById('section-' + section);
    if (target) target.style.display = 'block';
    document.querySelector(`.sidebar-link[data-section="${section}"]`)?.classList.add('active');

    if (section === 'bookings') this.loadBookings();
    if (section === 'browse')   this.loadCars('All');
  },

  loadCars(type = 'All') {
    const grid = document.getElementById('dashCarsGrid');
    if (!grid) return;
    grid.innerHTML = '<div class="cars-loading"><div class="spinner spinner-dark"></div><p>Loading...</p></div>';
    Ajax.get(`/car_rental/ajax/get_cars.php?type=${encodeURIComponent(type)}&available=1`, data => {
      if (data.success && data.data.length > 0) {
        grid.innerHTML = data.data.map(car => buildCarCard(car, true)).join('');
        this.attachBookBtns();
      } else {
        grid.innerHTML = `<div class="cars-loading" style="grid-column:1/-1">
          <div style="font-size:2.5rem">ðŸš—</div><h4>No cars available</h4>
          <p>No cars found in this category. Try another filter.</p></div>`;
      }
    });
  },

  initFilters() {
    document.querySelectorAll('#dashCarFilters .filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('#dashCarFilters .filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        this.loadCars(btn.dataset.type || 'All');
      });
    });
  },

  loadBookings() {
    const container = document.getElementById('bookingsContainer');
    if (!container) return;
    container.innerHTML = '<div class="cars-loading"><div class="spinner spinner-dark"></div><p>Loading bookings...</p></div>';
    Ajax.get('/car_rental/ajax/get_bookings.php', data => {
      if (!data.success) { container.innerHTML = `<div class="cars-loading"><p>Failed to load bookings.</p></div>`; return; }
      if (!data.data.length) {
        container.innerHTML = `<div class="empty-state">
          <div class="empty-icon">ðŸ“‹</div>
          <h4>No bookings yet</h4>
          <p>You haven't booked any cars yet. Browse our fleet and book your first ride!</p>
          <button class="btn btn-primary" style="margin-top:16px" onclick="DashApp.switchSection('browse')">Browse Cars</button>
        </div>`;
        return;
      }
      const statusColors = { confirmed:'success', pending:'warning', cancelled:'danger', completed:'primary' };
      container.innerHTML = `
        <div class="bookings-table-wrap">
          <table>
            <thead><tr>
              <th>Car</th><th>Pickup</th><th>Return</th><th>Days</th><th>Total</th><th>Status</th><th>Booked On</th>
            </tr></thead>
            <tbody>
              ${data.data.map(b => `
                <tr>
                  <td>
                    <div class="booking-car-info">
                      <img class="booking-car-thumb" src="${b.car_image_url}" alt="${b.car_name}"
                           onerror="this.src='/car_rental/assets/images/cars/placeholder.svg'">
                      <div><strong>${b.car_brand} ${b.car_name}</strong><br>
                           <small style="color:var(--text-muted)">${b.car_type}</small></div>
                    </div>
                  </td>
                  <td>${b.start_date_fmt}</td>
                  <td>${b.end_date_fmt}</td>
                  <td>${b.total_days} day(s)</td>
                  <td><strong style="color:var(--primary)">${b.total_price_fmt}</strong></td>
                  <td><span class="badge badge-${statusColors[b.status] || 'muted'}">${b.status}</span></td>
                  <td>${b.created_at_fmt}</td>
                </tr>`).join('')}
            </tbody>
          </table>
        </div>`;
    });
  },

  initBookingModal() {
    const modal    = document.getElementById('bookingModal');
    const closeBtn = document.getElementById('closeBookingModal');
    const form     = document.getElementById('bookingForm');
    const startIn  = document.getElementById('startDate');
    const endIn    = document.getElementById('endDate');

    if (!modal) return;

    closeBtn?.addEventListener('click', () => modal.classList.remove('active'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('active'); });

    // Set min dates
    this.setMinDates();

    // Auto-calculate summary
    [startIn, endIn].forEach(el => {
      el?.addEventListener('change', () => this.updateBookingSummary());
    });

    // Form submit
    form?.addEventListener('submit', e => {
      e.preventDefault();
      AlertBox.clear('bookingAlert');

      const carId     = document.getElementById('bCarId').value;
      const startDate = startIn.value;
      const endDate   = endIn.value;
      const submitBtn = document.getElementById('bookSubmitBtn');
      const btnTxt    = document.getElementById('bookBtnText');
      const sp        = document.getElementById('bookSpinner');

      if (!startDate || !endDate) { AlertBox.show('bookingAlert', 'Please select both dates.', 'danger'); return; }
      if (endDate <= startDate)   { AlertBox.show('bookingAlert', 'Return date must be after pickup.', 'danger'); return; }

      submitBtn.disabled = true;
      btnTxt.textContent = 'Confirming...';
      sp.style.display   = 'inline-block';

      Ajax.send('/car_rental/ajax/book_car.php', {
        data: { car_id: carId, start_date: startDate, end_date: endDate },
        onSuccess: data => {
          if (data.success) {
            modal.classList.remove('active');
            Toast.success(data.message, 6000);
            this.loadCars('All');
          } else {
            AlertBox.show('bookingAlert', data.message, 'danger');
          }
          submitBtn.disabled = false;
          btnTxt.textContent = 'Confirm Booking';
          sp.style.display   = 'none';
        },
        onError: err => {
          AlertBox.show('bookingAlert', err.message, 'danger');
          submitBtn.disabled = false;
          btnTxt.textContent = 'Confirm Booking';
          sp.style.display   = 'none';
        }
      });
    });
  },

  setMinDates() {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    const startIn = document.getElementById('startDate');
    const endIn   = document.getElementById('endDate');
    if (startIn) startIn.min = today;
    if (endIn)   endIn.min   = tomorrow;
  },

  updateBookingSummary() {
    const startDate = document.getElementById('startDate').value;
    const endDate   = document.getElementById('endDate').value;
    const summary   = document.getElementById('bookingSummary');
    if (!startDate || !endDate || !this.selectedCar) return;
    const days  = Math.ceil((new Date(endDate) - new Date(startDate)) / 86400000);
    if (days < 1) return;
    const total = days * this.selectedCar.price;
    document.getElementById('bsDays').textContent    = `${days} day(s)`;
    document.getElementById('bsPriceDay').textContent = `â‚¹${Number(this.selectedCar.price).toLocaleString('en-IN')}`;
    document.getElementById('bsTotal').textContent   = `â‚¹${total.toLocaleString('en-IN')}`;
    summary.style.display = 'block';
    document.getElementById('endDate').min = new Date(new Date(startDate).getTime() + 86400000).toISOString().split('T')[0];
  },

  attachBookBtns() {
    document.querySelectorAll('.book-car-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const carId    = btn.dataset.carId;
        const carName  = btn.dataset.carName;
        const carPrice = btn.dataset.carPrice;
        const carImage = btn.dataset.carImage;
        this.openBookingModal(carId, carName, carPrice, carImage);
      });
    });
  },

  openBookingModal(carId, carName, carPrice, carImage) {
    this.selectedCar = { id: carId, name: carName, price: carPrice };
    document.getElementById('bCarId').value = carId;
    document.getElementById('bookModalTitle').textContent = `ðŸ“… Book ${carName}`;
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value   = '';
    document.getElementById('bookingSummary').style.display = 'none';
    AlertBox.clear('bookingAlert');

    const preview = document.getElementById('carBookingPreview');
    preview.innerHTML = `
      <img src="${carImage}" alt="${carName}" style="width:80px;height:55px;object-fit:cover;border-radius:10px;background:var(--bg-2)"
           onerror="this.src='/car_rental/assets/images/cars/placeholder.svg'">
      <div>
        <div style="font-weight:700;color:var(--text-heading)">${carName}</div>
        <div style="color:var(--primary);font-weight:800;font-size:1.1rem">â‚¹${Number(carPrice).toLocaleString('en-IN')}<span style="font-size:0.8rem;font-weight:500;color:var(--text-muted)">/day</span></div>
      </div>`;

    this.setMinDates();
    document.getElementById('bookingModal').classList.add('active');
  }
};

document.addEventListener('DOMContentLoaded', () => DashApp.init());

