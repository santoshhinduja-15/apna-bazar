<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;

            if ($role === 'admin') {
                $_SESSION['admin_logged_in'] = true;  // Important for admin check
                header("Location: admin/admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Account not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Login - ApnaBazar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/admin_login.css" rel="stylesheet" />
</head>

<body class="min-vh-100">

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

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="card shadow-lg">
                    <div class="card-header">
                        <img src="images/logo.jpeg" alt="ApnaBazar Logo" />
                        <h4 class="text-primary m-0">ApnaBazar - Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" novalidate id="loginForm">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" required class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="passwordInput" required
                                        class="form-control" />
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show
                                        Password</button>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        New user? <a href="signup.php">Create an account</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="js/validate_login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>