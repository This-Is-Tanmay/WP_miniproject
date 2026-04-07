# 🚗 DriveEase — Modern Car Rental Website

A **premium, fully functional car rental web application** built from scratch using **PHP, MySQL (XAMPP), HTML, CSS, JavaScript, and AJAX**. Features a modern glassmorphism UI, secure authentication, real-time car booking, and a full admin panel.

---

## 📸 Screenshots

| Page | Preview |
|------|---------|
| Landing Page | `assets/images/ui/preview_home.png` |
| Login | `assets/images/ui/preview_login.png` |
| Dashboard | `assets/images/ui/preview_dashboard.png` |
| Admin Panel | `assets/images/ui/preview_admin.png` |

---

## ✨ Features

### User Features
- 🚗 **Browse Cars** — Filter by type (SUV, Sedan, Hatchback, Luxury, Convertible, Van)
- 📅 **Book a Car** — Select dates, see price summary, instant confirmation
- 📋 **Booking History** — View all past and upcoming bookings
- 🔐 **Secure Auth** — AJAX login/signup with hashed passwords
- 📱 **Fully Responsive** — Mobile-first design

### Admin Features
- 📊 **Dashboard Stats** — Total cars, users, bookings, revenue
- 🚗 **Car CRUD** — Add, edit, delete cars with image upload
- 📋 **All Bookings** — View & update booking statuses
- 👥 **User Overview** — See registered customer data
- 🔄 **Toggle Availability** — Mark cars available/unavailable instantly

### Technical Highlights
- ⚡ **AJAX Everywhere** — No page reloads for login, signup, car loading, booking
- 🔒 **PDO Prepared Statements** — SQL injection protected
- 💅 **Glassmorphism UI** — Soft gradients, backdrop blur, smooth animations
- 🔔 **Toast Notifications** — Real-time feedback system
- 🛡️ **Session Security** — session_regenerate_id after login, httponly cookies
- 📧 **Real-time Email Validation** — Checks uniqueness while typing

---

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| **PHP 8.x** | Backend logic, session management |
| **MySQL** | Database (via XAMPP) |
| **PDO** | Secure database queries |
| **HTML5** | Page structure |
| **CSS3** | Glassmorphism design system |
| **JavaScript (ES6+)** | Frontend interactions |
| **AJAX (Fetch API)** | Async data exchange |
| **XAMPP** | Local server (Apache + MySQL) |
| **Google Fonts (Inter)** | Typography |

---

## 📁 Project Structure

```
car_rental/
│
├── index.php                   ← Landing Page
├── database.sql                ← Database setup script
├── .htaccess                   ← Apache security config
│
├── includes/
│   ├── db.php                  ← PDO database connection
│   └── auth.php                ← Session & auth helpers
│
├── pages/
│   ├── login.php               ← Login page
│   ├── signup.php              ← Registration page
│   ├── dashboard.php           ← User dashboard
│   ├── admin.php               ← Admin panel
│   └── logout.php              ← Session destroy
│
├── ajax/
│   ├── login.php               ← AJAX login handler
│   ├── signup.php              ← AJAX signup handler
│   ├── get_cars.php            ← Fetch cars (with filter)
│   ├── book_car.php            ← Create booking
│   ├── get_bookings.php        ← Fetch user bookings
│   ├── admin_cars.php          ← Admin CRUD + stats
│   └── validate_email.php      ← Real-time email check
│
├── assets/
│   ├── css/
│   │   ├── style.css           ← Global design system
│   │   └── pages/
│   │       ├── home.css        ← Landing page styles
│   │       ├── auth.css        ← Login/signup styles
│   │       └── dashboard.css   ← Dashboard/admin styles
│   │
│   ├── js/
│   │   ├── main.js             ← Global utilities (AJAX, Toast)
│   │   ├── home.js             ← Landing page logic
│   │   ├── login.js            ← Login AJAX
│   │   ├── signup.js           ← Signup AJAX + validation
│   │   ├── dashboard.js        ← User dashboard logic
│   │   └── admin.js            ← Admin panel logic
│   │
│   └── images/
│       ├── cars/               ← Car images (car_suv.png, etc.)
│       ├── ui/                 ← Icons & illustrations
│       └── bg/                 ← Background images
```

