<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* IMPORTANT: use require_once + correct path safety */
require_once __DIR__ . '/../config/path.php';
require_once __DIR__ . '/../config/firebase.php';

/* CART COUNT */
$cartCount = 0;

if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cartCount = array_sum(array_map('intval', $_SESSION['cart']));
}

$currentPage = basename($_SERVER['PHP_SELF']);
$isAuthPage = in_array($currentPage, ['login.php', 'register.php']);

/* DEFAULT USER NAME */
$firstName = 'User';

/* FETCH USER FROM SESSION + DATABASE */
if (isset($_SESSION['user'])) {

    $uid = $_SESSION['user']['uid'] ?? null;

    if ($uid) {

        $userData = firebaseGet("users/$uid");

        if (!empty($userData['name'])) {
            $firstName = $userData['name'];

            // sync session with latest DB value
            $_SESSION['user']['name'] = $firstName;
        } else {
            $firstName = $_SESSION['user']['name']
                ?? $_SESSION['user']['email']
                ?? 'User';
        }
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
.navbar {
    background: rgba(15, 23, 42, 0.92);
    backdrop-filter: blur(12px);
    padding: 15px 0;
    position: sticky;
    top: 0;
    z-index: 9999;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.navbar-brand {
    font-size: 1.7rem;
    font-weight: 700;
    color: white !important;
}

.nav-link {
    color: #dbe4ff !important;
    margin-left: 14px;
    font-weight: 500;
    transition: 0.25s ease;
    padding: 8px 0;
}

.nav-link:hover,
.nav-link.active {
    color: #60a5fa !important;
}

.nav-icon {
    font-size: 1.45rem;
}

/* CART */
.cart-icon-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.cart-badge {
    position: absolute;
    top: -6px;
    right: -10px;
    min-width: 20px;
    height: 20px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 5px;
}

/* BUTTON */
.btn-primary {
    background: #2563eb;
    border: none;
    border-radius: 14px;
    padding: 8px 20px;
    font-weight: 600;
}

.btn-primary:hover {
    background: #1d4ed8;
}

/* DROPDOWN */
.dropdown-menu-dark {
    background: #111827;
    border: none;
    border-radius: 16px;
    padding: 10px;
}

.dropdown-item {
    color: #e5e7eb;
    border-radius: 10px;
}

.dropdown-item:hover {
    background: #2563eb;
    color: white;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark <?= !$isAuthPage ? 'sticky-top' : '' ?>">
    <div class="container">

        <a class="navbar-brand" href="<?= BASE_URL ?>"
            IoT Smart Store
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>">
                        Home
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Kategori
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="<?= PAGES_URL ?>category.php?category=input">Input</a></li>
                        <li><a class="dropdown-item" href="<?= PAGES_URL ?>category.php?category=Output">Output</a></li>
                        <li><a class="dropdown-item" href="<?= PAGES_URL ?>category.php?category=Other"">Other</a></li>
                    </ul>
                </li>

                <li class="nav-item ms-lg-2">
                    <a class="nav-link d-flex align-items-center gap-1"
                       href="<?= PAGES_URL ?>orders.php">
                        <i class="bi bi-receipt nav-icon"></i>
                        Orders
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="nav-link cart-icon-wrapper"
                       href="<?= PAGES_URL ?>cart.php">

                        <i class="bi bi-cart3 nav-icon"></i>

                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge"><?= $cartCount ?></span>
                        <?php endif; ?>

                    </a>
                </li>

                <?php if (isset($_SESSION['user'])): ?>

                    <li class="nav-item dropdown ms-lg-3">

                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                           href="#" data-bs-toggle="dropdown">

                            <i class="bi bi-person-circle nav-icon"></i>

                            <span class="d-none d-lg-inline">
                                <?= htmlspecialchars($firstName) ?>
                            </span>

                        </a>

                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= PAGES_URL ?>profile.php">My Profile</a></li>
                            <li><a class="dropdown-item text-danger" href="<?= PAGES_URL ?>logout.php">Logout</a></li>
                        </ul>

                    </li>

                <?php else: ?>

                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary" href="<?= PAGES_URL ?>login.php">
                            Login
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>
</nav>