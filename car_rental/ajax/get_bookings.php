<?php
/**
 * AJAX: Get Bookings (for logged-in user)
 * GET: (none)
 * Returns: JSON array of bookings with car details
 */

header('Content-Type: application/json; charset=UTF-8');

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireLogin();

try {
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare(
        "SELECT b.id, b.start_date, b.end_date, b.total_days, b.total_price, b.status, b.created_at,
                c.name AS car_name, c.brand AS car_brand, c.type AS car_type, c.image AS car_image
         FROM bookings b
         JOIN cars c ON b.car_id = c.id
         WHERE b.user_id = ?
         ORDER BY b.created_at DESC"
    );
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll();

    foreach ($bookings as &$booking) {
        $booking['car_image_url']    = '/car_rental/assets/images/cars/' . $booking['car_image'];
        $booking['total_price_fmt']  = 'Rs. ' . number_format($booking['total_price'], 0);
        $booking['start_date_fmt']   = date('d M Y', strtotime($booking['start_date']));
        $booking['end_date_fmt']     = date('d M Y', strtotime($booking['end_date']));
        $booking['created_at_fmt']   = date('d M Y, h:i A', strtotime($booking['created_at']));
    }

    echo json_encode(['success' => true, 'data' => $bookings, 'total' => count($bookings)]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to fetch bookings.']);
}
