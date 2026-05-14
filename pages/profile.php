<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

include '../config/path.php';
include '../config/firebase.php';
include '../config/firebase_auth.php';

$user = $_SESSION['user'];

$name = $user['name'] ?? 'User';
$email = $user['email'] ?? '-';
$uid = $user['uid'];

$firstLetter = strtoupper(substr($name, 0, 1));

$success = "";
$error = "";

/* UPDATE PROFILE */
if (isset($_POST['update_profile'])) {

    $newName = trim($_POST['name']);

    if ($newName !== "") {

        try {

            // 1. Update Firebase Auth
            $auth->updateUser($uid, [
                'displayName' => $newName
            ]);

            // 2. Update Realtime Database
            firebaseSet("users/$uid", [
                'name' => $newName,
                'email' => $email,
                'role' => $user['role'] ?? 'user'
            ]);

            // 3. Update session
            $_SESSION['user']['name'] = $newName;

            // refresh local variables
            $name = $newName;
            $firstLetter = strtoupper(substr($name, 0, 1));

            $success = "Nama berhasil diperbarui";

        } catch (Exception $e) {
            $error = "Gagal update: " . $e->getMessage();
        }

    } else {
        $error = "Nama tidak boleh kosong";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="<?= CSS_URL ?>style.css">
</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">

    <div class="profile-wrapper">

        <div class="row g-4">

            <!-- LEFT CARD -->
            <div class="col-lg-4">

                <div class="profile-card h-100 d-flex flex-column justify-content-between">

                    <div class="text-center">

                        <div class="profile-avatar mx-auto">
                            <?= $firstLetter ?>
                        </div>

                        <h2 class="profile-name mt-3">
                            <?= htmlspecialchars($name) ?>
                        </h2>

                        <p class="profile-email">
                            <?= htmlspecialchars($email) ?>
                        </p>

                    </div>

                    <div class="mt-4">

                        <a href="/iot-tech/admin/dashboard.php"
                           class="btn btn-primary w-100 mb-3">

                            Go to Admin
                        </a>

                        <a href="logout.php"
                           class="btn btn-danger w-100">

                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </a>

                    </div>

                </div>

            </div>

            <!-- RIGHT CARD -->
            <div class="col-lg-8">

                <div class="profile-info-card">

                    <div class="profile-section-title">
                        <h3>Account Settings</h3>
                        <p>Kelola informasi akun Anda</p>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success mt-4">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger mt-4">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="mt-4">

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Full Name
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="<?= htmlspecialchars($name) ?>"
                                   required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input type="email"
                                   class="form-control"
                                   value="<?= htmlspecialchars($email) ?>"
                                   disabled>

                        </div>

                        <button type="submit"
                                name="update_profile"
                                class="btn btn-primary">

                            <i class="bi bi-check-circle"></i>
                            Save Changes

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>