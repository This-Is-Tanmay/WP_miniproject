<?php
require_once 'includes/auth.php';

// Redirect logged-in users
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
  <title>DriveEase — Rent Your Perfect Car | Premium Car Rental</title>
  <meta name="description" content="DriveEase offers premium car rentals at affordable prices. Choose from SUVs, Sedans, Hatchbacks, Luxury cars and more. Book online instantly.">
  <link rel="stylesheet" href="/car_rental/assets/css/style.css">
  <link rel="stylesheet" href="/car_rental/assets/css/pages/home.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚗</text></svg>">
</head>
<body>

<!-- ===================== NAVBAR ===================== -->
<nav class="navbar" id="navbar">
  <div class="container">
    <div class="navbar-brand" onclick="window.location='/'">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3"/><polygon points="9 11 15 11 17 18 7 18 9 11"/><circle cx="7.5" cy="21" r="1.5"/><circle cx="17.5" cy="21" r="1.5"/><path d="M15 11h7"/></svg>
      DriveEase
    </div>
    <ul class="nav-links" id="navLinks">
      <li><a href="#home" class="nav-link active">Home</a></li>
      <li><a href="#cars" class="nav-link">Cars</a></li>
      <li><a href="#pricing" class="nav-link">Pricing</a></li>
      <li><a href="#about" class="nav-link">About</a></li>
      <li><a href="#contact" class="nav-link">Contact</a></li>
      <li><a href="/car_rental/pages/login.php" class="btn-nav">Login</a></li>
    </ul>
    <button class="hamburger" id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- ===================== HERO ===================== -->
<section class="hero" id="home">
  <div class="hero-bg-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
  </div>
  <div class="container">
    <div class="hero-content">
      <div class="hero-text">
        <div class="hero-badge animate-fadeInUp">🏆 India's #1 Car Rental Platform</div>
        <h1 class="hero-title animate-fadeInUp delay-1">
          Rent Your <span class="text-gradient">Perfect Car</span><br>For Any Journey
        </h1>
        <p class="hero-subtitle animate-fadeInUp delay-2">
          Choose from our premium fleet of 100+ cars. Transparent pricing, instant booking, and 24/7 support. Your dream ride is just a click away.
        </p>
        <div class="hero-actions animate-fadeInUp delay-3">
          <a href="/car_rental/pages/signup.php" class="btn btn-primary btn-lg">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            Book Now — It's Free
          </a>
          <a href="#cars" class="btn btn-outline btn-lg">View Fleet</a>
        </div>
        <div class="hero-stats animate-fadeInUp delay-4">
          <div class="stat-item">
            <span class="stat-number">500+</span>
            <span class="stat-label">Happy Customers</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">6+</span>
            <span class="stat-label">Car Models</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">24/7</span>
            <span class="stat-label">Support</span>
          </div>
        </div>
      </div>
      <div class="hero-visual animate-fadeInUp delay-2">
        <div class="car-showcase">
          <img src="/car_rental/assets/images/cars/car_luxury.png" alt="Luxury Car" class="hero-car animate-float" id="heroCar">
          <div class="car-glow"></div>
        </div>
        <div class="floating-card card-fuel">
          <span class="fc-icon">⛽</span>
          <div>
            <div class="fc-title">Fuel Included</div>
            <div class="fc-sub">First 100km free</div>
          </div>
        </div>
        <div class="floating-card card-price">
          <span class="fc-icon">💰</span>
          <div>
            <div class="fc-title">From ₹900/day</div>
            <div class="fc-sub">No hidden charges</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="hero-wave">
    <svg viewBox="0 0 1440 80" preserveAspectRatio="none"><path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#F4F3FF"/></svg>
  </div>
</section>

