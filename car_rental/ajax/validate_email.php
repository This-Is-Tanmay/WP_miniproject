<?php
/**
 * AJAX: Validate Email (real-time uniqueness check)
 * POST: email
 * Returns: JSON
 */

header('Content-Type: application/json');

require_once '../includes/db.php';

$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['available' => false, 'message' => 'Enter a valid email address.']);
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $exists = $stmt->fetch();

    if ($exists) {
        echo json_encode(['available' => false, 'message' => 'This email is already registered.']);
    } else {
        echo json_encode(['available' => true, 'message' => 'Email is available.']);
    }
} catch (PDOException $e) {
    echo json_encode(['available' => false, 'message' => 'Validation failed.']);
}
