<?php
session_start();
include '../config/path.php';
include '../config/firebase.php';
include '../config/admin_guard.php';

// Handle delete product
if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];
    firebaseSet("products/$productId", null);
    header("Location: products.php?deleted=1");
    exit;
}

$products = firebaseGet('products') ?? [];
$deleted = isset($_GET['deleted']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Manage Products - Admin Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="<?= CSS_URL ?>style.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h2 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: #64748b;
            margin: 0;
        }
        
        .products-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
        }
        
        .btn-add {
            background: #2563eb;
            border: none;
            border-radius: 14px;
            padding: 10px 24px;
            font-weight: 600;
            transition: 0.25s ease;
            color: white;
        }
        
        .btn-add:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-edit {
            background: #10b981;
            border: none;
            border-radius: 10px;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: 0.25s ease;
            color: white;
        }
        
        .btn-edit:hover {
            background: #059669;
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-delete {
            background: #ef4444;
            border: none;
            border-radius: 10px;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: 0.25s ease;
            color: white;
        }
        
        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-2px);
            color: white;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
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
        
        .category-badge {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
        
        .category-input {
            background: #dbeafe;
            color: #1d4ed8;
        }
        
        .category-output {
            background: #dcfce7;
            color: #166534;
        }
        
        .category-other {
            background: #fef3c7;
            color: #92400e;
        }
        
        @media (max-width: 768px) {
            .products-card {
                padding: 20px;
                overflow-x: auto;
            }
            
            .table {
                min-width: 700px;
            }
        }
    </style>
</head>

<body>

<?php include '../includes/admin_navbar.php'; ?>

<div class="container py-5">
    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> Produk berhasil ditambahkan
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> Produk berhasil diperbarui
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-box"></i> Kelola Produk</h2>
            <p>Lihat, edit, dan hapus produk yang tersedia</p>
        </div>
        <a href="add_product.php" class="btn btn-primary btn-add">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </a>
    </div>
    
    <?php if ($deleted): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> Produk berhasil dihapus
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="products-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div style="color: #64748b;">
                                    <p style="margin-bottom: 5px;">📦 Belum ada produk</p>
                                    <small>Klik "Tambah Produk" untuk menambahkan produk pertama</small>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $id => $product): ?>
                            <tr>
                                <td>
                                    <img src="/iot-tech/assets/images/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" 
                                         class="product-image" 
                                         alt="<?= htmlspecialchars($product['name'] ?? '-') ?>">
                                </td>
                                <td><code><?= htmlspecialchars($id) ?></code></td>
                                <td>
                                    <strong><?= htmlspecialchars($product['name'] ?? '-') ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars(substr($product['description'] ?? '', 0, 50)) ?>...</small>
                                </td>
                                <td>
                                    <?php 
                                    $category = $product['category'] ?? 'other';
                                    $categoryClass = match($category) {
                                        'input' => 'category-input',
                                        'output' => 'category-output',
                                        default => 'category-other'
                                    };
                                    ?>
                                    <span class="category-badge <?= $categoryClass ?>">
                                        <?= ucfirst($category) ?>
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: #2563eb;">
                                        Rp <?= number_format($product['price'] ?? 0) ?>
                                    </strong>
                                </td>
                                <td><?= $product['stock'] ?? 0 ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="edit_product.php?id=<?= $id ?>" class="btn btn-edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="products.php?delete=<?= $id ?>" 
                                           class="btn btn-delete"
                                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
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