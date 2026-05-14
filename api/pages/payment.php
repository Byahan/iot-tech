<?php
session_start();
include '../config/path.php';

if (!isset($_SESSION['last_order'])) {
    header("Location: ../index.php");
    exit;
}

$order = $_SESSION['last_order'];

/* fake BCA VA generator */
$vaNumber = '014' . rand(1000000000, 9999999999);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= CSS_URL ?>style.css" rel="stylesheet">
</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">

    <div class="section-title mb-4">
        <h2>Payment Instructions</h2>
        <p>Complete your payment using Virtual Account</p>
    </div>

    <div class="checkout-card text-center">

        <h4>Bank BCA Virtual Account</h4>

        <div class="my-4">
            <h2 class="text-primary"><?= $vaNumber ?></h2>
            <p class="text-muted">Please transfer exactly to this number</p>
        </div>

        <div class="mb-4">
            <h5>Total Payment</h5>
            <h3 class="product-price">
                Rp <?= number_format($order['total']) ?>
            </h3>
        </div>

        <a href="<?= PAGES_URL ?>orders.php" class="btn btn-primary w-100">
            Go to My Orders
        </a>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>