<!-- ===================== FEATURES ===================== -->
<section class="features-section section-sm">
  <div class="container">
    <div class="features-grid">
      <div class="feature-card" data-aos>
        <div class="feature-icon" style="background:rgba(108,99,255,0.12)">🚀</div>
        <h3>Instant Booking</h3>
        <p>Book your car in under 2 minutes. No lengthy paperwork. Just pick, confirm, and go.</p>
      </div>
      <div class="feature-card" data-aos>
        <div class="feature-icon" style="background:rgba(16,185,129,0.12)">🛡️</div>
        <h3>Fully Insured</h3>
        <p>All our vehicles come with comprehensive insurance coverage for your peace of mind.</p>
      </div>
      <div class="feature-card" data-aos>
        <div class="feature-icon" style="background:rgba(245,158,11,0.12)">💎</div>
        <h3>Premium Fleet</h3>
        <p>Regularly maintained vehicles from top brands. SUVs, Sedans, Luxury, Convertibles & more.</p>
      </div>
      <div class="feature-card" data-aos>
        <div class="feature-icon" style="background:rgba(239,68,68,0.12)">📍</div>
        <h3>Free Pickup</h3>
        <p>We deliver your car to your doorstep at no extra cost within city limits.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CARS SECTION ===================== -->
<section class="cars-section section" id="cars">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Our Fleet</div>
      <h2 class="section-title">Find Your <span class="text-gradient">Perfect Ride</span></h2>
      <p class="section-subtitle">Explore our diverse fleet ranging from budget-friendly hatchbacks to ultra-luxury sedans.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs" id="carFilters">
      <button class="filter-btn active" data-type="All">All Cars</button>
      <button class="filter-btn" data-type="SUV">SUV</button>
      <button class="filter-btn" data-type="Sedan">Sedan</button>
      <button class="filter-btn" data-type="Hatchback">Hatchback</button>
      <button class="filter-btn" data-type="Luxury">Luxury</button>
      <button class="filter-btn" data-type="Convertible">Convertible</button>
      <button class="filter-btn" data-type="Van">Van</button>
    </div>

    <!-- Cars Grid -->
    <div class="cars-grid" id="carsGrid">
      <div class="cars-loading">
        <div class="spinner spinner-dark"></div>
        <p>Loading cars...</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== PRICING ===================== -->
<section class="pricing-section section" id="pricing" style="background:linear-gradient(135deg,#F4F3FF,#EDE9FE);">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Transparent Pricing</div>
      <h2 class="section-title">Simple, <span class="text-gradient">Honest Pricing</span></h2>
      <p class="section-subtitle">No hidden fees. No surprises. What you see is what you pay.</p>
    </div>
    <div class="pricing-grid">
      <div class="pricing-card">
        <div class="pricing-icon">🏙️</div>
        <h3>City Drive</h3>
        <div class="price-range">₹900 – ₹1,500<span>/day</span></div>
        <p>Hatchbacks & Sedans</p>
        <ul class="pricing-features">
          <li>✅ 100km free daily</li>
          <li>✅ Insurance included</li>
          <li>✅ Free city pickup</li>
          <li>✅ 24/7 roadside support</li>
        </ul>
        <a href="/car_rental/pages/signup.php" class="btn btn-outline">Book Now</a>
      </div>
      <div class="pricing-card pricing-featured">
        <div class="popular-badge">Most Popular</div>
        <div class="pricing-icon">🚙</div>
        <h3>Family SUV</h3>
        <div class="price-range">₹2,000 – ₹2,500<span>/day</span></div>
        <p>SUVs & Vans</p>
        <ul class="pricing-features">
          <li>✅ 200km free daily</li>
          <li>✅ Full insurance</li>
          <li>✅ Free outstation pickup</li>
          <li>✅ Priority support</li>
        </ul>
        <a href="/car_rental/pages/signup.php" class="btn btn-primary">Book Now</a>
      </div>
      <div class="pricing-card">
        <div class="pricing-icon">👑</div>
        <h3>Luxury Class</h3>
        <div class="price-range">₹5,000 – ₹8,000<span>/day</span></div>
        <p>Luxury & Convertibles</p>
        <ul class="pricing-features">
          <li>✅ Unlimited km</li>
          <li>✅ Premium insurance</li>
          <li>✅ Chauffeur option</li>
          <li>✅ Dedicated concierge</li>
        </ul>
        <a href="/car_rental/pages/signup.php" class="btn btn-outline">Book Now</a>
      </div>
    </div>
  </div>
