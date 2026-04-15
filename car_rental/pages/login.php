<?php
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? '/car_rental/pages/admin.php' : '/car_rental/pages/dashboard.php'));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — DriveEase Car Rental</title>
  <meta name="description" content="Login to DriveEase to book premium cars, view your bookings, and manage your account.">
  <link rel="stylesheet" href="/car_rental/assets/css/style.css">
  <link rel="stylesheet" href="/car_rental/assets/css/pages/auth.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚗</text></svg>">
</head>
<body class="auth-body">
  <div class="auth-bg">
    <div class="auth-bg-shape s1"></div>
    <div class="auth-bg-shape s2"></div>
    <div class="auth-bg-shape s3"></div>
  </div>

  <div class="auth-wrapper">
    <!-- Left Panel -->
    <div class="auth-visual">
      <a href="/car_rental/" class="auth-logo">🚗 DriveEase</a>
      <div class="auth-visual-content">
        <img src="/car_rental/assets/images/cars/car_sedan.png" alt="Car" class="auth-car animate-float">
        <h2>Welcome Back, <br>Road Warrior!</h2>
        <p>Your next adventure is just a login away. Premium cars, honest prices, instant booking.</p>
        <div class="auth-badges">
          <span class="auth-badge">✅ Secure Login</span>
          <span class="auth-badge">🔒 Encrypted</span>
          <span class="auth-badge">⚡ Instant Access</span>
        </div>
      </div>
    </div>

    <!-- Right Panel — Form -->
    <div class="auth-form-panel">
      <div class="auth-form-box">
        <div class="auth-form-header">
          <h1>Sign In</h1>
          <p>Don't have an account? <a href="/car_rental/pages/signup.php">Create one free →</a></p>
        </div>

        <div id="loginAlert"></div>

        <form id="loginForm" novalidate>
          <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-group">
              <span class="input-icon">✉️</span>
              <input type="email" id="email" name="email" class="form-control"
                     placeholder="you@example.com" required autocomplete="email">
            </div>
            <div class="field-error" id="emailError"></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
              <span class="input-icon">🔒</span>
              <input type="password" id="password" name="password" class="form-control"
                     placeholder="Enter your password" required autocomplete="current-password">
              <span class="input-icon-right" id="togglePassword" title="Show/Hide Password">👁️</span>
            </div>
            <div class="field-error" id="passwordError"></div>
          </div>

          <div class="form-options">
            <label class="checkbox-label">
              <input type="checkbox" id="rememberMe"> Remember me
            </label>
            <a href="forgot_password.php">Forgot password?</a>
          </div>

          <button type="submit" class="btn btn-primary btn-full" id="loginBtn">
            <span id="loginBtnText">Sign In</span>
            <span id="loginSpinner" class="spinner" style="display:none;"></span>
          </button>
        </form>

        <div class="demo-credentials">
          <p>Demo Credentials:</p>
          <div class="demo-cred-grid">
            <div class="demo-cred" id="fillUser">
              <span>👤 User</span>
              <small>demo@carrental.com</small>
            </div>
            <div class="demo-cred" id="fillAdmin">
              <span>⚙️ Admin</span>
              <small>admin@carrental.com</small>
            </div>
          </div>
        </div>

        <div class="auth-back">
          <a href="/car_rental/">← Back to Home</a>
        </div>
      </div>
    </div>
  </div>

  <div class="toast-container" id="toastContainer"></div>
  <script src="/car_rental/assets/js/main.js"></script>
  <script src="/car_rental/assets/js/login.js"></script>
</body>
</html>