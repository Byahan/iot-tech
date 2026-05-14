<?php
session_start();
include '../config/path.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cara Pembelian</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Your CSS (FIXED PATH SYSTEM) -->
    <link rel="stylesheet" href="<?= CSS_URL ?>style.css">

</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">

    <!-- HEADER -->
    <div class="guide-header mb-5 text-center">

        <span class="guide-badge">
            🛒 Panduan Belanja
        </span>

        <h1 class="guide-title mt-3">
            Cara Pembelian Produk
        </h1>

        <p class="guide-text">
            Ikuti langkah-langkah berikut untuk melakukan pembelian produk di IoT Smart Store.
        </p>

    </div>

    <!-- STEPS -->
    <div class="row g-4">

        <?php
        $steps = [
            [
                "icon" => "bi-search",
                "title" => "Pilih Produk",
                "text"  => "Cari dan pilih produk IoT yang ingin dibeli sesuai kebutuhan Anda."
            ],
            [
                "icon" => "bi-cart-plus",
                "title" => "Add To Cart",
                "text"  => "Tambahkan produk ke keranjang belanja dengan satu klik."
            ],
            [
                "icon" => "bi-cart3",
                "title" => "Buka Cart",
                "text"  => "Lihat semua produk yang sudah Anda pilih di halaman cart."
            ],
            [
                "icon" => "bi-credit-card",
                "title" => "Checkout",
                "text"  => "Lanjutkan ke proses checkout untuk membuat pesanan."
            ],
            [
                "icon" => "bi-person-lines-fill",
                "title" => "Isi Data",
                "text"  => "Lengkapi data pengiriman seperti alamat dan nomor HP."
            ],
            [
                "icon" => "bi-check-circle",
                "title" => "Pesanan Diproses",
                "text"  => "Admin akan memproses pesanan Anda setelah checkout."
            ]
        ];
        ?>

        <?php foreach ($steps as $step): ?>

            <div class="col-lg-4 col-md-6">

                <div class="guide-card h-100">

                    <div class="guide-icon">
                        <i class="bi <?= $step['icon'] ?>"></i>
                    </div>

                    <h4><?= $step['title'] ?></h4>

                    <p><?= $step['text'] ?></p>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

</body>

</html>