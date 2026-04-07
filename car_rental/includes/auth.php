<?php
/**
 * Authentication & Session Helpers
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Check if any user is logged in */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/** Check if logged-in user is admin */
function isAdmin(): bool {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/** Redirect to login if not authenticated */
function requireLogin(): void {
    if (!isLoggedIn()) {
        if (isAjax()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Please login to continue.', 'redirect' => '/car_rental/pages/login.php']);
            exit();
        }
        header('Location: /car_rental/pages/login.php');
        exit();
    }
}

/** Redirect to dashboard if not admin */
function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        if (isAjax()) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Admin access required.']);
            exit();
        }
        header('Location: /car_rental/pages/dashboard.php');
        exit();
    }
}

/** Check if request is AJAX */
function isAjax(): bool {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/** Set session after successful login */
function setUserSession(array $user): void {
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role']  = $user['role'];
    session_regenerate_id(true);
}

/** Destroy session on logout */
function destroySession(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']
        );
    }
    session_destroy();
}

/** Sanitize input */
function sanitize(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/** Get base URL */
function baseUrl(string $path = ''): string {
    return '/car_rental/' . ltrim($path, '/');
}
