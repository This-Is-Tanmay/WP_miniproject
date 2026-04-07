/**
 * AJAX Functions for User Management System
 * Author: College Mini Project
 * Purpose: Handle asynchronous operations without page refresh
 */

// ============================================
// DELETE USER VIA AJAX
// ============================================
function deleteUserAjax(userId) {
    // Confirm before deletion
    if (!confirm('Are you sure you want to delete this user?')) {
        return false;
    }

    // Show loading state
    const deleteBtn = document.querySelector(`[data-user-id="${userId}"]`);
    if (deleteBtn) {
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        deleteBtn.disabled = true;
    }

    // Make AJAX request
    fetch('delete_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + userId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('User deleted successfully!', 'success');
            // Remove row from table after 1 second
            setTimeout(() => {
                document.querySelector(`[data-row-id="${userId}"]`).remove();
            }, 1000);
        } else {
            showAlert(data.message || 'Failed to delete user!', 'danger');
            // Restore button
            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Delete';
                deleteBtn.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error deleting user!', 'danger');
        // Restore button
        if (deleteBtn) {
            deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Delete';
            deleteBtn.disabled = false;
        }
    });

    return false;
}

// ============================================
// SEARCH USERS VIA AJAX (Real-time)
// ============================================
function searchUsersAjax(query) {
    if (query.length === 0) {
        document.getElementById('searchResults').innerHTML = '';
        return;
    }

    fetch('search_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'search=' + encodeURIComponent(query)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displaySearchResults(data.results);
        } else {
            document.getElementById('searchResults').innerHTML = 
                '<p class="text-muted p-3">No results found</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('searchResults').innerHTML = 
            '<p class="text-danger p-3">Error searching users</p>';
    });
}

// Display search results
function displaySearchResults(results) {
    const resultsDiv = document.getElementById('searchResults');
    
    if (results.length === 0) {
        resultsDiv.innerHTML = '<p class="text-muted p-3">No users found</p>';
        return;
    }

    let html = '<div class="search-results-list">';
    results.forEach(user => {
        html += `
            <div class="search-result-item p-2 border-bottom hover-highlight">
                <a href="javascript:void(0)" onclick="selectSearchResult('${user.id}', '${user.full_name}')">
                    <strong>${user.full_name}</strong>
                    <small class="text-muted d-block">${user.email}</small>
                </a>
            </div>
        `;
    });
    html += '</div>';
    
    resultsDiv.innerHTML = html;
}

// Select from search results
function selectSearchResult(userId, userName) {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = userName;
    }
    document.getElementById('searchResults').innerHTML = '';
    
    // Optional: Navigate to edit page
    // window.location.href = 'edit.php?id=' + userId;
}

// ============================================
// EMAIL VALIDATION VIA AJAX
// ============================================
function validateEmailAjax(email, callback) {
    fetch('validate_email_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
        callback(data.exists);
    })
    .catch(error => {
        console.error('Error:', error);
        callback(false);
    });
}

// Check email on blur
function checkEmailExists(emailInput) {
    const email = emailInput.value;
    const feedbackElement = document.getElementById('emailFeedback');
    
    if (!email) {
        feedbackElement.innerHTML = '';
        return;
    }

    feedbackElement.innerHTML = '<small class="text-info"><i class="fas fa-spinner fa-spin"></i> Checking...</small>';

    validateEmailAjax(email, function(exists) {
        if (exists) {
            feedbackElement.innerHTML = '<small class="text-danger"><i class="fas fa-times-circle"></i> Email already exists!</small>';
            emailInput.classList.add('is-invalid');
            emailInput.classList.remove('is-valid');
        } else {
            feedbackElement.innerHTML = '<small class="text-success"><i class="fas fa-check-circle"></i> Email available!</small>';
            emailInput.classList.add('is-valid');
            emailInput.classList.remove('is-invalid');
        }
    });
}

// ============================================
// FORM VALIDATION
// ============================================
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    const fullName = form.querySelector('[name="full_name"]');
    const email = form.querySelector('[name="email"]');
    const age = form.querySelector('[name="age"]');
    const password = form.querySelector('[name="password"]');

    let isValid = true;

    // Validate full name
    if (fullName && !fullName.value.trim()) {
        showFieldError(fullName, 'Full name is required');
        isValid = false;
    } else if (fullName) {
        clearFieldError(fullName);
    }

    // Validate email
    if (email && !email.value.trim()) {
        showFieldError(email, 'Email is required');
        isValid = false;
    } else if (email && !isValidEmail(email.value)) {
        showFieldError(email, 'Invalid email format');
        isValid = false;
    } else if (email) {
        clearFieldError(email);
    }

    // Validate age
    if (age && !age.value) {
        showFieldError(age, 'Age is required');
        isValid = false;
    } else if (age && (age.value < 18 || age.value > 100)) {
        showFieldError(age, 'Age must be between 18 and 100');
        isValid = false;
    } else if (age) {
        clearFieldError(age);
    }

    // Validate password
    if (password && !password.value) {
        showFieldError(password, 'Password is required');
        isValid = false;
    } else if (password && password.value.length < 6) {
        showFieldError(password, 'Password must be at least 6 characters');
        isValid = false;
    } else if (password) {
        clearFieldError(password);
    }

    return isValid;
}

// Helper function to validate email format
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    field.classList.remove('is-valid');
    let feedback = field.nextElementSibling;
    
    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback d-block';
        field.parentNode.appendChild(feedback);
    }
    
    feedback.textContent = message;
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
}

// ============================================
// SHOW ALERT MESSAGES
// ============================================
function showAlert(message, type = 'info') {
    const alertId = 'alert-' + Date.now();
    const alertHtml = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    // Insert at top of page
    const alertContainer = document.querySelector('.alert-container') || 
                          document.querySelector('main') || 
                          document.body;
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = alertHtml;
    alertContainer.insertBefore(tempDiv.firstElementChild, alertContainer.firstChild);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// ============================================
// LOAD MORE PAGINATION
// ============================================
function loadMoreUsers(page) {
    const pageNum = page || 2;
    const search = document.getElementById('searchInput')?.value || '';

    fetch('load_more_ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'page=' + pageNum + '&search=' + encodeURIComponent(search)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const tbody = document.querySelector('table tbody');
            const newRows = document.createElement('tbody');
            newRows.innerHTML = data.html;
            
            // Append new rows to table
            const rows = newRows.querySelectorAll('tr');
            rows.forEach(row => tbody.appendChild(row));

            // Hide load more button if no more pages
            if (!data.hasMore) {
                const btn = document.getElementById('loadMoreBtn');
                if (btn) btn.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error loading more users!', 'danger');
    });
}

// ============================================
// INITIALIZE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Real-time search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchUsersAjax(this.value);
            }, 300);
        });
    }

    // Email validation on blur
    const emailInputs = document.querySelectorAll('input[type="email"][name="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            checkEmailExists(this);
        });
    });

    // Form validation on submit
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this.id)) {
                e.preventDefault();
            }
        });
    });
});
