<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="Signup.css">
</head>

<body>
  <div class="home-btn">
    <a href="../front page/index.html">← Back to Home</a>
  </div>
  <div class="wrapper">
    <div class="title-text">
      <div class="title signup">Create Account</div>
    </div>

    <div class="form-container">
      <div class="form-inner">
        
        <!-- Display error/success message -->
        <?php
        session_start();
        require_once '../Login page/config/db.php';
        
        $error_message = '';
        $success_message = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = sanitizeInput($_POST['fullname'] ?? '');
            $email = sanitizeInput($_POST['email'] ?? '');
            $username = sanitizeInput($_POST['username'] ?? '');
            $phone = sanitizeInput($_POST['phone'] ?? '');
            $address = sanitizeInput($_POST['address'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validation
            if (empty($fullname) || empty($email) || empty($username) || empty($phone) || empty($address) || empty($password)) {
                $error_message = "All fields are required!";
            } 
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Invalid email format!";
            }
            elseif (strlen($password) < 6) {
                $error_message = "Password must be at least 6 characters!";
            }
            elseif ($password !== $confirm_password) {
                $error_message = "Passwords do not match!";
            }
            else {
                // Check if email or username already exists
                $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
                
                if ($check_stmt) {
                    $check_stmt->bind_param("ss", $email, $username);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();
                    
                    if ($check_result->num_rows > 0) {
                        $error_message = "Email or Username already registered!";
                    } else {
                        // Hash password
                        $hashed_password = hashPassword($password);
                        
                        // Insert user into database
                        $insert_stmt = $conn->prepare("INSERT INTO users (full_name, email, username, phone, address, password) VALUES (?, ?, ?, ?, ?, ?)");
                        
                        if ($insert_stmt) {
                            $insert_stmt->bind_param("ssssss", $fullname, $email, $username, $phone, $address, $hashed_password);
                            
                            if ($insert_stmt->execute()) {
                                $success_message = "✅ Registration successful! Redirecting to login...";
                                echo "<script>
                                    setTimeout(function() {
                                        window.location.href = '../Login page/login.php';
                                    }, 2000);
                                </script>";
                            } else {
                                $error_message = "Registration failed: " . $insert_stmt->error;
                            }
                            
                            $insert_stmt->close();
                        } else {
                            $error_message = "Database error: " . $conn->error;
                        }
                    }
                    
                    $check_stmt->close();
                } else {
                    $error_message = "Database error: " . $conn->error;
                }
            }
        }
        ?>
        
        <!-- Show error message if exists -->
        <?php if (!empty($error_message)): ?>
          <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb; font-weight: bold;">
            ❌ <?php echo htmlspecialchars($error_message); ?>
          </div>
        <?php endif; ?>
        
        <!-- Show success message if exists -->
        <?php if (!empty($success_message)): ?>
          <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb; font-weight: bold;">
            <?php echo htmlspecialchars($success_message); ?>
          </div>
        <?php endif; ?>
        
        <form class="signup-form" method="POST" action="">
          <div class="field">
            <input type="text" name="fullname" placeholder="Full Name" required>
          </div>

          <div class="field">
            <input type="email" name="email" placeholder="Email Address" required>
          </div>

          <div class="field">
            <input type="text" name="username" placeholder="Username" required>
          </div>

          <div class="field">
            <input type="tel" name="phone" placeholder="Phone" required>
          </div>

          <div class="field">
            <input type="text" name="address" placeholder="Address" required>
          </div>

          <div class="field">
            <input type="password" name="password" placeholder="Password" required>
          </div>
          <div class="field">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          </div>
          <div class="field btn">
            <input type="submit" value="Sign Up">
          </div>
        </form>

        <div class="login">
          <p>Already an user? <a href="../Login page/login.php">Login</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
