<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* FIX BASE PATH ISSUE */
$basePath = PAGES_URL . "login.php";

if (!isset($_SESSION['user'])) {
    header("Location: $basePath");
    exit;
}
?>