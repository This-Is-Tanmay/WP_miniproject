<?php
/**
 * AJAX: Admin Car CRUD
 * POST action=add|edit|delete|toggle
 * GET  action=list|all_bookings
 * Requires: admin
 */

header('Content-Type: application/json; charset=UTF-8');

require_once '../includes/db.php';
require_once '../includes/auth.php';

requireAdmin();

$action = trim($_REQUEST['action'] ?? '');

try {
    switch ($action) {

        // ----- LIST ALL CARS -----
        case 'list':
            $stmt = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC");
            $cars = $stmt->fetchAll();
            foreach ($cars as &$c) {
                $c['image_url'] = '/car_rental/assets/images/cars/' . $c['image'];
            }
            echo json_encode(['success' => true, 'data' => $cars]);
            break;

        // ----- ADD CAR -----
        case 'add':
            $name         = trim($_POST['name']         ?? '');
            $brand        = trim($_POST['brand']        ?? '');
            $type         = trim($_POST['type']         ?? '');
            $seats        = (int)($_POST['seats']       ?? 5);
            $price        = (float)($_POST['price']     ?? 0);
            $description  = trim($_POST['description']  ?? '');
            $fuel         = trim($_POST['fuel_type']    ?? 'Petrol');
            $transmission = trim($_POST['transmission'] ?? 'Automatic');
            $image        = 'placeholder.png';

            if (empty($name) || empty($brand) || empty($type) || $price <= 0) {
                echo json_encode(['success' => false, 'message' => 'Name, brand, type, and price are required.']);
                break;
            }

            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $ext   = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($ext, $allowed) && $_FILES['image']['error'] === 0) {
                    $image    = 'car_' . time() . '.' . $ext;
                    $dest     = $_SERVER['DOCUMENT_ROOT'] . '/car_rental/assets/images/cars/' . $image;
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                        $image = 'placeholder.png';
                    }
                }
            }

            $stmt = $pdo->prepare(
                "INSERT INTO cars (name, brand, type, seats, price_per_day, image, description, fuel_type, transmission)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$name, $brand, $type, $seats, $price, $image, $description, $fuel, $transmission]);

            echo json_encode(['success' => true, 'message' => "Car '{$brand} {$name}' added successfully!", 'id' => $pdo->lastInsertId()]);
            break;

        // ----- EDIT CAR -----
        case 'edit':
            $id           = (int)($_POST['id']          ?? 0);
            $name         = trim($_POST['name']         ?? '');
            $brand        = trim($_POST['brand']        ?? '');
            $type         = trim($_POST['type']         ?? '');
            $seats        = (int)($_POST['seats']       ?? 5);
            $price        = (float)($_POST['price']     ?? 0);
            $description  = trim($_POST['description']  ?? '');
            $fuel         = trim($_POST['fuel_type']    ?? 'Petrol');
            $transmission = trim($_POST['transmission'] ?? 'Automatic');
            $available    = (int)($_POST['available']   ?? 1);

            if (!$id || empty($name) || empty($brand)) {
                echo json_encode(['success' => false, 'message' => 'Invalid data.']);
                break;
            }

            // Get current image
            $cur = $pdo->prepare("SELECT image FROM cars WHERE id = ?");
            $cur->execute([$id]);
            $row   = $cur->fetch();
            $image = $row['image'] ?? 'placeholder.png';

            // Handle new image
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
                $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($ext, $allowed)) {
                    $newImg = 'car_' . time() . '.' . $ext;
                    $dest   = $_SERVER['DOCUMENT_ROOT'] . '/car_rental/assets/images/cars/' . $newImg;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                        $image = $newImg;
                    }
                }
            }

            $stmt = $pdo->prepare(
                "UPDATE cars SET name=?, brand=?, type=?, seats=?, price_per_day=?, image=?,
                 description=?, fuel_type=?, transmission=?, available=? WHERE id=?"
            );
            $stmt->execute([$name, $brand, $type, $seats, $price, $image, $description, $fuel, $transmission, $available, $id]);

            echo json_encode(['success' => true, 'message' => 'Car updated successfully!']);
            break;

        // ----- DELETE CAR -----
        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }

            $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true, 'message' => 'Car deleted successfully.']);
            break;

        // ----- TOGGLE AVAILABILITY -----
        case 'toggle':
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) { echo json_encode(['success' => false, 'message' => 'Invalid ID.']); break; }

            $stmt = $pdo->prepare("UPDATE cars SET available = NOT available WHERE id = ?");
            $stmt->execute([$id]);

            $stmt2 = $pdo->prepare("SELECT available FROM cars WHERE id = ?");
            $stmt2->execute([$id]);
            $row = $stmt2->fetch();

            echo json_encode([
                'success'   => true,
                'available' => (bool)$row['available'],
                'message'   => $row['available'] ? 'Car marked as available.' : 'Car marked as unavailable.'
            ]);
            break;

        // ----- ALL BOOKINGS (admin) -----
        case 'all_bookings':
            $stmt = $pdo->query(
                "SELECT b.*, u.full_name AS user_name, u.email AS user_email,
                        c.name AS car_name, c.brand AS car_brand, c.type AS car_type
                 FROM bookings b
                 JOIN users u ON b.user_id = u.id
                 JOIN cars  c ON b.car_id  = c.id
                 ORDER BY b.created_at DESC"
            );
            $bookings = $stmt->fetchAll();
            foreach ($bookings as &$b) {
                $b['total_price_fmt'] = 'Rs. ' . number_format($b['total_price'], 0);
                $b['start_fmt']       = date('d M Y', strtotime($b['start_date']));
                $b['end_fmt']         = date('d M Y', strtotime($b['end_date']));
                $b['created_fmt']     = date('d M Y', strtotime($b['created_at']));
            }
            echo json_encode(['success' => true, 'data' => $bookings]);
            break;

        // ----- STATS -----
        case 'stats':
            $stats = [
                'total_cars'     => $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn(),
                'available_cars' => $pdo->query("SELECT COUNT(*) FROM cars WHERE available=1")->fetchColumn(),
                'total_bookings' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
                'total_users'    => $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn(),
                'total_revenue'  => $pdo->query("SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE status='confirmed'")->fetchColumn(),
            ];
            $stats['total_revenue_fmt'] = 'Rs. ' . number_format($stats['total_revenue'], 0);
            echo json_encode(['success' => true, 'data' => $stats]);
            break;

        // ----- UPDATE BOOKING STATUS -----
        case 'update_booking':
            $id     = (int)($_POST['id']     ?? 0);
            $status = trim($_POST['status'] ?? '');
            $allowed = ['pending','confirmed','cancelled','completed'];
            if (!$id || !in_array($status, $allowed)) {
                echo json_encode(['success' => false, 'message' => 'Invalid data.']);
                break;
            }
            $stmt = $pdo->prepare("UPDATE bookings SET status=? WHERE id=?");
            $stmt->execute([$status, $id]);
            echo json_encode(['success' => true, 'message' => 'Booking status updated.']);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Unknown action.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
