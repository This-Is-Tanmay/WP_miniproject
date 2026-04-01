<?php
/**
 * Logout Page
 * Author: College Mini Project
 * Purpose: Destroy session and logout user
 */

session_start();

// Destroy all session data
session_destroy();

// Redirect to login page
header("Location: login.php?logout=success");
exit();
?>
