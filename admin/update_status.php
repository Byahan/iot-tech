<?php
include '../config/firebase.php';
include '../config/admin_guard.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $orderId = $_POST['order_id'];
    $status = $_POST['status'];

    // Get current order data to preserve other fields
    $currentOrder = firebaseGet("orders/$orderId");
    
    if ($currentOrder) {
        // Update only the status and updated_at fields
        $updatedOrder = array_merge($currentOrder, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        firebaseSet("orders/$orderId", $updatedOrder);
    }

    // Redirect back to dashboard with success message (optional)
    header("Location: dashboard.php?success=Status updated successfully");
    exit;
}

// If not POST request, redirect to dashboard
header("Location: dashboard.php");
exit;
?>