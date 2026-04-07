<?php
require_once '../includes/auth.php';
requireAdmin();
$adminName = htmlspecialchars($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel — DriveEase</title>
  <link rel="stylesheet" href="/car_rental/assets/css/style.css">
  <link rel="stylesheet" href="/car_rental/assets/css/pages/admin.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚙️</text></svg>">
</head>
<body class="dashboard-body">

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo">⚙️ Admin Panel</div>
  </div>
  <nav class="sidebar-nav">
    <a href="#" class="sidebar-link active" data-tab="overview">📊 Overview</a>
    <a href="#" class="sidebar-link" data-tab="cars">🚗 Manage Cars</a>
    <a href="#" class="sidebar-link" data-tab="bookings">📋 All Bookings</a>
    <a href="#" class="sidebar-link" data-tab="users">👥 Users</a>
  </nav>
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="su-avatar" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">
        <?= strtoupper(substr($adminName, 0, 1)) ?>
      </div>
      <div class="su-info">
        <div class="su-name"><?= $adminName ?></div>
        <div class="su-email" style="color:#f59e0b">⚙️ Administrator</div>
      </div>
    </div>
    <div style="display:flex;gap:8px;margin-top:12px;">
      <a href="/car_rental/pages/dashboard.php" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">User View</a>
      <a href="/car_rental/pages/logout.php"    class="btn btn-danger  btn-sm" style="flex:1;justify-content:center;">Logout</a>
    </div>
  </div>
</aside>

<!-- MAIN -->
<div class="dashboard-main">
  <header class="dash-topbar">
    <button class="sidebar-toggle" id="sidebarToggle">☰</button>
    <div class="dash-welcome">
      <h2>Admin Dashboard <span class="badge badge-warning">⚙️ Admin</span></h2>
      <p>Manage your car rental system</p>
    </div>
  </header>

  <div class="dash-content">

    <!-- ========== OVERVIEW ========== -->
    <div id="tab-overview" class="admin-tab">
      <div class="stats-grid" id="statsGrid">
        <div class="stat-card sk"><div class="spinner spinner-dark"></div></div>
        <div class="stat-card sk"></div>
        <div class="stat-card sk"></div>
        <div class="stat-card sk"></div>
        <div class="stat-card sk"></div>
      </div>
      <div style="margin-top:32px;">
        <h3 style="margin-bottom:16px;">Recent Bookings</h3>
        <div id="recentBookings" class="table-wrap">
          <div class="cars-loading"><div class="spinner spinner-dark"></div></div>
        </div>
      </div>
    </div>

    <!-- ========== CARS ========== -->
    <div id="tab-cars" class="admin-tab" style="display:none;">
      <div class="section-head">
        <div><h3>Manage Cars</h3><p>Add, edit, or remove vehicles from your fleet</p></div>
        <button class="btn btn-primary" id="openAddCarModal">+ Add New Car</button>
      </div>
      <div id="adminCarsTable" class="table-wrap">
        <div class="cars-loading"><div class="spinner spinner-dark"></div></div>
      </div>
    </div>

    <!-- ========== BOOKINGS ========== -->
    <div id="tab-bookings" class="admin-tab" style="display:none;">
      <div class="section-head">
        <div><h3>All Bookings</h3><p>Track and manage customer reservations</p></div>
      </div>
      <div id="adminBookingsTable" class="table-wrap">
        <div class="cars-loading"><div class="spinner spinner-dark"></div></div>
      </div>
    </div>

    <!-- ========== USERS ========== -->
    <div id="tab-users" class="admin-tab" style="display:none;">
      <div class="section-head">
        <div><h3>Registered Users</h3><p>View all registered customers</p></div>
      </div>
      <div id="adminUsersTable" class="table-wrap">
        <div class="cars-loading"><div class="spinner spinner-dark"></div></div>
      </div>
    </div>
  </div>
</div>

<!-- ====== ADD / EDIT CAR MODAL ====== -->
<div class="modal-overlay" id="carModal">
  <div class="modal" style="max-width:600px;">
    <div class="modal-header">
      <h3 class="modal-title" id="carModalTitle">Add New Car</h3>
      <button class="modal-close" id="closeCarModal">✕</button>
    </div>
    <div id="carModalAlert"></div>
    <form id="carForm" enctype="multipart/form-data">
      <input type="hidden" id="carId" name="id">
      <div class="form-grid-2">
        <div class="form-group">
          <label class="form-label">Car Name *</label>
          <input type="text" class="form-control" id="carName" name="name" placeholder="e.g. City" required>
        </div>
        <div class="form-group">
          <label class="form-label">Brand *</label>
          <input type="text" class="form-control" id="carBrand" name="brand" placeholder="e.g. Honda" required>
        </div>
        <div class="form-group">
          <label class="form-label">Type *</label>
          <select class="form-control" id="carType" name="type" required>
            <option value="">Select type</option>
            <option>Sedan</option><option>SUV</option><option>Hatchback</option>
            <option>Luxury</option><option>Convertible</option><option>Van</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Seats *</label>
          <input type="number" class="form-control" id="carSeats" name="seats" min="2" max="15" value="5" required>
        </div>
        <div class="form-group">
          <label class="form-label">Price/Day (₹) *</label>
          <input type="number" class="form-control" id="carPrice" name="price" min="100" step="50" required>
        </div>
        <div class="form-group">
          <label class="form-label">Fuel Type</label>
          <select class="form-control" id="carFuel" name="fuel_type">
            <option>Petrol</option><option>Diesel</option><option>Electric</option><option>Hybrid</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Transmission</label>
          <select class="form-control" id="carTransmission" name="transmission">
            <option>Automatic</option><option>Manual</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Available</label>
          <select class="form-control" id="carAvailable" name="available">
            <option value="1">Yes</option><option value="0">No</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-control" id="carDesc" name="description" rows="3" placeholder="Brief description of the car..."></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Car Image</label>
        <input type="file" class="form-control" id="carImage" name="image" accept="image/*">
        <small style="color:var(--text-muted)">JPG, PNG, WEBP accepted. Leave blank to keep existing image.</small>
      </div>
      <div style="display:flex;gap:12px;margin-top:8px;">
        <button type="submit" class="btn btn-primary" id="saveCarBtn">
          <span id="saveBtnText">Save Car</span>
          <span id="saveSpinner" class="spinner" style="display:none;"></span>
        </button>
        <button type="button" class="btn btn-outline" id="cancelCarModal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal-overlay" id="confirmModal">
  <div class="modal" style="max-width:400px;text-align:center;">
    <div style="font-size:3rem;margin-bottom:16px;">🗑️</div>
    <h3 style="margin-bottom:8px;">Delete Car?</h3>
    <p style="color:var(--text-muted);margin-bottom:24px;">This action cannot be undone. All related bookings will also be removed.</p>
    <div style="display:flex;gap:12px;justify-content:center;">
      <button class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
      <button class="btn btn-outline" id="cancelDeleteBtn">Cancel</button>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>
<script src="/car_rental/assets/js/main.js"></script>
<script src="/car_rental/assets/js/admin.js"></script>
</body>
</html>
