<?php
require_once __DIR__ . '/../includes/db.php';

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

  $email = $_POST['email'];
  $password = $_POST['password'];

  if(strlen($password) < 6){
    $message = "Password must be at least 6 characters";
  } else {

    // CHECK EMAIL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user){

      $hashed = password_hash($password, PASSWORD_DEFAULT);

      // UPDATE PASSWORD
      $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
      $update->execute([$hashed, $email]);

      $message = "Password updated successfully!";
    } else {
      $message = "Email not found!";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <style>
    body{
      margin:0;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg,#0f172a,#1e293b);
      height:100vh;
      display:flex;
      justify-content:center;
      align-items:center;
    }

    .box{
      background:#fff;
      padding:30px;
      border-radius:12px;
      width:350px;
      text-align:center;
      box-shadow:0 10px 30px rgba(0,0,0,0.2);
    }

    input{
      width:100%;
      padding:12px;
      margin:10px 0;
      border-radius:8px;
      border:1px solid #ccc;
    }

    button{
      width:100%;
      padding:12px;
      border:none;
      border-radius:25px;
      background:linear-gradient(to right,#3b82f6,#06b6d4);
      color:#fff;
      cursor:pointer;
    }

    .msg{
      margin-top:10px;
      color:red;
    }

    .success{
      color:green;
    }
  </style>
</head>

<body>

<div class="box">
  <h2>Reset Password</h2>

  <form method="POST">
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="password" name="password" placeholder="New password" required>
    <button type="submit">Reset Password</button>
  </form>

  <?php if($message != ""): ?>
    <div class="msg <?php echo ($message == 'Password updated successfully!') ? 'success' : ''; ?>">
      <?php echo $message; ?>
    </div>
  <?php endif; ?>

  <a href="login.php">← Back to Login</a>
</div>

</body>
</html>
