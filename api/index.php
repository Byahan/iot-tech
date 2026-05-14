<?php
session_start();
include __DIR__ . '/../config/path.php';
include __DIR__ . '/../config/firebase.php';

$products = firebaseGet('products');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IoT Smart Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= CSS_URL ?>style.css">

    <!-- Histats -->
    <script type="text/javascript">
        var _Hasync = _Hasync || [];
        _Hasync.push(['Histats.start', 'YOUR_ID_HERE']);
        _Hasync.push(['Histats.fasi', '1']);
        _Hasync.push(['Histats.track_hits', '']);

        (function () {
            var hs = document.createElement('script');
            hs.async = true;
            hs.src = '//s10.histats.com/js15_as.js';
            document.head.appendChild(hs);
        })();
    </script>
</head>

<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container py-5">

<?php
$promoKey = array_key_first($products);
$promoProduct = $products[$promoKey];

$originalPrice = $promoProduct['price'];
$fakePrice = $originalPrice * 1.15;
?>

<!-- PROMO -->
<div class="promo-section mb-5">

    <div class="row align-items-center">

        <div class="col-lg-6">

            <span class="promo-badge">🔥 PROMO SPESIAL</span>

            <h1 class="promo-title mt-3">
                <?= htmlspecialchars($promoProduct['name']) ?>
            </h1>

            <p class="promo-text">
                <?= htmlspecialchars($promoProduct['description']) ?>
            </p>

            <div class="promo-price-wrapper mt-4">

                <span class="old-price">
                    Rp <?= number_format($fakePrice) ?>
                </span>

                <h2 class="promo-price">
                    Rp <?= number_format($originalPrice) ?>
                </h2>

                <span class="discount-badge">15% OFF</span>

            </div>

            <button onclick="addToCart('<?= $promoKey ?>')"
                    class="btn btn-primary btn-lg mt-4">
                Add To Cart
            </button>

        </div>

        <div class="col-lg-6 text-center">

            <img src="<?= IMG_URL . $promoProduct['image'] ?>"
                 class="promo-image">

        </div>

    </div>

</div>

<!-- PRODUCTS -->
<div class="section-title">
    <h2>Produk Terbaru</h2>
    <p>Produk IoT pilihan dengan kualitas terbaik</p>
</div>

<div class="row">

<?php foreach ($products as $key => $product): ?>

    <div class="col-lg-4 col-md-6 mb-4">

        <div class="product-card">

            <div class="product-image-wrapper">
                <span class="product-badge">NEW</span>

                <img src="<?= IMG_URL . $product['image'] ?>">
            </div>

            <div class="product-content">

                <h4><?= htmlspecialchars($product['name']) ?></h4>
                <p><?= htmlspecialchars($product['description']) ?></p>

                <h5 class="product-price mt-3">
                    Rp <?= number_format($product['price']) ?>
                </h5>

                <button onclick="addToCart('<?= $key ?>')"
                        class="btn btn-primary w-100 mt-3">
                    Add To Cart
                </button>

            </div>

        </div>

    </div>

<?php endforeach; ?>

</div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<!-- TOAST -->
<div id="cartToast" class="cart-toast">
    ✅ Product added to cart
</div>

<script>

function addToCart(productId) {

    fetch('/pages/cart.php?id=' + productId, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        showToast();
        updateCartBadge(data.count);
    })
    .catch(console.error);

}

function showToast() {
    const toast = document.getElementById('cartToast');
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 2000);
}

function updateCartBadge(count) {

    const wrapper = document.querySelector('.cart-icon-wrapper');
    if (!wrapper) return;

    let badge = document.querySelector('.cart-badge');

    if (count > 0) {

        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'cart-badge';
            wrapper.appendChild(badge);
        }

        badge.textContent = count;

    } else {

        if (badge) badge.remove();

    }
}

</script>

</body>
</html>