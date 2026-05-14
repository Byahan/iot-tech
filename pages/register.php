<!-- register.php -->

<?php
session_start();

include '../config/firebase_auth.php';

if(isset($_SESSION['user'])){
    header("Location: /iot-tech/index.php");
    exit;
}

$error = '';

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if(empty($name) || empty($email) || empty($password)){

        $error = "Semua field wajib diisi";

    }elseif(strlen($password) < 6){

        $error = "Password minimal 6 karakter";

    }elseif($password !== $confirmPassword){

        $error = "Konfirmasi password tidak cocok";

    }else{

        try{

            $createdUser = $auth->createUser([
                'email' => $email,
                'password' => $password,
                'displayName' => $name
            ]);

            $_SESSION['success'] = "Register berhasil";

            header("Location: login.php");
            exit;

        }catch(Exception $e){

            $error = "Gagal register";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="../style.css">

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

                            <i class="bi bi-person-plus-fill"></i>

                        </div>

                        <h1 class="auth-title">
                            Create Account
                        </h1>

                        <p class="auth-subtitle">
                            Buat akun baru IoT Smart Store
                        </p>

                    </div>

                    <?php if($error): ?>

                        <div class="alert alert-danger">

                            <?php echo $error; ?>

                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Full Name

                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   placeholder="Masukkan nama lengkap"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Email Address

                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   placeholder="Masukkan email"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Password

                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Minimal 6 karakter"
                                   required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Confirm Password

                            </label>

                            <input type="password"
                                   name="confirm_password"
                                   class="form-control"
                                   placeholder="Ulangi password"
                                   required>

                        </div>

                        <button type="submit"
                                name="register"
                                class="btn btn-primary w-100 auth-button">

                            Create Account

                        </button>

                    </form>

                    <div class="text-center mt-4">

                        <p class="text-muted mb-0">

                            Sudah punya akun?

                            <a href="login.php"
                               class="auth-link">

                               Login

                            </a>

                        </p>

                    </div>

                    <div class="text-center mt-3">

                        <a href="/iot-tech/index.php"
                           class="back-home-link">

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