<?php
/**
 * Dashboard - Only accessible if logged in
 */
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login page/login.php");
    exit();
}

require_once '../Login page/config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #333;
            font-size: 28px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-details {
            text-align: right;
        }
        
        .user-details p {
            margin: 5px 0;
            color: #666;
        }
        
        .user-details strong {
            color: #333;
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
        
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
        }
        
        .content h2 {
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        .success-box {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚗 Car Rental Dashboard</h1>
            <div class="user-info">
                <div class="user-details">
                    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p>
                    <p style="font-size: 12px;"><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                </div>
                <form action="logout.php" method="POST" style="margin: 0;">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
        
        <div class="content">
            <div class="success-box">
                ✅ <strong>Login Successful!</strong> You are now logged in.
            </div>
            
            <h2>Your Dashboard</h2>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            
            <h2 style="margin-top: 30px;">Welcome to Your Dashboard</h2>
            <p>You have successfully logged into the Car Rental Management System. You can now access all available features.</p>
            <p>Click the <strong>Logout</strong> button above to safely end your session.</p>
        </div>
    </div>
</body>
</html>
