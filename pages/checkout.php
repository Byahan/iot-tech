<?php
session_start();

include '../config/path.php';
include '../config/firebase.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>
        alert('Cart kosong');
        window.location.href='../index.php';
    </script>";
    exit;
}

$cart = $_SESSION['cart'];
$products = firebaseGet('products');

$user = $_SESSION['user'];

$uid = $user['uid'] ?? '';
$name = $user['name'] ?? 'User';
$email = $user['email'] ?? '';

$total = 0;

/* CALCULATE TOTAL */
foreach ($cart as $id => $qty) {

    $item = $products[$id] ?? null;
    if (!$item) continue;

    $total += $item['price'] * $qty;
}

/* CHECKOUT */
if (isset($_POST['checkout'])) {

    $orderItems = [];

    foreach ($cart as $id => $qty) {

        if (!isset($products[$id])) continue;

        $orderItems[] = [
            'id' => $id,
            'name' => $products[$id]['name'],
            'price' => $products[$id]['price'],
            'qty' => $qty,
            'subtotal' => $products[$id]['price'] * $qty
        ];
    }

    $order = [
        'customer_uid' => $uid,
        'customer' => $name,
        'email' => $email,
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'payment' => $_POST['payment'],
        'items' => $orderItems,
        'total' => $total,
        'status' => 'Pending',
        'date' => date('Y-m-d H:i:s')
    ];

    $orderId = firebasePost('orders', $order);

    /* store last order for payment page */
    $_SESSION['last_order'] = $order;

    $_SESSION['cart'] = [];

    header("Location: payment.php");

    echo "<script>
        alert('Order berhasil dibuat');
        window.location.href='../index.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="<?= CSS_URL ?>style.css" rel="stylesheet">
</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">

    <div class="section-title mb-5">
        <h2>Checkout</h2>
        <p>Lengkapi data pengiriman Anda</p>
    </div>

    <div class="row">

        <div class="col-lg-5 mb-4">

            <div class="checkout-card">

                <h4>Detail Pesanan</h4>

                <hr>

                <?php foreach ($cart as $id => $qty): ?>

                    <?php
                        $item = $products[$id] ?? null;
                        if (!$item) continue;

                        $subtotal = $item['price'] * $qty;
                    ?>

                    <div class="d-flex justify-content-between mb-2">
                        <span><?= htmlspecialchars($item['name']) ?> x<?= $qty ?></span>
                        <span>Rp <?= number_format($subtotal) ?></span>
                    </div>

                <?php endforeach; ?>

                <hr>

                <div class="d-flex justify-content-between">
                    <h5>Total</h5>
                    <h5 class="product-price">
                        Rp <?= number_format($total) ?>
                    </h5>
                </div>

            </div>

        </div>

        <div class="col-lg-7">

            <div class="checkout-card">

                <h4>Data Pemesan</h4>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" value="<?= htmlspecialchars($name) ?>" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" value="<?= htmlspecialchars($email) ?>" disabled>
                </div>

                <form method="POST">

                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="address" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Nomor HP</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label>Metode Pembayaran</label>
                        <select name="payment" class="form-select" required>
                            <option value="">Pilih</option>
                            <option>Transfer Bank</option>
                            <option>E-Wallet</option>
                            <option>COD</option>
                        </select>
                    </div>

                    <button class="btn btn-primary w-100" name="checkout">
                        Buat Pesanan
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>