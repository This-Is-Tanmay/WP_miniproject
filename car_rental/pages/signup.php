<?php
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header('Location: /car_rental/pages/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up — DriveEase Car Rental</title>
  <meta name="description" content="Create a free DriveEase account and start booking premium cars instantly.">
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
        <img src="/car_rental/assets/images/cars/car_suv.png" alt="Car" class="auth-car animate-float">
        <h2>Join the <br>DriveEase Family!</h2>
        <p>Create your free account in seconds and unlock access to our entire premium fleet.</p>
        <div class="auth-badges">
          <span class="auth-badge">🎉 Free to Join</span>
          <span class="auth-badge">⚡ Instant Booking</span>
          <span class="auth-badge">🛡️ Secure</span>
        </div>
      </div>
    </div>

    <!-- Right Panel -->
    <div class="auth-form-panel">
      <div class="auth-form-box">
        <div class="auth-form-header">
          <h1>Create Account</h1>
          <p>Already have an account? <a href="/car_rental/pages/login.php">Sign in →</a></p>
        </div>

        <div id="signupAlert"></div>

        <form id="signupForm" novalidate>
          <div class="form-group">
            <label class="form-label" for="full_name">Full Name</label>
            <div class="input-group">
              <span class="input-icon">👤</span>
              <input type="text" id="full_name" name="full_name" class="form-control"
                     placeholder="John Doe" required minlength="2">
            </div>
            <div class="field-error" id="nameError"></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <div class="input-group">
              <span class="input-icon">✉️</span>
              <input type="email" id="email" name="email" class="form-control"
                     placeholder="you@example.com" required autocomplete="email">
              <span class="input-icon-right email-status" id="emailStatus" style="display:none;"></span>
            </div>
            <div class="field-error" id="emailError"></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="phone">Phone Number <span style="color:var(--text-muted);font-weight:400">(optional)</span></label>
            <div class="input-group">
              <span class="input-icon">📞</span>
              <input type="tel" id="phone" name="phone" class="form-control"
                     placeholder="+91-9876543210">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-group">
              <span class="input-icon">🔒</span>
              <input type="password" id="password" name="password" class="form-control"
                     placeholder="Min. 6 characters" required minlength="6">
              <span class="input-icon-right" id="togglePassword" title="Toggle">👁️</span>
            </div>
            <div class="password-strength" id="passwordStrength" style="display:none">
              <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
              <span class="strength-label" id="strengthLabel">Weak</span>
            </div>
            <div class="field-error" id="passwordError"></div>
          </div>

          <div class="form-group">
            <label class="form-label" for="confirm_password">Confirm Password</label>
            <div class="input-group">
              <span class="input-icon">🔒</span>
              <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                     placeholder="Re-enter password" required>
            </div>
            <div class="field-error" id="confirmError"></div>
          </div>

          <div class="form-group" style="margin-top:10px;">
            <label class="checkbox-label">
              <input type="checkbox" id="agreeTerms" required>
              I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </label>
          </div>

          <button type="submit" class="btn btn-primary btn-full" id="signupBtn">
            <span id="signupBtnText">🚀 Create Account</span>
            <span id="signupSpinner" class="spinner" style="display:none;"></span>
          </button>
        </form>

        <div class="auth-back">
          <a href="/car_rental/">← Back to Home</a>
        </div>
      </div>
    </div>
  </div>

  <div class="toast-container" id="toastContainer"></div>
  <script src="/car_rental/assets/js/main.js"></script>
  <script src="/car_rental/assets/js/signup.js"></script>
</body>
</html>
