<?php
/**
 * Registration Page
 * Author: College Mini Project
 * Purpose: User registration with file upload
 */

session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'config/db.php';

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $age = sanitizeInput($_POST['age'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($full_name) || empty($email) || empty($age) || empty($password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif ($age < 18 || $age > 100) {
        $error = "Age must be between 18 and 100!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } elseif ($password !== $confirm_pass) {
        $error = "Passwords do not match!";
    } else {
        // Check if user already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Handle file upload
            $file_path = NULL;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
                $file_name = $_FILES['profile_image']['name'];
                $file_size = $_FILES['profile_image']['size'];
                $file_tmp = $_FILES['profile_image']['tmp_name'];
                $file_type = $_FILES['profile_image']['type'];
                
                // Validate file
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file_type, $allowed_types)) {
                    $error = "Invalid file type! Only JPG, PNG, and GIF allowed.";
                } elseif ($file_size > 5000000) { // 5MB
                    $error = "File size exceeds 5MB limit!";
                } else {
                    // Create unique filename
                    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $new_filename = 'profile_' . time() . '.' . $file_ext;
                    $upload_dir = 'uploads/';
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    if (move_uploaded_file($file_tmp, $upload_dir . $new_filename)) {
                        $file_path = $upload_dir . $new_filename;
                    } else {
                        $error = "Failed to upload file!";
                    }
                }
            }
            
            // Insert user if no error
            if (empty($error)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Prepare and execute insert statement
                $insert_stmt = $conn->prepare("INSERT INTO users (full_name, email, age, password, profile_image) VALUES (?, ?, ?, ?, ?)");
                
                if (!$insert_stmt) {
                    $error = "Database error: " . $conn->error;
                } else {
                    // Bind parameters: s=string, i=integer, s=string, s=string, s=string
                    $insert_stmt->bind_param("ssiss", $full_name, $email, $age, $hashed_password, $file_path);
                    
                    // Execute the statement
                    if ($insert_stmt->execute()) {
                        $success = "✅ Registration successful! Your account has been created. Redirecting to login in 3 seconds...";
                        echo "<script>
                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 3000);
                        </script>";
                    } else {
                        $error = "❌ Registration failed: " . $insert_stmt->error;
                    }
                    $insert_stmt->close();
                }
            }
        }
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - User Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 500px;
            margin: 0 auto;
            animation: slideUp 0.5s ease-out;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .register-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .register-body {
            padding: 40px;
        }
        .form-control, .form-select {
            border-radius: 5px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .register-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
        }
        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        .file-input-label {
            display: block;
            padding: 10px;
            border: 2px dashed #667eea;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .file-input-label:hover {
            background: rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>🚗 Car Rental</h1>
            <p>Create New Account</p>
        </div>
        
        <div class="register-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>❌ Error!</strong> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>✅ Success!</strong> <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="registerForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="full_name" class="form-label">👤 Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" 
                           placeholder="Enter your full name" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">📧 Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           placeholder="Enter your email" required>
                </div>
                
                <div class="mb-3">
                    <label for="age" class="form-label">🎂 Age</label>
                    <input type="number" class="form-control" id="age" name="age" min="18" max="100"
                           placeholder="Enter your age" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">🔒 Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Enter password (min 6 characters)" required>
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">🔒 Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           placeholder="Confirm password" required>
                </div>
                
                <div class="mb-3">
                    <label for="profile_image" class="form-label">📸 Profile Image (Optional)</label>
                    <label for="profile_image" class="file-input-label">
                        <input type="file" class="form-control d-none" id="profile_image" 
                               name="profile_image" accept="image/*">
                        <span>Click to upload image (JPG, PNG, GIF)</span>
                    </label>
                    <small class="text-muted d-block mt-2">Max size: 5MB</small>
                </div>
                
                <button type="submit" class="btn btn-primary btn-register">Register</button>
            </form>
        </div>
        
        <div class="register-footer">
            <p class="mb-0">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File input label update
        document.getElementById('profile_image').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.querySelector('.file-input-label span').textContent = this.files[0].name;
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const fullName = document.getElementById('full_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const age = document.getElementById('age').value;
            const password = document.getElementById('password').value;
            const confirmPass = document.getElementById('confirm_password').value;

            if (fullName === '' || email === '' || age === '' || password === '' || confirmPass === '') {
                e.preventDefault();
                alert('Please fill in all required fields!');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters!');
                return false;
            }

            if (password !== confirmPass) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            if (age < 18 || age > 100) {
                e.preventDefault();
                alert('Age must be between 18 and 100!');
                return false;
            }
        });
    </script>
</body>
</html>
