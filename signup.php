<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $mobile = trim(mysqli_real_escape_string($conn, $_POST['mobile']));
    $password = trim(mysqli_real_escape_string($conn, $_POST['password']));
    $role = trim(mysqli_real_escape_string($conn, $_POST['role']));

    if (empty($name) || empty($email) || empty($mobile) || empty($password) || empty($role)) {
        header("Location: signup.php?error=Please fill all fields");
        exit();
    }

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' OR mobile = '$mobile'");
    if (mysqli_num_rows($check) > 0) {
        header("Location: signup.php?error=User with same email or mobile already exists");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $insert = mysqli_query($conn, "INSERT INTO users (name, email, mobile, password, role)
                                   VALUES ('$name', '$email', '$mobile', '$hashedPassword', '$role')");

    if ($insert) {
        header("Location: signup.php?success=Account created successfully");
        exit();
    } else {
        header("Location: signup.php?error=Something went wrong, please try again");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup - ApnaBazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="min-vh-100" style="background-color: #53d192;">

    <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fs-4" href="index.php">
                <img src="images/logo.jpeg" alt="Logo" style="height: 40px;">
                <span class="fw-bold">ApnaBazar</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item"><a class="nav-link d-flex align-items-center fs-5" href="index.php">
                            <i class="bi bi-house-door-fill text-info me-2 fs-5"></i>Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link d-flex align-items-center fs-5"
                            href="users/about.php">
                            <i class="bi bi-info-circle-fill text-warning me-2 fs-5"></i>About Us</a>
                    </li>
                    <li class="nav-item"><a class="nav-link d-flex align-items-center fs-5" href="login.php">
                            <i class="bi bi-box-arrow-in-right text-success me-2 fs-5"></i>Login</a>
                    </li>
                    <li class="nav-item"><a class="nav-link d-flex align-items-center fs-5" href="signup.php">
                            <i class="bi bi-person-plus-fill text-danger me-2 fs-5"></i>Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_GET['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="card shadow-lg">
                    <div
                        class="card-header bg-white text-center d-flex align-items-center justify-content-center gap-3">
                        <img src="images/logo.jpeg" alt="ApnaBazar Logo" style="height: 50px;">
                        <h4 class="text-primary m-0">ApnaBazar - Sign Up</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="signup.php" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" required class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" required class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" required class="form-control" maxlength="10">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="passwordInput" required
                                        class="form-control">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">Show
                                        Password</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Select Role --</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Create Account</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Already have an account? <a href="login.php">Login</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="js/validate_signup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>