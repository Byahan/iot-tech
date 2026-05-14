<?php
include '../config/firebase.php';

$orders = firebaseGet('orders');
?>

<!DOCTYPE html>
<html>
<head>

<title>Orders</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<div class="container mt-5">

<h1 class="mb-4">Data Orders</h1>

<div class="checkout-box">

<table class="table table-bordered">

<tr>
<th>Customer</th>
<th>Phone</th>
<th>Payment</th>
<th>Total</th>
<th>Status</th>
</tr>

<?php foreach($orders as $order): ?>

<tr>
<td><?php echo $order['customer']; ?></td>
<td><?php echo $order['phone']; ?></td>
<td><?php echo $order['payment']; ?></td>
<td>Rp <?php echo number_format($order['total']); ?></td>
<td><?php echo $order['status']; ?></td>
</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</body>
</html>