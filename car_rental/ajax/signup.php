<?php
/**
 * AJAX: Signup Handler
 * POST: full_name, email, phone, password, confirm_password
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

$full_name        = trim($_POST['full_name']        ?? '');
$email            = trim($_POST['email']            ?? '');
$phone            = trim($_POST['phone']            ?? '');
$password         = trim($_POST['password']         ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

// Validation
$errors = [];

if (empty($full_name) || strlen($full_name) < 2) {
    $errors[] = 'Full name must be at least 2 characters.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}
if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit();
}

try {
    // Check email uniqueness
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'This email is already registered. Please login.']);
        exit();
    }

    // Insert user
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, 'user')"
    );
    $stmt->execute([$full_name, $email, $hashed, $phone ?: null]);

    $userId = $pdo->lastInsertId();

    // Auto-login after signup
    $user = ['id' => $userId, 'full_name' => $full_name, 'email' => $email, 'role' => 'user'];
    setUserSession($user);

    echo json_encode([
        'success'  => true,
        'message'  => 'Account created successfully! Welcome aboard 🎉',
        'redirect' => '/car_rental/pages/dashboard.php'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again later.']);
}
