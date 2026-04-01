# 📂 FILE STRUCTURE & PURPOSE GUIDE

## Complete File Reference for Car Rental User Management System

---

## 📁 PROJECT HIERARCHY

```
C:\xampp\htdocs\PHP_Project\
│
├── Core Features
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── dashboard.php
│   ├── add.php
│   ├── edit.php
│   └── logout.php
│
├── Configuration
│   └── config/db.php
│
├── Styling & Frontend
│   └── css/style.css
│
├── File Storage
│   └── uploads/                [Profile images stored here]
│
├── Database
│   └── database.sql
│
├── Documentation
│   ├── README.md
│   ├── QUICKSTART.md
│   ├── SETUP_GUIDE.md
│   ├── PROJECT_SUMMARY.md
│   └── FILE_STRUCTURE.md      [This file]
│
└── Future Expansion
    └── js/                      [For JavaScript files]
```

---

## 📄 FILE DETAILS

### 🔑 Core PHP Files

#### 1. **index.php** (Entry Point)
**Purpose:** Redirects users based on login status
**Functions:**
- Session check
- Redirect to dashboard if logged in
- Redirect to login if not logged in

**When Used:** When http://localhost/PHP_Project/ is accessed

**Code Length:** ~15 lines

---

#### 2. **login.php** (Authentication)
**Purpose:** User login page with session initialization
**Features:**
- Email/password input form
- Password verification using password_verify()
- Session variable creation ($_SESSION)
- Error handling
- Demo credentials provided

**Database Operations:**
- SELECT user by email
- Password verification

**When Used:** Upon first visit or after logout

**Code Length:** ~150 lines + styling

---

#### 3. **register.php** (User Registration)
**Purpose:** New user registration with file upload
**Features:**
- Full name, email, age, password input
- Email validation & uniqueness check
- Age validation (18-100)
- Password confirmation matching
- Profile image upload
- File type validation (JPG, PNG, GIF)
- File size validation (5MB max)
- Success redirect to login

**Database Operations:**
- INSERT new user
- Password hashing with password_hash()

**When Used:** New users click register link

**Code Length:** ~200 lines + styling

---

#### 4. **dashboard.php** (Main Interface - READ Operation)
**Purpose:** Display all users, search, pagination
**Features:**
- User table display
- Profile image display
- Search by name/email
- Pagination (10 per page)
- Edit/Delete buttons
- Creation date display
- Session protection
- Search with pagination
- Total user count

**Database Operations:**
- SELECT all users (with pagination)
- SELECT count for pagination
- Handle delete action via GET parameter

**When Used:** After successful login

**Code Length:** ~350 lines + styling

---

#### 5. **add.php** (CREATE Operation)
**Purpose:** Add new user to database
**Features:**
- Form with name, email, age, password, image
- Input validation
- Email uniqueness check
- File upload handling
- Success message
- Redirect to dashboard

**Database Operations:**
- INSERT new user
- Password hashing
- File upload processing

**When Used:** Click "Add New User" button

**Code Length:** ~200 lines + styling

---

#### 6. **edit.php** (UPDATE Operation)
**Purpose:** Edit existing user information
**Features:**
- Get user ID from URL parameter
- Display current user data
- Edit any field
- Change profile image
- Optional password change
- Email uniqueness check (exclude current user)
- Current image display
- Update confirmation

**Database Operations:**
- SELECT user by ID
- UPDATE user data
- File replacement handling

**When Used:** Click "Edit" button on dashboard

**Code Length:** ~250 lines + styling

---

#### 7. **logout.php** (Session Destruction)
**Purpose:** Logout user and destroy session
**Features:**
- Session destruction
- Redirect to login
- No user interaction needed

**Database Operations:** None

**When Used:** Click "Logout" button

**Code Length:** ~10 lines

---

### ⚙️ Configuration Files

#### **config/db.php** (Database Connection)
**Purpose:** Database connection & helper functions
**Contains:**
- Database connection setup
- MySQLi connection creation
- Database credentials
- Helper functions:
  - `sanitizeInput()` - Sanitize user input
  - `showSuccess()` - Display success alert
  - `showError()` - Display error alert
  - `redirectTo()` - Redirect function

