<!-- FULL navbar.php -->

<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$cartCount = isset($_SESSION['cart'])
              ? count($_SESSION['cart'])
              : 0;

$currentPage = basename($_SERVER['PHP_SELF']);

$isAuthPage = (
    $currentPage == 'login.php' ||
    $currentPage == 'register.php'
);
?>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<nav class="navbar navbar-expand-lg navbar-dark <?php echo !$isAuthPage ? 'sticky-top' : ''; ?>">

    <div class="container">

        <!-- LOGO -->

        <a class="navbar-brand"
           href="<?= BASE_URL ?>">

           IoT Smart Store

        </a>

        <!-- MOBILE BUTTON -->

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- MENU -->

        <div class="collapse navbar-collapse"
             id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- HOME -->

                <li class="nav-item">

                    <a class="nav-link"
                       href="<?= BASE_URL ?>">

                       Home

                    </a>

                </li>

                <!-- CATEGORY -->

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown">

                       Kategori

                    </a>

                    <ul class="dropdown-menu dropdown-menu-dark">

                        <li>

                            <a class="dropdown-item"
                               href="<?= BASE_URL ?>">

                               Input

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item"
                               href="<?= BASE_URL ?>">

                               Output

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item"
                               href="<?= BASE_URL ?>">

                               Other

                            </a>

                        </li>

                    </ul>

                </li>

                <!-- CART -->

                <li class="nav-item ms-lg-3">

                    <a class="nav-link cart-icon-wrapper"
                       href="<?= BASE_URL ?>">

                        <i class="bi bi-cart3 nav-icon"></i>

                        <?php if($cartCount > 0): ?>

                            <span class="cart-badge">
                                <?php echo $cartCount; ?>
                            </span>

                        <?php endif; ?>

                    </a>

                </li>

                <!-- PROFILE -->

                <?php if(isset($_SESSION['user'])): ?>

                    <?php
                        $email = $_SESSION['user']['email'];

                        $namePart = explode('@', $email)[0];

                        $firstName = $_SESSION['user']['name'];
                    ?>

                    <li class="nav-item dropdown ms-lg-3">

                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown">

                            <i class="bi bi-person-circle nav-icon"></i>

                            <span class="d-none d-lg-inline">
                                <?php echo htmlspecialchars($firstName); ?>
                            </span>

                        </a>

                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">

                                <a class="dropdown-item"
                                href="<?= BASE_URL ?>">

                                My Profile

                                </a>

                            </li>

                            <li>

                                <a class="dropdown-item text-danger"
                                href="<?= BASE_URL ?>">

                                Logout

                                </a>

                            </li>

                        </ul>

                    </li>

                <?php else: ?>

                    <li class="nav-item ms-lg-3">

                        <a class="btn btn-primary"
                        href="<?= BASE_URL ?>">

                        Login

                        </a>

                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>