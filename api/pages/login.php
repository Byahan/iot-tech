<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/firebase_auth.php';

/* if already logged in */
if (isset($_SESSION['user'])) {
    header("Location: /iot-tech/index.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {

        /* Firebase sign in */
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);

        $firebaseUser = $signInResult->data();

        /* get user info safely */
        $uid = $firebaseUser['localId'];

        $userInfo = $auth->getUser($uid);

        /* GET ROLE FROM REALTIME DATABASE */
        include '../config/firebase.php';

        $userData = firebaseGet("users/$uid");

        $_SESSION['user'] = [
            'uid' => $uid,
            'email' => $userInfo->email,
            'name' => $userInfo->displayName ?? 'User',
            'role' => $userData['role'] ?? 'user'
        ];

        header("Location: /iot-tech/index.php");
        exit;

    } catch (Exception $e) {

        $error = "Email atau password salah";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- FIXED PATH -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="auth-page">
    <div class="auth-overlay"></div>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-lg-5 col-md-7">

                <div class="auth-card">

                    <div class="text-center mb-4">

                        <div class="auth-icon">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>

                        <h1 class="auth-title">Welcome Back</h1>

                        <p class="auth-subtitle">
                            Login ke akun IoT Smart Store
                        </p>

                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="Masukkan email" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Masukkan password" required>
                        </div>

                        <button type="submit"
                                name="login"
                                class="btn btn-primary w-100 auth-button">

                            Login

                        </button>

                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">
                            Belum punya akun?
                            <a href="register.php" class="auth-link">Register</a>
                        </p>
                    </div>

                    <div class="text-center mt-3">
                        <a href="/iot-tech/index.php" class="back-home-link">
                            ← Kembali ke Home
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>