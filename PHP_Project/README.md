# 🚗 Car Rental User Management System
## Experiment 6: Interactive Web Pages using PHP with MySQL Database Connectivity and Session Tracking

**College Mini Project**

---

## 📋 Project Overview

This project demonstrates a **complete web-based User Management System** with:
- ✅ Full CRUD Operations (Create, Read, Update, Delete)
- ✅ Secure Authentication with PHP Sessions
- ✅ MySQL Database Integration
- ✅ File Upload Functionality
- ✅ Form Validation (Frontend & Backend)
- ✅ Search & Pagination Features
- ✅ Responsive Bootstrap UI
- ✅ SQL Injection Prevention (Prepared Statements)

---

## 🛠️ Tech Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, Bootstrap 5
- **Server:** XAMPP/Apache

---

## 📁 Project Structure

```
PHP_Project/
├── config/
│   └── db.php                 # Database connection & functions
├── uploads/                   # Profile images directory
├── css/
│   └── style.css             # Main stylesheet
├── js/
│   └── (optional)            # JavaScript files
├── login.php                 # Login page with sessions
├── register.php              # User registration
├── dashboard.php             # Main dashboard (CRUD view)
├── add.php                   # Add new user (CREATE)
├── edit.php                  # Edit user (UPDATE)
├── logout.php                # Logout & destroy session
├── database.sql              # SQL database setup
└── README.md                 # This file
```

---

## 🚀 Installation Steps

### Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services
3. Check status indicators turn green

### Step 2: Copy Project Files
1. Locate your XAMPP htdocs folder: `C:\xampp\htdocs\`
2. Copy the `PHP_Project` folder into htdocs
3. Full path should be: `C:\xampp\htdocs\PHP_Project\`

### Step 3: Create Database
1. Open **phpMyAdmin**: http://localhost/phpmyadmin
2. Go to **SQL** tab
3. Copy & paste all content from `database.sql`
4. Click **Go** to execute
5. Verify table is created: `car_rental_db` → `users`

### Step 4: Verify Configuration
1. Open `config/db.php`
2. Ensure these settings match your setup:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');           // Default XAMPP password is empty
   define('DB_NAME', 'car_rental_db');
   ```

### Step 5: Create Uploads Folder
1. Ensure `PHP_Project/uploads/` folder exists
2. Make it writable (permissions 755)
3. This stores profile images

### Step 6: Access Application
- Open browser and go to: **http://localhost/PHP_Project/login.php**
- Ready to use! 🎉

---

## 👤 Demo Credentials

```
Email:    demo@example.com
Password: password123
```

---

## ✨ Key Features

### 1. **User Authentication & Sessions**
- Secure login with password hashing (bcrypt)
- Session tracking & timeout
- Restricted access to dashboard
- Logout functionality

### 2. **CRUD Operations**

#### CREATE (Register & Add User)
- Form validation
- Unique email checking
- Password hashing
- File upload for profile image

#### READ (Dashboard)
- Display all users in table
- Profile images display
- Search by name/email
- Pagination (10 users per page)
- Created date display

#### UPDATE (Edit User)
- Edit user details
- Change profile image
- Optional password change
- Email uniqueness validation

#### DELETE (Remove User)
- Confirmation dialog
- Delete profile image from server
- Success/error messages

### 3. **Form Validation**
- Frontend: Real-time validation with JavaScript
- Backend: Server-side validation
- Email format validation
- Age range validation (18-100)
- Password length requirements
- File type & size validation

### 4. **File Upload**
- Profile image upload
- Accept: JPG, PNG, GIF
- Max size: 5MB
- Unique filename generation
- Display with fallback icon

### 5. **Search Functionality**
- Search by name or email
- Real-time search
- Clear search option
- Maintains search on pagination

### 6. **Database Security**
- Prepared statements (prevent SQL injection)
- Password hashing (PHP password_hash)
- Input sanitization
- CORS protection

---

## 🎨 UI Features

- **Modern Gradient Design** - Purple gradient theme
- **Responsive Layout** - Works on desktop, tablet, mobile
- **Bootstrap 5 Integration** - Professional components
- **Font Awesome Icons** - Rich icon support
- **Animations** - Smooth transitions & fade-ins
- **Alert Messages** - Success/error notifications
- **Form Feedback** - Real-time validation messages

---

## 📊 Database Schema

### Users Table

