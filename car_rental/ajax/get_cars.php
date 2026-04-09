<?php
/**
 * AJAX: Get Cars
 * GET: type (optional filter), available (optional)
 * Returns: JSON array of cars
 */

header('Content-Type: application/json; charset=UTF-8');

require_once '../includes/db.php';

$type = trim($_GET['type'] ?? '');
$available = isset($_GET['available']) ? (int)$_GET['available'] : null;

try {
    $sql    = "SELECT * FROM cars WHERE 1=1";
    $params = [];

    if (!empty($type) && $type !== 'All') {
        $sql      .= " AND type = ?";
        $params[]  = $type;
    }
    if ($available !== null) {
        $sql      .= " AND available = ?";
        $params[]  = $available;
    }

    $sql .= " ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $cars = $stmt->fetchAll();

    // Add image URL to each car
    foreach ($cars as &$car) {
        $imgPath = '/car_rental/assets/images/cars/' . $car['image'];
        $car['image_url'] = $imgPath;
        $car['price_formatted'] = 'Rs. ' . number_format($car['price_per_day'], 0) . '/day';
    }

    echo json_encode(['success' => true, 'data' => $cars, 'total' => count($cars)]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to fetch cars.']);
}
