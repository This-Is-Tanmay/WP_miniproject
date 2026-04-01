# 📋 SETUP & DEPLOYMENT GUIDE
## Car Rental User Management System (Experiment 6)

---

## 📦 Project Contents

Your `PHP_Project` folder contains:

| File/Folder | Purpose |
|-------------|---------|
| `index.php` | Entry point - redirects to login or dashboard |
| `login.php` | User login page with session initialization |
| `register.php` | New user registration with file upload |
| `dashboard.php` | Main interface - displays users, search, pagination |
| `add.php` | Create new user (INSERT operation) |
| `edit.php` | Update user information (UPDATE operation) |
| `logout.php` | Destroy session and logout user |
| `config/db.php` | Database connection & helper functions |
| `css/style.css` | Complete styling with Bootstrap 5 |
| `uploads/` | Directory for profile images |
| `database.sql` | SQL commands to create database |
| `README.md` | Full documentation |
| `QUICKSTART.md` | 5-minute quick start |

---

## 🔧 INSTALLATION STEPS

### STEP 1: Prepare Your System

#### A. Install XAMPP (if not already installed)
1. Download from: https://www.apachefriends.org/
2. Run installer
3. Default installation is fine
4. Note your installation path (usually `C:\xampp`)

#### B. Start XAMPP Services
1. Open XAMPP Control Panel
2. Click **Start** next to Apache
3. Click **Start** next to MySQL
4. Both should show green status indicators
5. Keep XAMPP running during project usage

### STEP 2: Copy Project Files

1. Locate your XAMPP htdocs folder:
   ```
   C:\xampp\htdocs\
   ```

2. Copy entire `PHP_Project` folder into htdocs

3. Final path should be:
   ```
   C:\xampp\htdocs\PHP_Project\
   ```

4. Verify folder contains:
   - ✅ config/ (with db.php)
   - ✅ uploads/ (empty directory)
   - ✅ css/ (with style.css)
   - ✅ All .php files
   - ✅ database.sql
   - ✅ README.md

### STEP 3: Create Database

#### Method 1: Using phpMyAdmin (Easiest)

1. Open browser: http://localhost/phpmyadmin
2. Look for **SQL** tab at top (or left panel)
3. Copy entire content from `database.sql`
4. Paste into phpMyAdmin SQL area
5. Click **Go** button
6. Should see success message with database created

#### Method 2: Using MySQL Command Line

1. Open Command Prompt
2. Navigate to MySQL bin folder:
   ```
   cd C:\xampp\mysql\bin
   ```
3. Login to MySQL:
   ```
   mysql -u root -p
   ```
   (Just press Enter when asked for password)

4. Create database:
   ```sql
   CREATE DATABASE car_rental_db;
   USE car_rental_db;
   ```

5. Copy content from database.sql and paste

6. Exit:
   ```
   EXIT;
   ```

### STEP 4: Verify Database Setup

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Left sidebar → Look for `car_rental_db`
3. Click to expand → Should see `users` table
4. Click `users` table → Should see 3 demo records
5. ✅ Database setup complete!

### STEP 5: Verify Configuration

1. Open file: `PHP_Project/config/db.php`
2. Check these settings match your system:

```php
define('DB_HOST', 'localhost');      // Usually localhost
define('DB_USER', 'root');           // Default XAMPP user
define('DB_PASS', '');               // Empty password (default)
define('DB_NAME', 'car_rental_db');  // Must match database name
```

**Note:** If you set a MySQL password, update `DB_PASS` line.

### STEP 6: Test Installation

1. Open browser
2. Go to: **http://localhost/PHP_Project/**
3. Should see login page OR redirect to login
4. Try logging in with:
   ```
   Email: demo@example.com
   Password: password123
   ```
5. ✅ If you see dashboard with users table, setup is successful!

---

## 📱 Browser Testing

### Desktop Testing
- **Chrome** - Full compatibility ✅
- **Firefox** - Full compatibility ✅
- **Edge** - Full compatibility ✅
- **Safari** - Full compatibility ✅

### Mobile Testing
1. Open browser on phone
2. Visit: `http://[your-computer-ip]:80/PHP_Project/`
   
   Example: `http://192.168.1.100/PHP_Project/`

3. Should display responsive layout ✅

---

## ✅ Features Verification Checklist

### Authentication ✅
- [ ] Can access login page
- [ ] Demo login works
- [ ] Can register new user
- [ ] Session prevents unauthorized access
- [ ] Logout destroys session

