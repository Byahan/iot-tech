<?php
session_start();

include '../config/path.php';
include '../config/firebase.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$products = firebaseGet('products');

/* ADD +1 */
if (isset($_GET['id'])) {

    $id = (string) $_GET['id'];

    if (isset($products[$id])) {

        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 1;
        } else {
            $_SESSION['cart'][$id]++;
        }
    }

    // AJAX
    if (
        isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'count' => array_sum($_SESSION['cart'])
        ]);
        exit;
    }

    header("Location: cart.php");
    exit;
}

/* REMOVE -1 */
if (isset($_GET['remove'])) {

    $id = (string) $_GET['remove'];

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]--;

        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }

    header("Location: cart.php");
    exit;
}

/* DELETE ALL OF ITEM */
if (isset($_GET['delete'])) {

    $id = (string) $_GET['delete'];

    unset($_SESSION['cart'][$id]);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="<?= CSS_URL ?>style.css" rel="stylesheet">
    
    <style>
        /* Additional styles for cart page consistency */
        .cart-card {
            background: white;
            border-radius: 24px;
            padding: 25px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
            transition: 0.25s ease;
            margin-bottom: 24px;
        }
        
        .cart-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.1);
        }
        
        .cart-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 18px;
        }
        
        .product-price {
            color: #2563eb;
            font-size: 1.4rem;
            font-weight: 700;
        }
        
        .summary-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
            position: sticky;
            top: 100px;
        }
        
        .summary-card h4 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
        }
        
        .empty-cart {
            background: white;
            border-radius: 30px;
            padding: 80px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        }
        
        .empty-cart h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 15px;
        }
        
        .empty-cart p {
            color: #64748b;
            margin-bottom: 25px;
        }
        
        .btn-outline-secondary {
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 500;
            transition: 0.25s ease;
        }
        
        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }
        
        .btn-danger {
            border-radius: 12px;
            padding: 8px 20px;
            font-weight: 500;
            transition: 0.25s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
        }
        
        .section-title {
            margin-bottom: 40px;
        }
        
        .section-title h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
        }
        
        .section-title p {
            color: #64748b;
            margin-top: 8px;
        }
        
        hr {
            background-color: #eef2f7;
            margin: 20px 0;
        }
        
        @media (max-width: 768px) {
            .cart-card {
                padding: 20px;
            }
            
            .cart-image {
                height: 100px;
                margin-bottom: 15px;
            }
            
            .product-price {
                font-size: 1.2rem;
                margin-top: 10px;
            }
            
            .summary-card {
                margin-top: 20px;
                position: relative;
                top: 0;
            }
            
            .empty-cart h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">

    <div class="section-title mb-5">
        <h2>Shopping Cart</h2>
        <p>Review produk yang akan Anda checkout</p>
    </div>

    <?php if (!empty($cart)): ?>

        <div class="row">

            <div class="col-lg-8">

                <?php foreach ($cart as $id => $qty): ?>

                    <?php
                        $item = $products[$id] ?? null;
                        if (!$item) continue;

                        $subtotal = $item['price'] * $qty;
                        $total += $subtotal;
                    ?>

                    <div class="cart-card mb-4">

                        <div class="row align-items-center">

                            <div class="col-md-3">
                                <img src="../assets/images/<?= htmlspecialchars($item['image']) ?>"
                                     class="cart-image"
                                     alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>

                            <div class="col-md-6">

                                <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 10px;">
                                    <?= htmlspecialchars($item['name']) ?>
                                </h4>

                                <p style="color: #64748b; margin-bottom: 15px;">
                                    <?= htmlspecialchars($item['description']) ?>
                                </p>

                                <!-- QUANTITY -->
                                <div class="d-flex align-items-center gap-2 mt-2">

                                    <a href="cart.php?remove=<?= $id ?>"
                                       class="btn btn-sm btn-outline-secondary"
                                       style="border-radius: 10px; padding: 6px 14px;">-</a>

                                    <span class="px-2" style="font-weight: 500; color: #1e293b;"><?= $qty ?></span>

                                    <a href="cart.php?id=<?= $id ?>"
                                       class="btn btn-sm btn-outline-secondary"
                                       style="border-radius: 10px; padding: 6px 14px;">+</a>

                                </div>

                            </div>

                            <div class="col-md-3 text-md-end">

                                <h5 class="product-price" style="color: #2563eb; font-size: 1.4rem; font-weight: 700;">
                                    Rp <?= number_format($subtotal) ?>
                                </h5>

                                <a href="cart.php?delete=<?= $id ?>"
                                   class="btn btn-danger btn-sm mt-3"
                                   style="border-radius: 10px; padding: 8px 20px; font-weight: 500;">
                                    Remove
                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <div class="col-lg-4">

                <div class="summary-card">

                    <h4 style="font-weight: 700; color: #0f172a;">Order Summary</h4>

                    <hr style="background-color: #eef2f7; margin: 20px 0;">

                    <div class="d-flex justify-content-between mb-3">
                        <span style="color: #64748b;">Total Items</span>
                        <strong style="color: #1e293b;"><?= array_sum($cart) ?></strong>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <span style="color: #64748b;">Total Harga</span>
                        <strong class="product-price" style="color: #2563eb; font-size: 1.4rem; font-weight: 700;">
                            Rp <?= number_format($total) ?>
                        </strong>
                    </div>

                    <a href="checkout.php" class="btn btn-primary w-100" style="border-radius: 14px; padding: 12px; font-weight: 600;">
                        Proceed To Checkout
                    </a>

                </div>

            </div>

        </div>

    <?php else: ?>

        <div class="empty-cart text-center">
            <h3>Keranjang masih kosong</h3>
            <p>Tambahkan produk terlebih dahulu</p>

            <a href="../index.php" class="btn btn-primary mt-3" style="border-radius: 14px; padding: 12px 30px; font-weight: 600;">
                Belanja Sekarang
            </a>
        </div>

    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>