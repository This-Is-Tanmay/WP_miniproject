<?php
/**
 * AJAX: Book a Car
 * POST: car_id, start_date, end_date
 * Requires: login
 * Returns: JSON
 */

header('Content-Type: application/json; charset=UTF-8');

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit();
}

$car_id     = (int)($_POST['car_id']     ?? 0);
$start_date = trim($_POST['start_date']  ?? '');
$end_date   = trim($_POST['end_date']    ?? '');
$user_id    = $_SESSION['user_id'];

// Validation
if (!$car_id || empty($start_date) || empty($end_date)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
}

$start = strtotime($start_date);
$end   = strtotime($end_date);

if ($start === false || $end === false) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format.']);
    exit();
}
if ($start < strtotime('today')) {
    echo json_encode(['success' => false, 'message' => 'Start date cannot be in the past.']);
    exit();
}
if ($end <= $start) {
    echo json_encode(['success' => false, 'message' => 'End date must be after start date.']);
    exit();
}

$total_days = (int)(($end - $start) / 86400);
if ($total_days < 1) { $total_days = 1; }

try {
    // Get car details + check availability
    $stmt = $pdo->prepare("SELECT id, name, brand, price_per_day, available FROM cars WHERE id = ? LIMIT 1");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();

    if (!$car) {
        echo json_encode(['success' => false, 'message' => 'Car not found.']);
        exit();
    }
    if (!$car['available']) {
        echo json_encode(['success' => false, 'message' => 'This car is currently not available.']);
        exit();
    }

    // Check for overlapping bookings
    $overlap = $pdo->prepare(
        "SELECT id FROM bookings
         WHERE car_id = ? AND status NOT IN ('cancelled')
           AND NOT (end_date <= ? OR start_date >= ?)
         LIMIT 1"
    );
    $overlap->execute([$car_id, $start_date, $end_date]);
    if ($overlap->fetch()) {
        echo json_encode(['success' => false, 'message' => 'This car is already booked for the selected dates. Please choose different dates.']);
        exit();
    }

    $total_price = $car['price_per_day'] * $total_days;

    // Create booking
    $stmt = $pdo->prepare(
        "INSERT INTO bookings (user_id, car_id, start_date, end_date, total_days, total_price, status)
         VALUES (?, ?, ?, ?, ?, ?, 'confirmed')"
    );
    $stmt->execute([$user_id, $car_id, $start_date, $end_date, $total_days, $total_price]);

    $booking_id = $pdo->lastInsertId();

    echo json_encode([
        'success'     => true,
        'message'     => "Booking confirmed! {$car['brand']} {$car['name']} booked for {$total_days} day(s).",
        'booking_id'  => $booking_id,
        'total_price' => 'Rs. ' . number_format($total_price, 0),
        'total_days'  => $total_days
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Booking failed. Please try again.']);
}
