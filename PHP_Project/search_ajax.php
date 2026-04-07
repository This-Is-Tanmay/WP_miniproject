<?php
/**
 * SEARCH USERS VIA AJAX
 * Author: College Mini Project
 * Purpose: Search users in real-time without page refresh
 */

session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search = sanitizeInput($_POST['search']);
    
    try {
        $max_results = 10;
        $sql = "SELECT id, full_name, email, age, profile_image FROM users 
                WHERE full_name LIKE '%$search%' OR email LIKE '%$search%' 
                LIMIT $max_results";
        
        $result = $conn->query($sql);
        
        if ($result) {
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            
            echo json_encode([
                'success' => true,
                'results' => $users
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Query error: ' . $conn->error
            ]);
        }
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
