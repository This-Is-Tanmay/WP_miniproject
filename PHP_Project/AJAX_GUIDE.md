# AJAX Implementation Guide

## 📚 Overview

This document explains the AJAX functionality implemented in the Car Rental User Management System.

---

## 🎯 What Has Been Added

### 1. **JavaScript File: `js/script.js`**
   - Contains all AJAX functions
   - Handles form validation
   - Real-time user search
   - Email validation
   - Delete operations
   - Pagination

### 2. **PHP Endpoints (AJAX Handlers)**

#### a) `delete_ajax.php`
- **Purpose:** Delete user via AJAX
- **Method:** POST
- **Parameters:** `id` (user ID)
- **Response:** JSON with success/failure status
- **Returns:**
  ```json
  {"success": true, "message": "User deleted successfully"}
  ```

#### b) `search_ajax.php`
- **Purpose:** Real-time user search
- **Method:** POST
- **Parameters:** `search` (search query)
- **Response:** JSON array of matching users
- **Returns:**
  ```json
  {
    "success": true,
    "results": [
      {"id": 1, "full_name": "John", "email": "john@example.com", "age": 25, "profile_image": "..."},
      ...
    ]
  }
  ```

#### c) `validate_email_ajax.php`
- **Purpose:** Check if email already exists
- **Method:** POST
- **Parameters:** `email` (user email)
- **Response:** JSON with existence status
- **Returns:**
  ```json
  {"success": true, "exists": false}
  ```

#### d) `load_more_ajax.php`
- **Purpose:** Load more users for pagination
- **Method:** POST
- **Parameters:** `page`, `search` (optional)
- **Response:** JSON with HTML table rows
- **Returns:**
  ```json
  {
    "success": true,
    "html": "<tr>...</tr>",
    "hasMore": true
  }
  ```

---

## 🚀 How to Use

### 1. **Delete User without Page Reload**

**Before (Old Way):**
```html
<a href="dashboard.php?action=delete&id=5" onclick="return confirm('Delete?')">Delete</a>
```

**After (AJAX Way):**
```html
<button onclick="deleteUserAjax(5)" data-user-id="5">Delete</button>
```

**How It Works:**
- Click delete button
- Confirmation dialog appears
- User is deleted via AJAX
- Table row is removed smoothly
- Alert message shows success/failure

---

### 2. **Real-Time User Search**

**Updated Dashboard Search:**
```html
<input type="text" id="searchInput" placeholder="Search by name or email...">
<div id="searchResults"></div>
```

**Features:**
- Type to search users instantly
- Results appear in a dropdown
- No page refresh needed
- 300ms debounce for performance
- Click on result to select

---

### 3. **Email Validation on Registration**

**Updated Add/Register Forms:**
```html
<input type="email" name="email" onblur="checkEmailExists(this)">
<div id="emailFeedback"></div>
```

**Features:**
- Check email availability as you type
- Visual feedback (valid/invalid)
- Prevents duplicate registrations
- Real-time validation

---

### 4. **Form Validation**

**Client-Side Validation:**
```javascript
validateForm('userForm');
```

**Validates:**
- Full name (required, non-empty)
- Email (required, valid format)
- Age (required, 18-100)
- Password (required, 6+ characters)

---

## 📋 File Structure

```
PHP_Project/
├── js/
│   └── script.js              ← NEW: All AJAX functions
├── delete_ajax.php            ← NEW: Delete handler
├── search_ajax.php            ← NEW: Search handler
├── validate_email_ajax.php    ← NEW: Email validation
├── load_more_ajax.php         ← NEW: Pagination handler
├── dashboard.php              ← UPDATED: Uses AJAX
├── add.php                    ← (Can be updated for validation)
├── edit.php                   ← (Can be updated for validation)
└── ... other files
```

---

## 💡 Key Functions in `script.js`

### 1. **deleteUserAjax(userId)**
```javascript
deleteUserAjax(5); // Delete user with ID 5
```

### 2. **searchUsersAjax(query)**
```javascript
searchUsersAjax('John'); // Search for "John"
```

### 3. **validateEmailAjax(email, callback)**
```javascript
validateEmailAjax('user@example.com', function(exists) {
    if (exists) {
        console.log('Email already exists');
    }
});
```

### 4. **checkEmailExists(emailInput)**
```html
<input type="email" onblur="checkEmailExists(this)">
```

### 5. **validateForm(formId)**
```javascript
if (validateForm('addUserForm')) {
    // Form is valid, submit
}
```

