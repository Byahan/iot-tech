<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* FIX BASE PATH ISSUE */
$basePath = "/iot-tech/pages/login.php";

if (!isset($_SESSION['user'])) {
    header("Location: $basePath");
    exit;
}
?>