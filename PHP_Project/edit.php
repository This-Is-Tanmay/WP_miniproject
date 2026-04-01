<?php
/**
 * Edit User Page
 * Author: College Mini Project
 * Purpose: Update user records
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/db.php';

$error = '';
$success = '';
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get user data
if ($user_id === 0) {
    header("Location: dashboard.php");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
if ($result->num_rows === 0) {
    header("Location: dashboard.php");
    exit();
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $age = sanitizeInput($_POST['age'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($full_name) || empty($email) || empty($age)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif ($age < 18 || $age > 100) {
        $error = "Age must be between 18 and 100!";
    } else {
        // Check if email is unique (excluding current user)
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_email->bind_param("si", $email, $user_id);
        $check_email->execute();
        $check_result = $check_email->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            // Handle file upload if new file is provided
            $file_path = $user['profile_image'];
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
                $file_name = $_FILES['profile_image']['name'];
                $file_size = $_FILES['profile_image']['size'];
                $file_tmp = $_FILES['profile_image']['tmp_name'];
                $file_type = $_FILES['profile_image']['type'];
                
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file_type, $allowed_types)) {
                    $error = "Invalid file type! Only JPG, PNG, and GIF allowed.";
                } elseif ($file_size > 5000000) {
                    $error = "File size exceeds 5MB limit!";
                } else {
                    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $new_filename = 'profile_' . time() . '.' . $file_ext;
                    $upload_dir = 'uploads/';
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    if (move_uploaded_file($file_tmp, $upload_dir . $new_filename)) {
                        // Delete old file
                        if ($file_path && file_exists($file_path)) {
                            unlink($file_path);
                        }
                        $file_path = $upload_dir . $new_filename;
                    } else {
                        $error = "Failed to upload file!";
                    }
                }
            }
            
            // Update user if no error
            if (empty($error)) {
                // Update with or without password change
                if (!empty($password)) {
                    if (strlen($password) < 6) {
                        $error = "Password must be at least 6 characters!";
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, age = ?, password = ?, profile_image = ? WHERE id = ?");
                        $stmt->bind_param("ssissi", $full_name, $email, $age, $hashed_password, $file_path, $user_id);
                    }
                } else {
                    $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, age = ?, profile_image = ? WHERE id = ?");
                    $stmt->bind_param("ssssi", $full_name, $email, $age, $file_path, $user_id);
                }

                if (isset($stmt) && $stmt->execute()) {
                    $_SESSION['message'] = "User updated successfully!";
                    $_SESSION['msg_type'] = "success";
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Failed to update user: " . (isset($stmt) ? $stmt->error : "Unknown error");
                }
            }
        }
        $check_email->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f5f7fa;
            padding: 30px 20px;
        }

        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header h1 {
            color: #667eea;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }

        .profile-image {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .profile-image img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
            margin-bottom: 15px;
        }

        .file-input-label {
            display: block;
            padding: 15px;
            border: 2px dashed #667eea;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(102, 126, 234, 0.05);
        }

        .file-input-label:hover {
            background: rgba(102, 126, 234, 0.1);
            border-color: #764ba2;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            margin-top: 10px;
            width: 100%;
            text-align: center;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-link:hover {
            transform: translateX(-5px);
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <div class="form-header">
            <h1><i class="fas fa-user-edit"></i> Edit User</h1>
            <p class="text-muted">Update user information</p>
        </div>

        <div class="info-box">
            <strong><i class="fas fa-info-circle"></i> Note:</strong> Leave password field empty to keep the current password
        </div>

        <?php if (!empty($error)): ?>
            <?php echo showError($error); ?>
        <?php endif; ?>

        <!-- Current Profile Image -->
        <?php if ($user['profile_image'] && file_exists($user['profile_image'])): ?>
            <div class="profile-image">
                <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile">
                <p class="small text-muted">Current Profile Image</p>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="editUserForm">
            <div class="form-group">
                <label for="full_name" class="form-label">
                    <i class="fas fa-user"></i> Full Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="full_name" name="full_name" 
                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="age" class="form-label">
                    <i class="fas fa-birthday-cake"></i> Age <span class="text-danger">*</span>
                </label>
                <input type="number" class="form-control" id="age" name="age" min="18" max="100"
                       value="<?php echo $user['age']; ?>" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password (Leave blank to keep current)
                </label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Enter new password (min 6 characters)">
            </div>

            <div class="form-group">
                <label for="profile_image" class="form-label">
                    <i class="fas fa-image"></i> Change Profile Image
                </label>
                <label for="profile_image" class="file-input-label">
                    <input type="file" class="form-control d-none" id="profile_image" 
                           name="profile_image" accept="image/*">
                    <span id="file-name"><i class="fas fa-cloud-upload-alt"></i> Click to upload new image (JPG, PNG, GIF)</span>
                </label>
                <small class="text-muted d-block mt-2">Max size: 5MB</small>
            </div>

            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i> Update User
            </button>
            <a href="dashboard.php" class="btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File input update
        document.getElementById('profile_image').addEventListener('change', function() {
            const fileName = this.files.length > 0 ? this.files[0].name : 'Click to upload new image (JPG, PNG, GIF)';
            document.getElementById('file-name').innerHTML = fileName;
        });

        // Form validation
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            const fullName = document.getElementById('full_name').value.trim();
            const password = document.getElementById('password').value;

            if (fullName.length < 3) {
                e.preventDefault();
                alert('Full name must be at least 3 characters!');
                return false;
            }

            if (password !== '' && password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters!');
                return false;
            }
        });
    </script>
</body>
</html>
