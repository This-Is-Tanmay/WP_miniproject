<?php
/**
 * EMAIL VALIDATION VIA AJAX
 * Author: College Mini Project
 * Purpose: Check if email already exists
 */

session_start();
header('Content-Type: application/json');

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = sanitizeInput($_POST['email']);
    
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        $exists = $stmt->num_rows > 0;
        
        echo json_encode([
            'success' => true,
            'exists' => $exists
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