</section>

<!-- ===================== ABOUT ===================== -->
<section class="about-section section" id="about">
  <div class="container">
    <div class="about-grid">
      <div class="about-text">
        <div class="section-badge">About Us</div>
        <h2>We Make <span class="text-gradient">Car Rental</span> Effortless</h2>
        <p>DriveEase was founded with a simple mission: make premium car rentals accessible to everyone. No stress, no complexity — just great cars at honest prices.</p>
        <p style="margin-top:16px;">Our fleet is meticulously maintained and every booking is backed by our 100% satisfaction guarantee. Whether you need a car for a day trip, a family vacation, or a business meeting, we've got you covered.</p>
        <div class="about-stats">
          <div class="astat"><strong>500+</strong><span>Vehicles Rented</span></div>
          <div class="astat"><strong>98%</strong><span>Customer Satisfaction</span></div>
          <div class="astat"><strong>3+</strong><span>Years in Business</span></div>
        </div>
      </div>
      <div class="about-visual">
        <div class="about-img-card card">
          <img src="/car_rental/assets/images/cars/car_suv.png" alt="Our Fleet" style="border-radius:14px;width:100%;">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CONTACT ===================== -->
<section class="contact-section section" id="contact" style="background:linear-gradient(135deg,#F4F3FF,#EDE9FE);">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Get In Touch</div>
      <h2 class="section-title">We're Here to <span class="text-gradient">Help You</span></h2>
    </div>
    <div class="contact-grid">
      <div class="contact-card card card-body">
        <div class="contact-icon">📧</div>
        <h4>Email Us</h4>
        <p>info@driveease.com</p>
      </div>
      <div class="contact-card card card-body">
        <div class="contact-icon">📞</div>
        <h4>Call Us</h4>
        <p>+91-9876543210</p>
      </div>
      <div class="contact-card card card-body">
        <div class="contact-icon">📍</div>
        <h4>Visit Us</h4>
        <p>123 Drive Street, Mumbai, India</p>
      </div>
    </div>
    <div class="contact-cta">
      <p>Ready to hit the road?</p>
      <a href="/car_rental/pages/signup.php" class="btn btn-primary btn-lg" style="margin-top:20px">
        🚗 Create Free Account
      </a>
    </div>
  </div>
</section>

<!-- ===================== FOOTER ===================== -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="footer-brand">🚗 DriveEase</div>
        <p class="footer-desc">Premium car rentals made simple. Your journey, our passion. Available 24/7 for all your mobility needs.</p>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#cars">Cars</a></li>
          <li><a href="#pricing">Pricing</a></li>
          <li><a href="#about">About</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Account</h4>
        <ul>
          <li><a href="/car_rental/pages/login.php">Login</a></li>
          <li><a href="/car_rental/pages/signup.php">Sign Up</a></li>
          <li><a href="/car_rental/pages/dashboard.php">Dashboard</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Contact</h4>
        <ul>
          <li><a href="mailto:info@driveease.com">info@driveease.com</a></li>
          <li><a href="tel:+919876543210">+91-9876543210</a></li>
          <li><a href="#contact">Get Directions</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2026 DriveEase. All rights reserved. Made with ❤️ for college mini project.</p>
    </div>
  </div>
</footer>

<!-- Booking Modal (for quick book from homepage) -->
<div class="modal-overlay" id="bookModal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">🗓️ Quick Book</h3>
      <button class="modal-close" id="closeBookModal">✕</button>
    </div>
    <div id="bookModalBody">
      <p style="text-align:center;color:var(--text-muted);padding:20px 0;">
        Please <a href="/car_rental/pages/login.php" style="font-weight:600">login</a> or
        <a href="/car_rental/pages/signup.php" style="font-weight:600">sign up</a> to book a car.
      </p>
    </div>
  </div>
</div>

<!-- Toast container -->
<div class="toast-container" id="toastContainer"></div>

<script src="/car_rental/assets/js/main.js"></script>
<script src="/car_rental/assets/js/home.js"></script>
</body>
</html>
