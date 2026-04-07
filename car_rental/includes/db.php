<?php
/**
 * Database Configuration & Connection
 * Using PDO with prepared statements for security
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Change if your MySQL root has a password
define('DB_NAME', 'car_rental');
define('DB_CHARSET', 'utf8mb4');

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    DB_HOST, DB_NAME, DB_CHARSET
);

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Return JSON error for AJAX requests, HTML for normal requests
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database connection failed. Please check XAMPP and try again.']);
    } else {
        http_response_code(500);
        echo '<h2 style="font-family:sans-serif;color:red;text-align:center;padding:50px;">
              ⚠️ Database Error<br><small style="color:#666;font-size:14px;">Could not connect to MySQL. Make sure XAMPP is running and the database is imported.</small></h2>';
    }
    exit();
}
