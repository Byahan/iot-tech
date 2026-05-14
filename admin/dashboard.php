<?php
include '../config/path.php';
include '../config/firebase.php';
include '../config/admin_guard.php';

$orders = firebaseGet('orders') ?? [];

/* STATS */
$totalOrders = count($orders);
$totalRevenue = 0;

$statusCount = [
    'pending' => 0,
    'processing' => 0,
    'shipped' => 0,
    'completed' => 0,
    'cancelled' => 0
];

foreach ($orders as $o) {

    $status = strtolower($o['status'] ?? 'pending');
    $statusCount[$status] = ($statusCount[$status] ?? 0) + 1;

    $totalRevenue += $o['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="<?= CSS_URL ?>style.css" rel="stylesheet">
    
    <style>
        /* Consistent styling with orders page */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }
        
        .stats-card {
            background: white;
            border-radius: 24px;
            padding: 25px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
            transition: 0.25s ease;
            height: 100%;
        }
        
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card h5 {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-card h2 {
            color: #0f172a;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .stats-card .stats-icon {
            font-size: 2rem;
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        .orders-table-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
        }
        
        .orders-table-card h4 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 25px;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px;
            border-bottom: 2px solid #eef2f7;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f0f2f5;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
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
        
        .form-select-sm {
            border-radius: 12px;
            border: 1px solid #dbe4ff;
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        
        .form-select-sm:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.2);
        }
        
        .btn-primary-sm {
            background: #2563eb;
            border: none;
            border-radius: 12px;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: 0.25s ease;
        }
        
        .btn-primary-sm:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }
        
        .admin-header {
            margin-bottom: 30px;
        }
        
        .admin-header h2 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }
        
        .admin-header p {
            color: #64748b;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 15px;
            }
            
            .orders-table-card {
                padding: 20px;
                overflow-x: auto;
            }
            
            .table {
                min-width: 600px;
            }
        }
    </style>
</head>

<body>

<?php include '../includes/admin_navbar.php'; ?>

<div class="container py-5">

    <div class="admin-header">
        <h2>Admin Dashboard</h2>
        <p>Manage and track all customer orders</p>
    </div>

    <!-- STATS CARDS -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="stats-card">
                <div class="stats-icon">📦</div>
                <h5>Total Orders</h5>
                <h2><?= $totalOrders ?></h2>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stats-card">
                <div class="stats-icon">💰</div>
                <h5>Total Revenue</h5>
                <h2>Rp <?= number_format($totalRevenue) ?></h2>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stats-card">
                <div class="stats-icon">⏳</div>
                <h5>Pending Orders</h5>
                <h2><?= $statusCount['pending'] ?></h2>
            </div>
        </div>

    </div>

    <!-- ORDERS TABLE -->
    <div class="orders-table-card">

        <h4>📋 Manage Orders</h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div style="color: #64748b;">
                                    <p style="margin-bottom: 5px;">📭 Belum ada pesanan</p>
                                    <small>Tunggu pelanggan melakukan checkout</small>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $id => $o): ?>
                            <tr>
                                <td>
                                    <strong style="color: #0f172a;">#<?= htmlspecialchars(substr($id, 0, 8)) ?>...</strong>
                                    <br>
                                    <small style="color: #64748b; font-size: 0.7rem;"><?= htmlspecialchars($id) ?></small>
                                </td>
                                
                                <td><?= htmlspecialchars($o['customer'] ?? '-') ?></td>
                                
                                <td>
                                    <strong style="color: #2563eb;">
                                        Rp <?= number_format($o['total'] ?? 0) ?>
                                    </strong>
                                </td>
                                
                                <td>
                                    <?php 
                                    $status = strtolower($o['status'] ?? 'pending');
                                    $statusClass = match ($status) {
                                        'completed' => 'status-completed',
                                        'processing' => 'status-processing',
                                        'shipped' => 'status-shipped',
                                        'cancelled' => 'status-cancelled',
                                        default => 'status-pending'
                                    };
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <form method="POST" action="update_status.php" class="d-flex gap-2">
                                        <input type="hidden" name="order_id" value="<?= $id ?>">
                                        
                                        <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                            <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="processing" <?= $status == 'processing' ? 'selected' : '' ?>>Processing</option>
                                            <option value="shipped" <?= $status == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                            <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="cancelled" <?= $status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        
                                        <button class="btn btn-primary-sm" style="color: #fff;">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>

            </table>
        </div>

    </div>

</div>

<?php include '../includes/admin_footer.php'; ?>

</body>
</html>