**Key Functions:**
```php
sanitizeInput($data)     // Remove slashes, trim, escape
showSuccess($message)    // HTML success alert
showError($message)      // HTML error alert
redirectTo($url)         // Header redirect
```

**Code Length:** ~60 lines

**Usage:** Included in all PHP files with `require_once 'config/db.php'`

---

### 🎨 Styling Files

#### **css/style.css** (Main Stylesheet)
**Purpose:** Complete styling for all pages
**Contains:**
- Alert styles (success, error, warning)
- Button styles (primary, secondary, success, danger)
- Navbar styling
- Form element styling
- Table styling
- Animation keyframes
- Responsive media queries
- Utility classes

**Features:**
- Bootstrap 5 integration
- Gradient backgrounds
- Smooth transitions
- Color consistency
- Mobile responsive

**Code Length:** ~300 lines

---

### 💾 Database Files

#### **database.sql** (Database Setup)
**Purpose:** Create database and tables
**Contains:**
- DROP/CREATE database statements
- CREATE users table with proper structure
- INSERT demo user data (3 users)
- Comments and notes

**Table Structure:**
```sql
- id (Primary Key, Auto Increment)
- full_name (VARCHAR 100)
- email (VARCHAR 100, UNIQUE)
- age (INT)
- password (VARCHAR 255)
- profile_image (VARCHAR 255)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

**Usage:** Import via phpMyAdmin SQL tab

**Code Length:** ~50 lines

---

### 📚 Documentation Files

#### **README.md** (Complete Documentation)
**Purpose:** Comprehensive project documentation
**Sections:**
- Project overview
- Tech stack
- Project structure
- Installation steps (6 detailed steps)
- Key features
- Database schema
- Security features
- UI features
- File structure
- Testing scenarios (8 scenarios)
- Troubleshooting
- Learning outcomes
- Submission checklist

**Code Length:** ~500 lines

---

#### **QUICKSTART.md** (Fast Setup)
**Purpose:** 5-minute quick start guide
**Sections:**
- 5-step setup
- Features summary
- File structure
- Troubleshooting table
- Learning points
- Completion checklist

**Code Length:** ~200 lines

---

#### **SETUP_GUIDE.md** (Detailed Installation)
**Purpose:** Step-by-step setup instructions
**Sections:**
- Package contents
- 6-step installation process
- Database creation (2 methods)
- Configuration verification
- Testing procedures
- Feature verification
- Security verification
- Debugging tips
- Test cases (7 test cases)
- Screenshots to capture
- Performance notes
- Common issues table
- Pre-submission checklist

**Code Length:** ~600 lines

---

#### **PROJECT_SUMMARY.md** (Delivery Summary)
**Purpose:** Complete project delivery overview
**Sections:**
- Deliverables list
- Features implemented (10 categories)
- Database structure
- Code statistics
- Exam requirements met
- Documentation provided
- Learning outcomes
- Screenshots list
- Quality metrics
- Expected marks breakdown

**Code Length:** ~400 lines

---

#### **FILE_STRUCTURE.md** (This File)
**Purpose:** File reference guide
**Sections:**
- Complete file hierarchy
- Detailed file descriptions
- File purposes and uses
- Code lengths
- Feature summaries

**Code Length:** ~400 lines

---

### 📁 Storage Directories

#### **uploads/ (Profile Images)**
**Purpose:** Store uploaded profile images
**Contains:**
- User profile pictures
- File naming: profile_[timestamp].[extension]
- Permissions: 755 (read/write/execute)
- Max file size per image: 5MB

**Created:** When first image is uploaded
**Managed By:** register.php, add.php, edit.php

---

#### **js/ (JavaScript Directory)**
**Purpose:** For future JavaScript needs
**Status:** Empty (ready for expansion)
**Potential Uses:**
- Form validation scripts
- AJAX functionality
- Dynamic interactions
- Animations

---

---

## 📊 CODE STATISTICS

| File | Lines | Functions | Purpose |
|------|-------|-----------|---------|
| index.php | 15 | 0 | Entry point |
| login.php | 150 | 0 | Authentication |
| register.php | 200 | 0 | Registration |
| dashboard.php | 350 | 0 | Main interface |
| add.php | 200 | 0 | Add user |
| edit.php | 250 | 0 | Edit user |
| logout.php | 10 | 0 | Logout |
| config/db.php | 60 | 4 | Configuration |
| css/style.css | 300 | 0 | Styling |
| database.sql | 50 | 0 | Database |

**Total PHP Code:** ~1,385 lines
**Total CSS Code:** ~300 lines
**Total SQL Code:** ~50 lines
**Total Documentation:** ~2,100 lines

---

## 🔄 FILE RELATIONSHIPS

```
index.php
  ├─→ login.php (if not logged in)
  └─→ dashboard.php (if logged in)