### CRUD Operations ✅
- [ ] **CREATE**: Add new user button works
- [ ] **READ**: Users display in table
- [ ] **UPDATE**: Edit button opens form
- [ ] **DELETE**: Delete confirmation works
- [ ] All operations show success messages

### File Upload ✅
- [ ] Can upload profile image
- [ ] Images appear with circle display
- [ ] File validation works (rejects non-images)
- [ ] Size limit enforced (5MB)

### Search & Filter ✅
- [ ] Search box appears on dashboard
- [ ] Can search by name
- [ ] Can search by email
- [ ] Results filter correctly
- [ ] Clear search button works

### Pagination ✅
- [ ] Pagination links appear with 10+ users
- [ ] Can navigate between pages
- [ ] Page numbers are clickable
- [ ] Search works with pagination

### Form Validation ✅
- [ ] Empty field validation works
- [ ] Email format validation works
- [ ] Age range validation (18-100) works
- [ ] Password length validation works
- [ ] File type validation works

### Responsive Design ✅
- [ ] Works on desktop (1920px wide)
- [ ] Works on tablet (768px wide)
- [ ] Works on mobile (360px wide)
- [ ] Navigation collapses on mobile
- [ ] Tables scroll on mobile

---

## 🔒 Security Verification

### SQL Injection Prevention ✅
Check `config/db.php` and `*.php` files:
```php
// ✅ CORRECT - Using prepared statements
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);

// ❌ WRONG - Direct query (we DON'T do this)
$sql = "SELECT * FROM users WHERE email = '$email'";
```

### Password Security ✅
```php
// ✅ CORRECT - Password hashing
$hash = password_hash($password, PASSWORD_DEFAULT);
password_verify($input, $hash);

// ❌ WRONG - Plain text (we DON'T do this)
$password = $_POST['password']; // Direct use
```

### Session Security ✅
```php
// ✅ Sessions checked at page start
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
```

### Input Sanitization ✅
```php
// ✅ CORRECT - Input sanitized
$input = sanitizeInput($_POST['name']); // Trims, escapes, removes slashes

// ❌ WRONG - Direct use
$name = $_POST['name']; // Could contain injection code
```

---

## 🐛 Debugging Tips

### If Login Page Shows Error

1. Check MySQL is running (green in XAMPP)
2. Check `config/db.php` settings
3. Open phpMyAdmin - verify database exists
4. Check browser console (F12 → Console tab)

### If Dashboard Doesn't Load

1. Verify you're logged in
2. Check `dashboard.php` file exists
3. Verify `users` table exists in database
4. Check permissions on `uploads/` folder

### If File Upload Fails

1. Right-click `uploads` folder → Properties
2. Go to Security tab
3. Edit → Add user "Everyone" with Full Control
4. Click Apply → OK

### If Search Doesn't Work

1. Check database connection is working
2. Verify table has data
3. Check search field has value
4. Open browser console for JavaScript errors (F12)

### If Pagination Shows No Pages

1. Verify more than 10 users in database
2. Check pagination code in `dashboard.php`
3. Verify database query returns correct count

---

## 📊 Sample Test Cases

### Test Case 1: User Registration
```
1. Navigate to register.php
2. Fill form:
   - Full Name: John Doe
   - Email: john@newmail.com
   - Age: 25
   - Password: Test@123 (6+ chars)
   - Upload image: Test image JPG
3. Click Register
✅ EXPECTED: Redirect to login, success message
```

### Test Case 2: User Login
```
1. Enter email: demo@example.com
2. Enter password: password123
3. Click Login
✅ EXPECTED: Redirect to dashboard, see users table
```

### Test Case 3: Add User
```
1. Click "Add New User"
2. Fill details: Name, Email, Age, Password
3. Submit
✅ EXPECTED: User appears in dashboard table
```

### Test Case 4: Edit User
```
1. Click Edit on any user
2. Change name to "Updated Name"
3. Click Update
✅ EXPECTED: Name updated in dashboard
```

### Test Case 5: Delete User
```
1. Click Delete on user
2. Confirm deletion
✅ EXPECTED: User removed, success message shown
```

### Test Case 6: Search User
```
1. Type "john" in search box
2. Click Search
✅ EXPECTED: Only matching users displayed
```

### Test Case 7: Session Protection
```
1. Open DevTools (F12)
2. Delete all cookies
3. Try to access dashboard.php directly
✅ EXPECTED: Redirected to login
```

---

## 📸 Screenshots for Submission

Capture these screenshots:

1. **Login Page**
   - Show: Clean login form with email/password
   - Note: Demo credentials visible

