<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* MUST be logged in */
if (!isset($_SESSION['user'])) {
    header("Location: " . PAGES_URL . "login.php");
    exit;
}

/* SAFE ROLE CHECK */
$role = $_SESSION['user']['role'] ?? 'user';

if ($role !== 'admin') {
    header("Location: " . BASE_URL);
    exit;
}