login.php
  ├─→ register.php (register link)
  ├─→ dashboard.php (on success)
  └─→ config/db.php (database connection)

register.php
  ├─→ login.php (on success)
  └─→ config/db.php (database connection)

dashboard.php
  ├─→ add.php (add new user)
  ├─→ edit.php (edit user)
  ├─→ delete handling (inline)
  ├─→ logout.php (logout link)
  └─→ config/db.php (database connection)

add.php
  ├─→ dashboard.php (on success)
  └─→ config/db.php (database connection)

edit.php
  ├─→ dashboard.php (on success)
  └─→ config/db.php (database connection)

logout.php
  └─→ login.php (redirect after logout)

All PHP files
  └─→ config/db.php (included in all)
  └─→ css/style.css (linked in all HTML)
```

---

## 🔐 Database Operations by File

| File | Operations | Type |
|------|-----------|------|
| login.php | SELECT, password_verify() | READ, Verify |
| register.php | INSERT, SELECT (check email) | CREATE, Check |
| dashboard.php | SELECT (all), DELETE | READ, DELETE |
| add.php | INSERT, SELECT (check email) | CREATE, Check |
| edit.php | SELECT, UPDATE, SELECT (check email) | READ, UPDATE, Check |
| logout.php | None | None |

---

## 📋 SESSION VARIABLES CREATED

| Variable | Created In | Used In | Value |
|----------|-----------|---------|-------|
| `$_SESSION['user_id']` | login.php | All pages | User's ID |
| `$_SESSION['user_name']` | login.php | dashboard.php | User's name |
| `$_SESSION['user_email']` | login.php | dashboard.php | User's email |
| `$_SESSION['login_time']` | login.php | config/db.php | Login timestamp |
| `$_SESSION['message']` | CRUD files | dashboard.php | Success/error message |
| `$_SESSION['msg_type']` | CRUD files | dashboard.php | Message type |

---

## 🛡️ Security Measures by File

| File | Security Measures |
|------|------------------|
| All PHP | Session start, connection check |
| login.php | password_verify(), prepared statement |
| register.php | Email validation, file type check, size limit |
| dashboard.php | Session check, search sanitization, pagination |
| add.php | Input sanitization, email check, file validation |
| edit.php | Input sanitization, email check (exclude current), file validation |
| config/db.php | Prepared statements, sanitizeInput() function, secure session |

---

## 🎯 How to Use This Reference

**Finding a file:**
1. Look at what you need to do
2. Find file purpose in this guide
3. File tells you what it does
4. "When Used" section shows when it's accessed

**Understanding code flow:**
1. Start with index.php
2. Follow file relationships
3. Track database operations
4. Check session variables

**For debugging:**
1. Check which file should handle operation
2. Review security measures
3. Check database operations
4. Review session variables used

---

## ✅ All Files Status

- [x] index.php - ✅ Complete
- [x] login.php - ✅ Complete
- [x] register.php - ✅ Complete
- [x] dashboard.php - ✅ Complete
- [x] add.php - ✅ Complete
- [x] edit.php - ✅ Complete
- [x] logout.php - ✅ Complete
- [x] config/db.php - ✅ Complete
- [x] css/style.css - ✅ Complete
- [x] database.sql - ✅ Complete
- [x] uploads/ - ✅ Ready
- [x] js/ - ✅ Ready
- [x] README.md - ✅ Complete
- [x] QUICKSTART.md - ✅ Complete
- [x] SETUP_GUIDE.md - ✅ Complete
- [x] PROJECT_SUMMARY.md - ✅ Complete
- [x] FILE_STRUCTURE.md - ✅ Complete (this file)

---

**Total Files: 17**
**Total Folders: 4**
**Total Code Lines: 2,000+**
**Total Documentation Lines: 2,100+**

**Status: ✅ COMPLETE AND READY**
