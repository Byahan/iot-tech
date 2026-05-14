<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        
        <!-- Brand -->
        <a class="navbar-brand" href="dashboard.php">
            IoT Smart Store <span style="font-size: 0.8rem; opacity: 0.7;">| Admin</span>
        </a>
        
        <!-- Toggler -->
        <button class="navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menu -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-1 <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>" 
                       href="dashboard.php">
                        <i class="bi bi-speedometer2 nav-icon"></i>
                        <span class="d-none d-lg-inline">Dashboard</span>
                    </a>
                </li>
                
                <!-- Products -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-1 <?= $currentPage == 'products.php' ? 'active' : '' ?>" 
                       href="products.php">
                        <i class="bi bi-box nav-icon"></i>
                        <span class="d-none d-lg-inline">Products</span>
                    </a>
                </li>
                
                <!-- View Site Button -->
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-primary" href="<?= BASE_URL ?>" target="_blank">
                        <i class="bi bi-box-arrow-up-right"></i>
                        View Site
                    </a>
                </li>
                
            </ul>
        </div>
        
    </div>
</nav>

<style>
    /* Admin navbar styles - matching user navbar */
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
    .nav-link:focus,
    .nav-link.active {
        color: #60a5fa !important;
    }
    
    .nav-icon {
        font-size: 1.45rem;
    }
    
    /* Button styling matching user navbar */
    .btn-primary {
        background: #1444abff;
        border: none;
        border-radius: 14px;
        padding: 8px 20px;
        font-weight: 600;
        transition: 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary:hover {
        background: #1b3ea0ff;
        transform: translateY(-2px);
    }
    
    /* Mobile responsive */
    @media (max-width: 992px) {
        .navbar-nav {
            margin-top: 15px;
        }
        
        .nav-link {
            margin-left: 0;
            padding: 10px 0;
        }
        
        .btn-primary {
            margin-top: 10px;
            width: 100%;
            justify-content: center;
        }
    }
</style>