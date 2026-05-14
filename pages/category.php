<!-- category.php -->

<?php
session_start();
include '../config/firebase.php';

$category = $_GET['category'] ?? 'input';

$products = firebaseGet('products');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Category - <?php echo ucfirst($category); ?>
    </title>

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

    <!-- HEADER -->

    <div class="category-header mb-5">

        <h1>
            <?php echo strtoupper($category); ?>
        </h1>

        <p>
            Temukan berbagai produk kategori <?php echo $category; ?>
        </p>

    </div>

    <div class="row">

        <!-- SIDEBAR -->

        <div class="col-lg-3 mb-4">

            <div class="category-sidebar">

                <h4 class="mb-4">
                    Kategori
                </h4>

                <a href="category.php?category=input"
                   class="category-link <?php echo $category == 'input' ? 'active-category' : ''; ?>">

                   🔌 Input

                </a>

                <a href="category.php?category=output"
                   class="category-link <?php echo $category == 'output' ? 'active-category' : ''; ?>">

                   💡 Output

                </a>

                <a href="category.php?category=other"
                   class="category-link <?php echo $category == 'other' ? 'active-category' : ''; ?>">

                   ⚙️ Other

                </a>

            </div>

        </div>

        <!-- PRODUCTS -->

        <div class="col-lg-9">

            <div class="row">

                <?php foreach($products as $key => $product): ?>

                    <?php if($product['category'] == $category): ?>

                        <div class="col-md-6 col-lg-4 mb-4">

                            <div class="product-card">

                                <div class="product-image-wrapper">

                                    <span class="product-badge">
                                        NEW
                                    </span>

                                    <img src="../assets/images/<?php echo $product['image']; ?>">

                                </div>

                                <div class="product-content">

                                    <h4>
                                        <?php echo $product['name']; ?>
                                    </h4>

                                    <p>
                                        <?php echo $product['description']; ?>
                                    </p>

                                    <h5 class="product-price">
                                        Rp <?php echo number_format($product['price']); ?>
                                    </h5>

                                    <a href="cart.php?id=<?php echo $key; ?>"
                                       class="btn btn-primary w-100 mt-3">

                                       Add To Cart

                                    </a>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</div>

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>