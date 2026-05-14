<!-- profile.php -->

<?php
session_start();

if(!isset($_SESSION['user'])){

    header('Location: login.php');
    exit;
}

include '../config/firebase_auth.php';

$user = $_SESSION['user'];

$name = $user['name'] ?? 'User';
$email = $user['email'] ?? '-';

$firstLetter = strtoupper(substr($name, 0, 1));

$success = "";
$error = "";

/* UPDATE NAME */

if(isset($_POST['update_profile'])){

    $newName = trim($_POST['name']);

    if($newName != ""){

        try{

            $uid = $user['uid'];

            $auth->updateUser($uid, [
                'displayName' => $newName
            ]);

            $_SESSION['user']['name'] = $newName;

            $name = $newName;

            $firstLetter = strtoupper(substr($name, 0, 1));

            $success = "Nama berhasil diperbarui";

        }catch(Exception $e){

            $error = $e->getMessage();
        }

    }else{

        $error = "Nama tidak boleh kosong";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="../style.css">

</head>

<body>

<?php include '../navbar.php'; ?>

<div class="container py-5">

    <div class="profile-wrapper">

        <div class="row g-4">

            <!-- PROFILE CARD -->

            <div class="col-lg-4">

                <div class="profile-card h-100">

                    <div>

                        <div class="profile-avatar">

                            <?php echo $firstLetter; ?>

                        </div>

                        <h2 class="profile-name">

                            <?php echo htmlspecialchars($name); ?>

                        </h2>

                        <p class="profile-email">

                            <?php echo htmlspecialchars($email); ?>

                        </p>

                        <span class="profile-badge">

                            Firebase Auth User

                        </span>

                    </div>

                    <!-- LOGOUT BUTTON -->

                    <div class="mt-auto w-100">

                        <a href="logout.php"
                           class="btn btn-danger w-100 mt-4">

                            <i class="bi bi-box-arrow-right"></i>
                            Logout

                        </a>

                    </div>

                </div>

            </div>

            <!-- PROFILE INFO -->

            <div class="col-lg-8">

                <div class="profile-info-card">

                    <div class="profile-section-title">

                        <h3>
                            Account Settings
                        </h3>

                        <p>
                            Kelola informasi akun Anda
                        </p>

                    </div>

                    <?php if($success != ""): ?>

                        <div class="alert alert-success mt-4">

                            <?php echo $success; ?>

                        </div>

                    <?php endif; ?>

                    <?php if($error != ""): ?>

                        <div class="alert alert-danger mt-4">

                            <?php echo $error; ?>

                        </div>

                    <?php endif; ?>

                    <!-- UPDATE FORM -->

                    <form method="POST"
                          class="mt-4">

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Full Name

                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($name); ?>"
                                   required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Email Address

                            </label>

                            <input type="email"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($email); ?>"
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

<?php include '../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>