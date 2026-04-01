<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="styles.css"> 
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 
</head>
<body>
  <div class="wrapper">
    <form action="" method="POST">
      <h1>Login</h1>
      
      <!-- Display error message if login fails -->
      <?php
      session_start();
      require_once 'config/db.php';
      
      $error_message = '';
      
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $email = sanitizeInput($_POST['email'] ?? '');
          $password = $_POST['password'] ?? '';
          
          // Validate input
          if (empty($email) || empty($password)) {
              $error_message = "Email and password are required!";
          } else {
              // Use prepared statement to fetch user
              $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
              
              if ($stmt) {
                  $stmt->bind_param("s", $email);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  
                  if ($result->num_rows == 1) {
                      $user = $result->fetch_assoc();
                      
                      // Verify password
                      if (verifyPassword($password, $user['password'])) {
                          // Password correct - create session
                          $_SESSION['user_id'] = $user['id'];
                          $_SESSION['user_name'] = $user['full_name'];
                          $_SESSION['user_email'] = $user['email'];
                          
                          // Redirect to dashboard
                          header("Location: dashboard.php");
                          exit();
                      } else {
                          $error_message = "Invalid password!";
                      }
                  } else {
                      $error_message = "Email not found. Please register first!";
                  }
                  
                  $stmt->close();
              } else {
                  $error_message = "Database error: " . $conn->error;
              }
          }
      }
      ?>
      
      <!-- Show error if exists -->
      <?php if (!empty($error_message)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
          ❌ <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>
      
      <div class="input-box">
        <input type="email" name="email" placeholder="Email" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Password" required>
        <i class='bx bxs-lock-alt' ></i>
      </div>
      <div class="remember-forgot">
        <label><input type="checkbox">Remember Me</label>
        <a href="#">Forgot Password?</a>
      </div>
      <button type="submit" class="btn">Login</button>
      <div class="register-link">
        <p>Don't have an account? <a href="register.php">Register</a></p>
      </div>
    </form>
  </div>
</body>
</html>