---

## ⚙️ Setup Guide (Step-by-Step)

### Step 1: Install XAMPP

1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install with default settings
3. Open **XAMPP Control Panel**
4. Start **Apache** and **MySQL** — both should show green

### Step 2: Place the Project in htdocs

1. Copy the entire `car_rental/` folder
2. Paste it into: `C:\xampp\htdocs\`
3. Final path should be: `C:\xampp\htdocs\car_rental\`

### Step 3: Import the Database

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **"New"** in the left sidebar (if no `car_rental` DB exists)
3. Click the **"Import"** tab at the top
4. Click **"Choose File"** and select `car_rental/database.sql`
5. Click **"Go"** at the bottom
6. ✅ You should see a green success message

> **Alternative**: You can also just click "Import" from the main phpMyAdmin screen without creating the DB first — the SQL file creates the database itself.

### Step 4: Configure Database Connection

Open `car_rental/includes/db.php` and verify:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Add your MySQL password if set
define('DB_NAME', 'car_rental');
```

> For most default XAMPP installations, the root password is empty — no changes needed.

### Step 5: Run the Project

Open your browser and visit:
```
http://localhost/car_rental/
```

That's it! The website should load with the landing page. 🎉

---

## 🔑 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@carrental.com` | `password123` |
| **User** | `demo@carrental.com` | `password123` |

> You can also register a new account from the Sign Up page.

---

## 🖼️ Adding Car Images

Car images go in: `assets/images/cars/`

Currently included images:
- `car_suv.png` — Toyota Fortuner
- `car_sedan.png` — Honda City
- `car_hatchback.png` — Maruti Swift
- `car_luxury.png` — Mercedes S-Class
- `car_convertible.png` — Ford Mustang GT
- `car_van.png` — Toyota Innova Crysta
- `placeholder.png` — Fallback image

To add a new car with an image via Admin Panel:
1. Go to `http://localhost/car_rental/pages/admin.php`
2. Login as admin
3. Click **"Manage Cars"** → **"+ Add New Car"**
4. Fill details and upload an image
5. Click **"Save Car"**

---

## 🔧 Troubleshooting

### ❌ "Database connection failed"
- Make sure XAMPP Apache and MySQL are both running (green in Control Panel)
- Verify the database was imported correctly via phpMyAdmin
- Check `db.php` — is `DB_PASS` correct for your MySQL root user?

### ❌ "Page not found" / 404 errors
- Make sure the folder is at `C:\xampp\htdocs\car_rental\` (not inside another folder)
- Access via `http://localhost/car_rental/` — NOT by opening the `.php` file directly

### ❌ Images not showing
- Check that car images are inside `assets/images/cars/`
- Image names must match exactly what's in the database (case-sensitive)
- The fallback `placeholder.png` shows if a real image is missing

### ❌ AJAX not working (login/signup fails silently)
- Make sure Apache is running (not just MySQL)
- Open browser DevTools (F12) → Network tab → look for failed requests
- Check `php.ini` for `display_errors = On` while debugging

### ❌ "Session" errors or getting logged out constantly
- Make sure `C:\xampp\tmp` exists and Apache has write permission
- Try restarting XAMPP completely

### ❌ Admin panel not accessible
- You must login with `admin@carrental.com` — regular users are blocked
- The `role` column in the `users` table must be `'admin'`

### ❌ Password doesn't work for demo accounts
- The SQL file uses `password_hash('password123', PASSWORD_DEFAULT)`
- If the hash seems wrong, register a new account via the signup page — it hashes correctly

---

## 📝 Development Notes

- All AJAX requests include the `X-Requested-With: XMLHttpRequest` header
- The `auth.php` helper checks this header to return JSON vs HTML for errors
- Car booking checks for date overlaps — same car can't be double-booked
- Admin CRUD for cars supports image upload (JPG, PNG, WebP)
- SQLi protected via PDO prepared statements throughout

---

## 👨‍💻 Credits

Built as a **Web Programming Mini Project** — using PHP, MySQL, AJAX, and modern CSS techniques.

**Stack**: PHP 8 + PDO | MySQL | Vanilla JS | CSS3 Glassmorphism | AJAX (Fetch API)
