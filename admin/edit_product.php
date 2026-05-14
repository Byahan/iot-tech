<?php
session_start();
include '../config/path.php';
include '../config/firebase.php';
include '../config/admin_guard.php';

$productId = $_GET['id'] ?? '';
$product = firebaseGet("products/$productId");

if (!$product) {
    header("Location: products.php");
    exit;
}

$success = "";
$error = "";

// Handle product update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (int)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category = $_POST['category'];
    
    // Prepare update data
    $updateData = [
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'stock' => $stock,
        'category' => $category,
        'image' => $product['image'],
        'created_at' => $product['created_at'] ?? date('Y-m-d'),
        'updated_at' => date('Y-m-d')
    ];
    
    // Handle image upload if new image provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($fileext, $allowed)) {
            $newfilename = time() . '_' . uniqid() . '.' . $fileext;
            $uploadpath = $_SERVER['DOCUMENT_ROOT'] . '/iot-tech/assets/images/' . $newfilename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadpath)) {
                // Delete old image if exists
                if ($product['image'] && file_exists($_SERVER['DOCUMENT_ROOT'] . '/iot-tech/assets/images/' . $product['image'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/iot-tech/assets/images/' . $product['image']);
                }
                $updateData['image'] = $newfilename;
            } else {
                $error = "Gagal mengupload gambar";
            }
        } else {
            $error = "Format gambar tidak valid. Gunakan JPG, PNG, atau GIF";
        }
    }
    
    if (empty($error)) {
        $result = firebaseSet("products/$productId", $updateData);
        
        if ($result) {
            header("Location: products.php?updated=1");
            exit;
        } else {
            $error = "Gagal memperbarui produk";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Edit Produk - Admin Panel</title>
    
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
        
        .form-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
        }
        
        .form-label {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border-radius: 14px;
            padding: 12px;
            border: 1px solid #dbe4ff;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.2);
        }
        
        .btn-submit {
            background: #10b981;
            border: none;
            border-radius: 14px;
            padding: 12px 30px;
            font-weight: 600;
            transition: 0.25s ease;
            color: white;
        }
        
        .btn-submit:hover {
            background: #059669;
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-back {
            background: #2563eb;
            border: none;
            border-radius: 14px;
            padding: 12px 30px;
            font-weight: 600;
            transition: 0.25s ease;
            color: white;
        }
        
        .btn-back:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            color: white;
        }
        
        .current-image {
            max-width: 150px;
            max-height: 150px;
            border-radius: 12px;
            margin-bottom: 10px;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 12px;
            display: none;
        }
    </style>
</head>

<body>

<?php include '../includes/admin_navbar.php'; ?>

<div class="container py-5">
    
    <div class="page-header">
        <h2><i class="bi bi-pencil-square"></i> Edit Produk</h2>
        <p>Edit informasi produk yang sudah ada</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            
            <div class="form-card">
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-4">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               value="<?= htmlspecialchars($product['name'] ?? '') ?>"
                               required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" 
                                  class="form-control" 
                                  rows="4" 
                                  required><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="price" 
                                   class="form-control" 
                                   value="<?= $product['price'] ?? 0 ?>"
                                   required>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="stock" 
                                   class="form-control" 
                                   value="<?= $product['stock'] ?? 0 ?>"
                                   required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <option value="input" <?= ($product['category'] ?? '') == 'input' ? 'selected' : '' ?>>Input</option>
                            <option value="output" <?= ($product['category'] ?? '') == 'output' ? 'selected' : '' ?>>Output</option>
                            <option value="other" <?= ($product['category'] ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Gambar Saat Ini</label>
                        <br>
                        <img src="/iot-tech/assets/images/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" 
                             class="current-image" 
                             alt="Current Image">
                        <br>
                        <label class="form-label mt-3">Ganti Gambar (Opsional)</label>
                        <input type="file" 
                               name="image" 
                               class="form-control" 
                               accept="image/*"
                               onchange="previewImage(this)">
                        <img id="imagePreview" class="image-preview" alt="Preview Gambar">
                        <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti gambar</small>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <a href="products.php" class="btn btn-back">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" name="update_product" class="btn btn-submit">
                            <i class="bi bi-save"></i> Update Produk
                        </button>
                    </div>
                    
                </form>
                
            </div>
            
        </div>
    </div>
    
</div>

<?php include '../includes/admin_footer.php'; ?>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>