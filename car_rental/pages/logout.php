<?php
require_once '../includes/auth.php';
destroySession();
header('Location: /car_rental/');
exit();
