<?php
/**
 * DELETE USER VIA AJAX
 * Author: College Mini Project
 * Purpose: Handle user deletion without page refresh
 */

session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $delete_id = (int)$_POST['id'];
    
    try {
        // Get file path before deletion
        $result = $conn->query("SELECT profile_image FROM users WHERE id = $delete_id");
        $row = $result->fetch_assoc();
        
        // Delete from database
        $delete_result = $conn->query("DELETE FROM users WHERE id = $delete_id");
        
        if ($delete_result) {
            // Delete file if exists
            if ($row && $row['profile_image'] && file_exists($row['profile_image'])) {
                unlink($row['profile_image']);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete user: ' . $conn->error
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
