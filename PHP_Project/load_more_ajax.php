<?php
/**
 * LOAD MORE USERS VIA AJAX
 * Author: College Mini Project
 * Purpose: Pagination - Load more users
 */

session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 2;
    $search = isset($_POST['search']) ? sanitizeInput($_POST['search']) : '';
    $records_per_page = 10;
    
    try {
        $offset = ($page - 1) * $records_per_page;
        $search_query = $search ? "WHERE full_name LIKE '%$search%' OR email LIKE '%$search%'" : "";
        
        // Get total records
        $count_result = $conn->query("SELECT COUNT(*) as total FROM users $search_query");
        $count_row = $count_result->fetch_assoc();
        $total_records = $count_row['total'];
        $total_pages = ceil($total_records / $records_per_page);
        
        // Get data for this page
        if ($search) {
            $sql = "SELECT id, full_name, email, age, profile_image, created_at FROM users 
                    WHERE full_name LIKE '%$search%' OR email LIKE '%$search%' 
                    ORDER BY created_at DESC LIMIT $offset, $records_per_page";
        } else {
            $sql = "SELECT id, full_name, email, age, profile_image, created_at FROM users 
                    ORDER BY created_at DESC LIMIT $offset, $records_per_page";
        }
        
        $result = $conn->query($sql);
        
        if ($result) {
            $html = '';
            while ($row = $result->fetch_assoc()) {
                $profile_image = $row['profile_image'] ? $row['profile_image'] : 'https://via.placeholder.com/50';
                $created_date = date('M d, Y', strtotime($row['created_at']));
                
                $html .= "
                    <tr data-row-id='{$row['id']}'>
                        <td>
                            <img src='{$profile_image}' alt='Profile' style='width: 40px; height: 40px; border-radius: 50%;'>
                        </td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['age']}</td>
                        <td>{$created_date}</td>
                        <td>
                            <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>
                                <i class='fas fa-edit'></i> Edit
                            </a>
                            <button class='btn btn-sm btn-danger' onclick='deleteUserAjax({$row['id']})' data-user-id='{$row['id']}'>
                                <i class='fas fa-trash'></i> Delete
                            </button>
                        </td>
                    </tr>
                ";
            }
            
            echo json_encode([
                'success' => true,
                'html' => $html,
                'hasMore' => $page < $total_pages
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