```sql
CREATE TABLE users (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    full_name       VARCHAR(100) NOT NULL,
    email           VARCHAR(100) NOT NULL UNIQUE,
    age             INT NOT NULL,
    password        VARCHAR(255) NOT NULL,
    profile_image   VARCHAR(255),
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Fields:**
- `id` - Unique identifier
- `full_name` - User's full name (max 100 chars)
- `email` - Unique email address
- `age` - Age (18-100 range)
- `password` - Hashed password
- `profile_image` - Path to profile image
- `created_at` - Registration timestamp
- `updated_at` - Last update timestamp

---

## 🔐 Security Features

1. **Prepared Statements** - Prevents SQL injection
   ```php
   $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
   $stmt->bind_param("s", $email);
   ```

2. **Password Hashing** - Using PHP's password_hash()
   ```php
   $hashed = password_hash($password, PASSWORD_DEFAULT);
   password_verify($input, $hashed);
   ```

3. **Input Sanitization** - stripslashes & trim
   ```php
   $input = $conn->real_escape_string(stripslashes(trim($data)));
   ```

4. **Session Security** - HTTPOnly cookies
   ```php
   session_set_cookie_params(['httponly' => true]);
   ```

5. **File Validation** - Type & size checks
   ```php
   $allowed_types = ['image/jpeg', 'image/png'];
   if (in_array($file_type, $allowed_types) && $file_size < 5000000)
   ```

---

## 🧪 Testing Scenarios

### Test 1: User Registration
1. Click "Register here" on login page
2. Fill form with valid details
3. Upload profile image
4. Submit
5. ✅ Should redirect to login with success message

### Test 2: User Login
1. Enter demo credentials
2. ✅ Should redirect to dashboard

### Test 3: Add User
1. Click "Add New User" button
2. Fill form details
3. ✅ User appears in dashboard table

### Test 4: Edit User
1. Click "Edit" button on any user
2. Update information
3. ✅ Dashboard updated

### Test 5: Search Functionality
1. Type in search box
2. ✅ Results filter in real-time

### Test 6: Delete User
1. Click "Delete" button
2. Confirm deletion
3. ✅ User removed from table

### Test 7: Session Timeout
1. Try accessing dashboard without login
2. ✅ Redirected to login page

### Test 8: Logout
1. Click logout button
2. ✅ Redirected to login, session destroyed

---

## 📸 Screenshots to Capture (for submission)

1. **Login Page** - Clean login form with demo credentials
2. **Registration Page** - Registration form with file upload
3. **Dashboard** - List of users with search & pagination
4. **Add User** - Form to add new user with validation
5. **Edit User** - User editing with current image display
6. **Success Message** - Alert showing after CRUD operation
7. **Responsive Mobile** - Dashboard on phone/tablet
8. **File Upload Success** - Profile image displayed

---

## 🐛 Troubleshooting

### Issue: Cannot connect to database
**Solution:** 
- Check MySQL service is running
- Verify credentials in `config/db.php`
- Ensure database.sql was imported

### Issue: File upload not working
**Solution:**
- Check `uploads/` folder exists
- Set permissions: Right-click → Properties → Security → Edit
- Verify file size < 5MB
- Check file type is JPG, PNG, or GIF

### Issue: Session not working
**Solution:**
- Check `session_start()` called at top of files
- Verify cookie settings in `config/db.php`
- Clear browser cookies

### Issue: Prepared statement error
**Solution:**
- Ensure MySQLi extension enabled in PHP
- Check SQL syntax correctness
- Verify parameter binding order

---

## 📝 SQL Injection Prevention Example

**❌ Vulnerable Code:**
```php
$sql = "SELECT * FROM users WHERE email = '$email'";
```

**✅ Safe Code (Prepared Statements):**
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
```

---

## 🎓 Learning Outcomes

After completing this project, you'll understand:
- ✅ PHP session management & authentication
- ✅ MySQL database design & queries
- ✅ CRUD operations implementation
- ✅ Form validation techniques
- ✅ File upload handling
- ✅ Security best practices
- ✅ Responsive web design
- ✅ Bootstrap framework usage
- ✅ Error handling & debugging

---

## 📚 Additional Notes

### Password Hashing
All passwords are hashed using PHP's `password_hash()` function:
```php
// Generate hash
$hash = password_hash("password123", PASSWORD_DEFAULT);

// Verify password
if (password_verify($input, $hash)) {
    // Correct password
}
```

### Session Variables Used
```php
$_SESSION['user_id']    // User's unique ID
$_SESSION['user_name']  // User's full name
$_SESSION['user_email'] // User's email
$_SESSION['login_time'] // Login timestamp
```

### Helper Functions in config/db.php
```php
sanitizeInput($data)     // Sanitize user input
showSuccess($message)    // Display success alert
showError($message)      // Display error alert
redirectTo($url)         // Redirect to URL
```

---

## ✅ Submission Checklist

- [ ] All PHP files present and working
- [ ] Database created with sample data
- [ ] File upload functionality working
- [ ] Search & pagination functioning
- [ ] Form validation (frontend & backend) working
- [ ] Session tracking preventing unauthorized access
- [ ] Responsive design tested on mobile
- [ ] CRUD operations all working
- [ ] No SQL injection vulnerabilities
- [ ] Screenshots captured
- [ ] README documentation complete

---

## 🤝 Support

For issues or questions:
1. Check this README thoroughly
2. Review the code comments
3. Check browser console for JavaScript errors
4. Check server error logs (XAMPP/Apache)
5. Verify phpMyAdmin for database issues

---

## 📄 License

This project is for educational purposes only.

---

## 👨‍💻 Author

Created as a College Mini Project for "Experiment 6: Interactive Web Pages with PHP and MySQL"

**Date:** April 2026

---

**Happy Coding! 🚀**