### 6. **showAlert(message, type)**
```javascript
showAlert('User deleted successfully!', 'success');
showAlert('Error deleting user!', 'danger');
```

---

## 🔧 How to Integrate into Other Pages

### **Add AJAX Script to Any Page:**

```html
<!-- Add this before closing </body> tag -->
<script src="js/script.js"></script>
```

### **Use AJAX Delete Button:**

```html
<button class="btn btn-danger" onclick="deleteUserAjax(<?php echo $user_id; ?>)" data-user-id="<?php echo $user_id; ?>">
    <i class="fas fa-trash"></i> Delete
</button>
```

### **Use Email Validation:**

```html
<input type="email" name="email" 
       onblur="checkEmailExists(this)" 
       id="emailInput">
<div id="emailFeedback"></div>
```

### **Use Form Validation:**

```html
<form id="myForm" onsubmit="return validateForm('myForm')">
    <!-- form fields -->
</form>
```

---

## ⚙️ Browser Console Debugging

Open Developer Tools (F12) and check the Console tab to see:
- AJAX request/response details
- Any JavaScript errors
- Network activity

**Check Network Tab to see AJAX calls:**
1. Open DevTools (F12)
2. Go to Network tab
3. Perform AJAX action (search, delete, etc.)
4. See the request/response details

---

## 🎨 Styling for AJAX Elements

Add these CSS classes to customize appearance:

```css
.search-results-list { /* Search dropdown */ }
.search-result-item { /* Individual search result */ }
.search-result-item.hover-highlight { /* On hover */ }
.invalid-feedback { /* Error messages */ }
.is-valid { /* Valid field */ }
.is-invalid { /* Invalid field */ }
```

---

## 📱 Mobile Responsiveness

All AJAX features are mobile-responsive:
- Search works on mobile
- Delete buttons are touch-friendly
- Alert messages are responsive
- Pagination adapts to screen size

---

## 🔒 Security Notes

**Current Implementation:**
- ✅ Session checks on all endpoints
- ✅ Input sanitization using `sanitizeInput()`
- ✅ Prepared statements for email validation
- ✅ File validation before deletion

**Best Practices:**
- All AJAX endpoints verify `$_SESSION['user_id']`
- User inputs are sanitized
- File operations check file existence
- JSON responses prevent XSS

---

## 📝 Testing the AJAX Features

### **Test 1: Delete User**
1. Go to Dashboard
2. Click Delete button on any user
3. Confirm deletion
4. Row should disappear without refresh

### **Test 2: Search Users**
1. Go to Dashboard
2. Type in search box
3. Results should appear instantly
4. Click on result to select

### **Test 3: Email Validation**
1. Go to Add User page
2. Enter an existing email
3. On blur, error message appears
4. Email field shows red border

### **Test 4: Form Validation**
1. Go to Add User page
2. Try to submit with empty fields
3. Error messages appear for each field
4. Form doesn't submit until valid

---

## 🐛 Troubleshooting

### **AJAX not working?**
- Check if `js/script.js` is loaded (DevTools → Network tab)
- Check browser console for errors (F12 → Console)
- Verify PHP files exist and are in correct location

### **Search not showing results?**
- Check if search query is being sent (DevTools → Network tab)
- Verify database has users with matching names/emails
- Check `search_ajax.php` response

### **Delete button not responding?**
- Check if `deleteUserAjax()` function is called (see Network tab)
- Verify `delete_ajax.php` is deleting correctly
- Check database permissions

### **Email validation not working?**
- Ensure email field has correct ID `#emailInput`
- Check `validate_email_ajax.php` response
- Verify database connection in `config/db.php`

---

## 📈 Performance Tips

1. **Search Debouncing:** Searches have 300ms delay to reduce server load
2. **Pagination:** Load 10 users per page instead of all users
3. **Caching:** Consider caching frequently searched users
4. **Database Indexes:** Index `full_name` and `email` columns for faster searches

---

## 🚀 Future Enhancements

Possible improvements:
- [ ] Add AJAX for edit user (without page refresh)
- [ ] Add AJAX for add user (form submission without refresh)
- [ ] Add user export to CSV via AJAX
- [ ] Add bulk delete functionality
- [ ] Add advanced filters (age, date range, etc.)
- [ ] Add user activity logging
- [ ] Add real-time notifications
- [ ] Add autocomplete for search

---

## 📞 Support

For issues or questions:
1. Check the Troubleshooting section above
2. Review browser console for errors (F12)
3. Check Network tab to see API responses
4. Verify all PHP files are in correct locations

---

**Last Updated:** April 4, 2026
**Author:** College Mini Project Team
