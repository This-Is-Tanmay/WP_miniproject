<?php
/**
 * AJAX: Login Handler
 * POST: email, password
 * Returns: JSON
 */

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

require_once '../includes/db.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit();
}

// If already logged in
if (isLoggedIn()) {
    $redirect = isAdmin() ? '/car_rental/pages/admin.php' : '/car_rental/pages/dashboard.php';
    echo json_encode(['success' => true, 'message' => 'Already logged in.', 'redirect' => $redirect]);
    exit();
}

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

// Basic validation
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit();
}

// Lookup user
try {
    $stmt = $pdo->prepare("SELECT id, full_name, email, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        // Artificial slight delay to prevent timing attacks
        usleep(200000);
        echo json_encode(['success' => false, 'message' => 'Invalid email or password. Please try again.']);
        exit();
    }

    // Set session
    setUserSession($user);

    $redirect = ($user['role'] === 'admin') ? '/car_rental/pages/admin.php' : '/car_rental/pages/dashboard.php';

    echo json_encode([
        'success'  => true,
        'message'  => 'Login successful! Redirecting...',
        'redirect' => $redirect,
        'user'     => ['name' => $user['full_name'], 'role' => $user['role']]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again later.']);
}
