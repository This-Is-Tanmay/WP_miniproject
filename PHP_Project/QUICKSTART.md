# ⚡ Quick Start Guide

## 5-Minute Setup

### 1️⃣ Start Services
```
XAMPP Control Panel → Start Apache → Start MySQL
```

### 2️⃣ Copy Files
```
Copy PHP_Project folder to C:\xampp\htdocs\
```

### 3️⃣ Import Database
```
Open http://localhost/phpmyadmin
SQL Tab → Paste database.sql → Go
```

### 4️⃣ Access Application
```
Open http://localhost/PHP_Project/login.php
```

### 5️⃣ Login
```
Email: demo@example.com
Password: password123
```

---

## ✨ What You Can Do

### ✅ Login
- Use demo credentials
- Sessions are tracked
- Logout anytime

### ✅ Add Users (CREATE)
- Click "Add New User"
- Upload profile image
- Data saved to database

### ✅ View Users (READ)
- See all users in table
- Search by name/email
- Paginate through results

### ✅ Edit Users (UPDATE)
- Click "Edit" button
- Change any information
- Update database

### ✅ Delete Users (DELETE)
- Click "Delete" button
- Confirm action
- User removed

---

## 🎯 Key Features Tested

✅ **Session Management** - Login/Logout tracking
✅ **Form Validation** - Frontend + Backend checks
✅ **File Upload** - Profile images stored in uploads/
✅ **Search** - Find users by name/email
✅ **Pagination** - View 10 users per page
✅ **CRUD** - All operations working
✅ **Security** - Prepared statements, password hashing
✅ **Responsive** - Works on mobile!

---

## 📁 File Structure

```
PHP_Project/
├── config/db.php          ← Database connection
├── login.php              ← Login page
├── register.php           ← Registration
├── dashboard.php          ← Main dashboard (R)
├── add.php                ← Add user (C)
├── edit.php               ← Edit user (U)
├── logout.php             ← Logout (destroy session)
├── uploads/               ← Profile images folder
└── database.sql           ← Database setup
```

---

## 🔧 Troubleshooting

| Issue | Solution |
|-------|----------|
| Can't connect database | Check MySQL is running |
| Upload not working | Check uploads/ folder exists |
| Session lost | Clear browser cookies |
| Email field shows error | Email might already exist |

---

## 📊 Demo Database

```
User Table
├── User 1: demo@example.com (password: password123)
├── User 2: john@example.com
└── User 3: jane@example.com
```

---

## 🎓 Learning Points

1. **PHP Sessions** - Track logged-in users
2. **MySQL Queries** - CRUD operations
3. **Security** - Prepared statements, hashing
4. **Forms** - Validation, file upload
5. **Responsive Design** - Bootstrap 5
6. **Pagination** - Show data in chunks
7. **Search** - Filter users dynamically

---

## ✅ Completion Checklist

- [ ] XAMPP running
- [ ] Files in C:\xampp\htdocs\PHP_Project\
- [ ] Database imported
- [ ] Can login with demo@example.com
- [ ] Can add new user
- [ ] Can edit user
- [ ] Can delete user
- [ ] Can search users
- [ ] Profile images upload working
- [ ] Responsive on mobile browser

---

## 🎬 Demo Actions

1. **Register** → New user auto-redirects to login
2. **Login** → Taken to dashboard
3. **Add** → New user appears in table instantly
4. **Search** → Filter results dynamically
5. **Edit** → Changes reflect in table
6. **Delete** → Confirmation + removal
7. **Logout** → Session destroyed, redirected to login

---

## 🚀 Ready to Submit!

All requirements completed:
✅ 5+ form fields (name, email, age, password, image)
✅ Full CRUD operations
✅ MySQL database with proper schema
✅ Session tracking & authentication
✅ Prepared statements (SQL injection safe)
✅ File upload functionality
✅ Search & pagination
✅ Modern responsive UI
✅ Form validation (frontend + backend)
✅ Error handling

---

**Questions? Check README.md for detailed documentation!**
