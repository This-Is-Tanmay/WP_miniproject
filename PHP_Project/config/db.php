<?php
/**
 * Database Configuration File
 * Author: College Mini Project
 * Purpose: Secure database connection using MySQLi
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_rental_db');

// Create connection using MySQLi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    return $conn->real_escape_string(stripslashes(trim($data)));
}

// Function to display success message
function showSuccess($message) {
    return "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Success!</strong> $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// Function to display error message
function showError($message) {
    return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>Error!</strong> $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

// Function to redirect
function redirectTo($url) {
    header("Location: $url");
    exit();
}

// Session configuration
ini_set('session.gc_maxlifetime', 3600); // 1 hour session timeout
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'secure' => false, // true if using HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

?>
