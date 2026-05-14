<?php
session_start();

include '../config/path.php';
include '../config/firebase.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$uid = $user['uid'];

$orders = firebaseGet('orders');

if (!$orders) {
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Orders</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="<?= CSS_URL ?>style.css" rel="stylesheet">
    
    <style>
        /* Styles for orders page */
        .order-filter-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        
        .filter-tab {
            padding: 10px 24px;
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            color: #334155;
            border: none;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .filter-tab:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }
        
        .filter-tab.active {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        
        .order-card {
            background: white;
            border-radius: 24px;
            padding: 25px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
            transition: 0.25s ease;
            margin-bottom: 24px;
        }
        
        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.1);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eef2f7;
        }
        
        .order-info {
            flex: 1;
        }
        
        .order-id {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }
        
        .order-meta {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }
        
        .order-status-section {
            text-align: right;
        }
        
        .status-badge {
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-processing {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .status-shipped {
            background: #cffafe;
            color: #0891b2;
        }
        .status-completed {
            background: #dcfce7;
            color: #166534;
        }
        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .order-total {
            font-weight: 700;
            color: #2563eb;
            font-size: 1.2rem;
            margin-top: 10px;
        }
        
        .order-items {
            margin-top: 15px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f2f5;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item-name {
            font-weight: 500;
            color: #1e293b;
        }
        
        .order-item-qty {
            color: #64748b;
            font-size: 0.85rem;
            margin-left: 8px;
        }
        
        .order-item-price {
            font-weight: 600;
            color: #2563eb;
        }
        
        /* Main empty state - full card */
        .empty-orders {
            background: white;
            border-radius: 30px;
            padding: 80px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        }
        
        .empty-orders h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 15px;
        }
        
        .empty-orders p {
            color: #64748b;
            margin-bottom: 25px;
        }
        
        /* Filter empty state - simple text */
        .filter-empty-text {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 24px;
            margin-top: 20px;
        }
        
        .filter-empty-text p {
            color: #64748b;
            font-size: 1rem;
            margin: 0;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
            }
            
            .order-status-section {
                text-align: left;
                margin-top: 15px;
            }
            
            .filter-tab {
                padding: 8px 16px;
                font-size: 12px;
            }
            
            .filter-empty-text {
                padding: 40px 20px;
            }
        }
    </style>
</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">

    <div class="section-title mb-5">
        <h2>Pesanan Saya</h2>
        <p>Lihat dan manage pesanan Anda</p>
    </div>

    <!-- Filter Tabs -->
    <div class="order-filter-tabs">
        <button class="filter-tab active" data-status="all">Semua Pesanan</button>
        <button class="filter-tab" data-status="pending">Pending</button>
        <button class="filter-tab" data-status="processing">Processing</button>
        <button class="filter-tab" data-status="shipped">Shipped</button>
        <button class="filter-tab" data-status="completed">Completed</button>
        <button class="filter-tab" data-status="cancelled">Cancelled</button>
    </div>

    <div class="orders-container">
        <?php
        $hasOrders = false;

        // Loop through all orders
        foreach ($orders as $key => $order):

            // Check if order belongs to current user
            if (!isset($order['customer_uid']) || $order['customer_uid'] !== $uid) {
                continue;
            }

            $hasOrders = true;

            // Get status (default to 'pending' if not set)
            $status = strtolower($order['status'] ?? 'pending');

            $statusClass = match ($status) {
                'completed' => 'status-completed',
                'processing' => 'status-processing',
                'shipped' => 'status-shipped',
                'cancelled' => 'status-cancelled',
                default => 'status-pending'
            };
            
            // Format date nicely
            $orderDate = $order['date'] ?? '-';
            if ($orderDate !== '-' && strtotime($orderDate)) {
                $orderDate = date('F j, Y', strtotime($orderDate));
            }
        ?>

            <div class="order-card" data-status="<?= $status ?>">
                
                <div class="order-header">
                    <div class="order-info">
                        <div class="order-id">
                            Order #<?= htmlspecialchars(substr($key, 0, 8)) ?>...
                        </div>
                        <div class="order-meta">
                            📅 <?= htmlspecialchars($orderDate) ?>
                        </div>
                        <div class="order-meta">
                            💳 Payment: <?= htmlspecialchars($order['payment'] ?? '-') ?>
                        </div>
                    </div>
                    
                    <div class="order-status-section">
                        <div class="status-badge <?= $statusClass ?>">
                            <?= ucfirst($status) ?>
                        </div>
                        <div class="order-total">
                            Rp <?= number_format($order['total'] ?? 0) ?>
                        </div>
                    </div>
                </div>

                <div class="order-items">
                    <?php if (!empty($order['items'])): ?>
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="order-item">
                                <div>
                                    <span class="order-item-name">
                                        <?= htmlspecialchars($item['name'] ?? '-') ?>
                                    </span>
                                    <?php if (isset($item['qty'])): ?>
                                        <span class="order-item-qty">x<?= $item['qty'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="order-item-price">
                                    Rp <?= number_format($item['subtotal'] ?? $item['price'] ?? 0) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted text-center py-3">Tidak ada item</div>
                    <?php endif; ?>
                </div>

            </div>

        <?php endforeach; ?>
    </div>

    <?php if (!$hasOrders): ?>
        <!-- Full card empty state when no orders at all -->
        <div class="empty-orders" id="mainEmptyState">
            <h3>Belum ada pesanan</h3>
            <p>Mulai belanja untuk melihat pesanan Anda</p>
            <a href="../index.php" class="btn btn-primary">Belanja Sekarang</a>
        </div>
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>

<script>
    // Filter functionality with simple text for empty filter results
    document.addEventListener('DOMContentLoaded', function() {
        const filterTabs = document.querySelectorAll('.filter-tab');
        const orderCards = document.querySelectorAll('.order-card');
        const ordersContainer = document.querySelector('.orders-container');
        const mainEmptyState = document.getElementById('mainEmptyState');
        
        function getStatusDisplayName(status) {
            const statusNames = {
                'pending': 'Pending',
                'processing': 'Processing',
                'shipped': 'Shipped',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            };
            return statusNames[status] || status;
        }
        
        function filterOrders(status) {
            let visibleCount = 0;
            
            // Hide main empty state if it exists
            if (mainEmptyState) {
                mainEmptyState.style.display = 'none';
            }
            
            orderCards.forEach(card => {
                if (status === 'all') {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    const cardStatus = card.getAttribute('data-status');
                    if (cardStatus === status) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
            
            // Remove any existing filter empty message
            const existingFilterMessage = document.querySelector('.filter-empty-text');
            if (existingFilterMessage) {
                existingFilterMessage.remove();
            }
            
            // Show simple text message for filtered empty results
            if (visibleCount === 0 && status !== 'all') {
                const statusName = getStatusDisplayName(status);
                const emptyMessage = document.createElement('div');
                emptyMessage.className = 'filter-empty-text';
                emptyMessage.innerHTML = `
                    <p>📦 Tidak ada pesanan dengan status <strong>${statusName}</strong></p>
                `;
                ordersContainer.appendChild(emptyMessage);
            }
            
            // If no orders at all and showing all, show main empty state
            if (visibleCount === 0 && status === 'all' && mainEmptyState) {
                mainEmptyState.style.display = 'block';
            }
        }
        
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const status = this.getAttribute('data-status');
                filterOrders(status);
            });
        });
        
        // Show all orders by default
        filterOrders('all');
    });
</script>

</body>
</html>