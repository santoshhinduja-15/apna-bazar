<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thank You | Apna Bazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fs-4" href="../index.php">
                <img src="../images/logo.jpeg" alt="Logo" style="height: 40px;">
                <span class="fw-bold">ApnaBazar</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3">

                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../index.php">
                            <i class="bi bi-house-door-fill me-2 text-primary"></i> Home
                        </a>
                    </li>

                    <!-- About Us -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="about.php">
                            <i class="bi bi-info-circle-fill me-2 text-warning"></i> About Us
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="cart.php">
                            <i class="bi bi-cart me-2 text-warning"></i></i>Cart
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="my-orders.php">
                            <i class="bi bi-receipt-cutoff text-warning me-2"></i> My Orders
                        </a>
                    </li>
                    
                    <!-- Conditional: Logged In or Not -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../logout.php">
                            <i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout
                        </a>
                    </li>
                    <?php else: ?>
                    <!-- Login -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../login.php">
                            <i class="bi bi-box-arrow-in-right me-2 text-success"></i> Login
                        </a>
                    </li>

                    <!-- Sign Up -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../signup.php">
                            <i class="bi bi-person-plus-fill me-2 text-danger"></i> Sign Up
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Thank You Card -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-success shadow text-center">
                    <div class="card-body">
                        <div class="display-4 text-success mb-3">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h2 class="fw-bold text-success">Thank You for Your Order!</h2>
                        <p class="fs-5 mt-3">We’ve received your order and it’s now being processed.<br>Your items will
                            be shipped soon.</p>
                        <a href="../index.php" class="btn btn-outline-primary mt-4">
                            <i class="bi bi-arrow-left-circle"></i> Continue Shopping
                        </a>
                    </div>
                    <div class="card-footer bg-success text-white text-center">
                        <small class="fw-semibold">Apna Bazar – Your Trusted Online Store 🛍️</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>