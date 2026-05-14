<?php
include '../config/firebase.php';

$orders = firebaseGet('orders');
$products = firebaseGet('products');

$totalOrders = count($orders);
$totalProducts = count($products);
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<div class="container mt-5">

<h1 class="mb-5">Dashboard Admin</h1>

<div class="row">

<div class="col-md-6 mb-4">
<div class="dashboard-card">
<h3>Total Orders</h3>
<h1><?php echo $totalOrders; ?></h1>
</div>
</div>

<div class="col-md-6 mb-4">
<div class="dashboard-card">
<h3>Total Products</h3>
<h1><?php echo $totalProducts; ?></h1>
</div>
</div>

</div>

<a href="orders.php" class="btn btn-dark">
Lihat Semua Orders
</a>

</div>

</body>
</html>