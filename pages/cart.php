<?php
session_start();
include '../config/firebase.php';

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

/* ADD PRODUCT */

if(isset($_GET['id'])){

    $products = firebaseGet('products');

    $id = (string) $_GET['id'];

    if(isset($products[$id])){

        $_SESSION['cart'][] = $products[$id];

    }

    /* AJAX REQUEST */

    if(
        isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
    ){

        echo json_encode([
            'success' => true,
            'count' => count($_SESSION['cart'])
        ]);

        exit;
    }

    /* NORMAL REQUEST */

    header("Location: cart.php");
    exit;
}

/* REMOVE PRODUCT */

if(isset($_GET['remove'])){

    $removeIndex = (int) $_GET['remove'];

    if(isset($_SESSION['cart'][$removeIndex])){

        unset($_SESSION['cart'][$removeIndex]);

        $_SESSION['cart'] = array_values($_SESSION['cart']);

    }

    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart</title>

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
            Shopping Cart
        </h2>

        <p>
            Review produk yang akan Anda checkout
        </p>

    </div>

    <?php if (count($cart) > 0): ?>

        <div class="row">

            <div class="col-lg-8">

                <?php foreach ($cart as $index => $item): ?>

                    <?php $total += $item['price']; ?>

                    <div class="cart-card mb-4">

                        <div class="row align-items-center">

                            <div class="col-md-3 mb-3 mb-md-0">

                                <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>"
                                     alt="<?php echo htmlspecialchars($item['name']); ?>"
                                     class="cart-image">

                            </div>

                            <div class="col-md-6">

                                <h4>
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </h4>

                                <p>
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </p>

                            </div>

                            <div class="col-md-3 text-md-end">

                                <h5 class="product-price">
                                    Rp <?php echo number_format($item['price']); ?>
                                </h5>

                                <a href="cart.php?remove=<?php echo $index; ?>"
                                   class="btn btn-danger btn-sm mt-3">

                                   Remove

                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <div class="col-lg-4">

                <div class="summary-card">

                    <h4>
                        Order Summary
                    </h4>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">

                        <span>Total Produk</span>

                        <strong>
                            <?php echo count($cart); ?>
                        </strong>

                    </div>

                    <div class="d-flex justify-content-between mb-4">

                        <span>Total Harga</span>

                        <strong class="product-price">
                            Rp <?php echo number_format($total); ?>
                        </strong>

                    </div>

                    <a href="checkout.php"
                       class="btn btn-primary w-100">

                       Proceed To Checkout

                    </a>

                </div>

            </div>

        </div>

    <?php else: ?>

        <div class="empty-cart">

            <h3>
                Keranjang masih kosong
            </h3>

            <p>
                Tambahkan produk terlebih dahulu
            </p>

            <a href="/iot-tech/index.php"
               class="btn btn-primary mt-3">

               Belanja Sekarang

            </a>

        </div>

    <?php endif; ?>

</div>

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>