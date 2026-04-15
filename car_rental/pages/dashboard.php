<?php
require_once '../includes/auth.php';
requireLogin();
$userName  = htmlspecialchars($_SESSION['user_name']);
$userEmail = htmlspecialchars($_SESSION['user_email']);
$userRole  = $_SESSION['user_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — DriveEase Car Rental</title>
  <link rel="stylesheet" href="/car_rental/assets/css/style.css">
  <link rel="stylesheet" href="/car_rental/assets/css/pages/home.css">
  <link rel="stylesheet" href="/car_rental/assets/css/pages/dashboard.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚗</text></svg>">
</head>
<body class="dashboard-body">

<!-- ---- SIDEBAR ---- -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo">🚗 DriveEase</div>
  </div>
  <nav class="sidebar-nav">
    <a href="#browse" class="sidebar-link active" data-section="browse">
      <span class="sl-icon">🚙</span> Browse Cars
    </a>
    <a href="#bookings" class="sidebar-link" data-section="bookings">
      <span class="sl-icon">📋</span> My Bookings
    </a>
    <?php if ($userRole === 'admin'): ?>
    <a href="/car_rental/pages/admin.php" class="sidebar-link">
      <span class="sl-icon">⚙️</span> Admin Panel
    </a>
    <?php endif; ?>
  </nav>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="su-avatar"><?= strtoupper(substr($userName, 0, 1)) ?></div>
      <div class="su-info">
        <div class="su-name"><?= $userName ?></div>
        <div class="su-email"><?= $userEmail ?></div>
      </div>
    </div>
    <a href="/car_rental/pages/logout.php" class="btn btn-outline btn-sm" style="margin-top:12px;width:100%;justify-content:center;">
      🚪 Logout
    </a>
  </div>
</aside>

<!-- ---- MAIN ---- -->
<div class="dashboard-main">
  <!-- Top Bar -->
  <header class="dash-topbar">
    <button class="sidebar-toggle" id="sidebarToggle">☰</button>
    <div class="dash-welcome">
      <h2>Good day, <span class="text-gradient"><?= $userName ?></span>! 👋</h2>
      <p>Ready for your next adventure?</p>
    </div>
    <a href="/car_rental/pages/logout.php" class="btn btn-outline btn-sm dash-logout">🚪 Logout</a>
  </header>

  <!-- Content -->
  <div class="dash-content">

    <!-- ===== BROWSE CARS SECTION ===== -->
    <div id="section-browse" class="dash-section">
      <div class="section-head">
        <div>
          <h3>Available Cars</h3>
          <p>Choose your next ride from our premium fleet</p>
        </div>
        <div class="filter-tabs mini" id="dashCarFilters">
          <button class="filter-btn active" data-type="All">All</button>
          <button class="filter-btn" data-type="SUV">SUV</button>
          <button class="filter-btn" data-type="Sedan">Sedan</button>
          <button class="filter-btn" data-type="Hatchback">Hatchback</button>
          <button class="filter-btn" data-type="Luxury">Luxury</button>
          <button class="filter-btn" data-type="Van">Van</button>
        </div>
      </div>
      <div class="cars-grid dash-cars" id="dashCarsGrid">
        <div class="cars-loading"><div class="spinner spinner-dark"></div><p>Loading...</p></div>
      </div>
    </div>

    <!-- ===== BOOKINGS SECTION ===== -->
    <div id="section-bookings" class="dash-section" style="display:none;">
      <div class="section-head">
        <div>
          <h3>My Bookings</h3>
          <p>Your complete rental history</p>
        </div>
        <button class="btn btn-primary btn-sm" onclick="DashApp.switchSection('browse')">+ Book a Car</button>
      </div>
      <div id="bookingsContainer">
        <div class="cars-loading"><div class="spinner spinner-dark"></div><p>Loading bookings...</p></div>
      </div>
    </div>
  </div>
  <footer style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 0.9rem;">
    <p>Made by: ITA443, ITA449, ITA459, ITA460</p>
  </footer>
</div>

<!-- ===== BOOKING MODAL ===== -->
<div class="modal-overlay" id="bookingModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title" id="bookModalTitle">📅 Book Car</h3>
      <button class="modal-close" id="closeBookingModal">✕</button>
    </div>
    <div id="bookModalContent">
      <div class="car-booking-preview" id="carBookingPreview"></div>
      <div id="bookingAlert"></div>
      <form id="bookingForm">
        <input type="hidden" id="bCarId" name="car_id">
        <div class="booking-dates">
          <div class="form-group">
            <label class="form-label">Pickup Date</label>
            <input type="date" class="form-control" id="startDate" name="start_date" required>
          </div>
          <div class="form-group">
            <label class="form-label">Return Date</label>
            <input type="date" class="form-control" id="endDate" name="end_date" required>
          </div>
        </div>
        <div class="booking-summary" id="bookingSummary" style="display:none;">
          <div class="bs-row"><span>Duration:</span><strong id="bsDays">—</strong></div>
          <div class="bs-row"><span>Price/day:</span><strong id="bsPriceDay">—</strong></div>
          <div class="bs-row bsTotal"><span>Total Price:</span><strong id="bsTotal">—</strong></div>
        </div>
        <button type="submit" class="btn btn-primary btn-full" id="bookSubmitBtn">
          <span id="bookBtnText">Confirm Booking</span>
          <span id="bookSpinner" class="spinner" style="display:none;"></span>
        </button>
      </form>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>
<script src="/car_rental/assets/js/main.js"></script>
<script src="/car_rental/assets/js/dashboard.js"></script>
</body>
</html>
