<!-- pages/checkout.php -->

<?php
session_start();
include '../config/firebase.php';

if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

$total = 0;

foreach($cart as $item) {
    $total += $item['price'];
}

if(isset($_POST['checkout'])) {

    $order = [

        'customer' => $_POST['customer'],

        'address' => $_POST['address'],

        'phone' => $_POST['phone'],

        'payment' => $_POST['payment'],

        'items' => $cart,

        'total' => $total,

        'status' => 'Pending',

        'date' => date('Y-m-d H:i:s')

    ];

    firebasePost('orders', $order);

    $_SESSION['cart'] = [];

    echo "
    <script>
        alert('Order berhasil dibuat');
        window.location.href='/iot-tech/index.php';
    </script>
    ";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="../style.css">

</head>

<body>

<?php include '../navbar.php'; ?>

<div class="container py-5">

    <div class="section-title mb-5">

        <h2>
            Checkout
        </h2>

        <p>
            Lengkapi data pesanan Anda
        </p>

    </div>

    <div class="row">

        <!-- ORDER DETAIL -->

        <div class="col-lg-5 mb-4">

            <div class="checkout-card">

                <h4 class="mb-4">
                    Detail Pesanan
                </h4>

                <?php foreach($cart as $item): ?>

                    <div class="checkout-item">

                        <div>

                            <h6>
                                <?php echo $item['name']; ?>
                            </h6>

                        </div>

                        <div>

                            Rp <?php echo number_format($item['price']); ?>

                        </div>

                    </div>

                <?php endforeach; ?>

                <hr>

                <div class="d-flex justify-content-between">

                    <h5>Total</h5>

                    <h5 class="product-price">
                        Rp <?php echo number_format($total); ?>
                    </h5>

                </div>

            </div>

        </div>

        <!-- FORM -->

        <div class="col-lg-7">

            <div class="checkout-card">

                <h4 class="mb-4">
                    Data Customer
                </h4>

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Lengkap
                        </label>

                        <input type="text"
                               name="customer"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Alamat
                        </label>

                        <textarea name="address"
                                  class="form-control"
                                  rows="4"
                                  required></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Nomor HP
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Metode Pembayaran
                        </label>

                        <select name="payment"
                                class="form-select"
                                required>

                            <option value="">
                                Pilih Pembayaran
                            </option>

                            <option value="Transfer Bank">
                                Transfer Bank
                            </option>

                            <option value="E-Wallet">
                                E-Wallet
                            </option>

                            <option value="COD">
                                COD
                            </option>

                        </select>

                    </div>

                    <button type="submit"
                            name="checkout"
                            class="btn btn-primary w-100">

                            Buat Pesanan

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>