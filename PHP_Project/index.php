<?php
/**
 * Index/Home Page
 * Author: College Mini Project
 * Purpose: Redirect to login or dashboard based on session
 */

session_start();

if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to dashboard
    header("Location: dashboard.php");
} else {
    // If not logged in, redirect to login
    header("Location: login.php");
}
exit();
?>