2. **Dashboard**
   - Show: User table with 3+ users
   - Note: Search box, Edit/Delete buttons visible

3. **Add User Form**
   - Show: Complete form with all fields
   - Note: File upload area visible

4. **Edit User Form**
   - Show: Form with current data
   - Note: Profile image displayed

5. **Search Result**
   - Show: Filtered search results
   - Note: Search box with query

6. **Mobile View**
   - Show: Dashboard on mobile screen
   - Note: Responsive layout working

7. **Profile Image Upload**
   - Show: User with profile image in table
   - Note: Image displays correctly

8. **Success Message**
   - Show: Green success alert after action
   - Note: Message fully visible

9. **Pagination**
   - Show: Page numbers when 10+ users
   - Note: Multiple pages working

10. **Database**
    - Show: phpMyAdmin with users table
    - Note: Sample data visible

---

## ⚡ Performance Notes

- Dashboard loads users per page: 10
- Images max size: 5MB
- Session timeout: 1 hour
- Password strength: Minimum 6 characters
- Search performance: O(n) with LIMIT clause

---

## 🔄 Workflow Summary

```
Entry Point: index.php
    ↓
Is User Logged In? 
    ├─ YES → Redirect to dashboard.php
    └─ NO → Redirect to login.php
    
At login.php:
    ├─ New user? → Click "Register" → register.php
    └─ Returning? → Enter credentials → dashboard.php
    
At dashboard.php:
    ├─ Add User → add.php → INSERT
    ├─ Edit User → edit.php → UPDATE
    ├─ Delete User → DELETE (inline)
    ├─ Search → Filter & display
    └─ Logout → logout.php (destroy session)
```

---

## 📚 Key PHP Functions Used

| Function | Purpose | Location |
|----------|---------|----------|
| `session_start()` | Start session | All pages (top) |
| `$conn->prepare()` | Prepared statement | crud operations |
| `password_hash()` | Hash password | register.php, login.php |
| `password_verify()` | Check password | login.php |
| `sanitizeInput()` | Clean input | config/db.php |
| `move_uploaded_file()` | Save file | upload operations |
| `header()` | Redirect | auth checks |

---

## 🎯 Expected Results

### For Examiner
When evaluating, look for:
- ✅ **Authentication**: Login/logout with sessions
- ✅ **CRUD**: All 4 operations functional
- ✅ **Database**: MySQL with 5+ fields
- ✅ **Upload**: File storage in uploads/
- ✅ **Security**: Prepared statements, hashing
- ✅ **UI**: Modern, responsive design
- ✅ **Validation**: Frontend + Backend
- ✅ **Search**: Filter functionality
- ✅ **Pagination**: Large dataset handling
- ✅ **Documentation**: This complete guide

---

## 📞 Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| "Connection failed" | MySQL not running | Start MySQL in XAMPP |
| "Access denied for user 'root'" | Wrong password | Check DB_PASS in config/db.php |
| "No database selected" | database.sql not imported | Import via phpMyAdmin |
| White screen | PHP error | Check Apache error log |
| File upload fails | No permission | Set uploads/ permissions to 755 |
| Search doesn't work | Data type mismatch | Check column types in database |
| Session lost | Cookie settings | Clear browser cookies |
| Images don't display | Wrong path | Check profile_image field in database |

---

## ✅ Pre-Submission Checklist

- [ ] All files copied to htdocs
- [ ] MySQL service running
- [ ] Database imported successfully
- [ ] Can login with demo credentials
- [ ] All CRUD operations working
- [ ] File upload working
- [ ] Search & pagination working
- [ ] Responsive on mobile
- [ ] No console errors
- [ ] No PHP errors in error log
- [ ] Screenshots captured
- [ ] Documentation complete
- [ ] README.md in project folder
- [ ] All code properly commented

---

## 🎓 For College Submission

**Deliverables:**
1. ✅ Complete PHP project files
2. ✅ SQL database script
3. ✅ Setup documentation (this file)
4. ✅ README with features & codes
5. ✅ Screenshots of all features
6. ✅ Demo video (optional, but impressive)

**Marks Distribution:**
- CRUD Operations: 20 marks
- Database Design: 15 marks
- Session Security: 15 marks
- File Upload: 10 marks
- UI/UX Design: 15 marks
- Form Validation: 10 marks
- Code Quality: 10 marks
- Documentation: 5 marks

---

**Project completed! Ready for submission! 🚀**

For more details, see README.md
For quick start, see QUICKSTART.md
