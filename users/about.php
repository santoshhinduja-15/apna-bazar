<?php
session_start();
include '../db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us - ApnaBazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
</head>

<body class="min-vh-100" style="background-color: #53d192;">

    <!-- Navbar -->
    <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-2 fs-5">
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
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../index.php">
                            <i class="bi bi-house-door-fill text-info me-2 fs-5"></i>Home
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
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../logout.php">
                            <i class="bi bi-box-arrow-right text-danger me-2 fs-5"></i>Logout
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../login.php">
                            <i class="bi bi-box-arrow-in-right text-success me-2 fs-5"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../signup.php">
                            <i class="bi bi-person-plus-fill text-danger me-2 fs-5"></i>Sign Up
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center align-items-center gap-3">
                <img src="../images/logo.jpeg" alt="ApnaBazar Logo" style="height: 50px;">
                <h2 class="text-dark">ApnaBazar - About Us</h2>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title">Who We Are</h4>
                <p class="card-text">
                    <strong>ApnaBazar</strong> is your trusted online grocery platform, offering a wide range of daily
                    essentials — from fresh fruits to pantry staples — all delivered to your doorstep.
                </p>

                <h4 class="mt-4">Our Mission</h4>
                <p class="card-text">
                    To simplify your shopping experience by bringing local markets online with a promise of quality,
                    speed, and affordability.
                </p>

                <h4 class="mt-4">Why Choose Us?</h4>
                <ul class="list-unstyled">
                    <li><i class="bi bi-bag-check-fill text-success me-2"></i>Fresh and quality-checked products</li>
                    <li><i class="bi bi-truck text-primary me-2"></i>Fast delivery and real-time tracking</li>
                    <li><i class="bi bi-shield-lock-fill text-warning me-2"></i>Secure and multiple payment options</li>
                    <li><i class="bi bi-tag-fill text-danger me-2"></i>Exclusive discounts and seasonal offers</li>
                    <li><i class="bi bi-headset text-info me-2"></i>Friendly customer support</li>
                </ul>

                <h4 class="mt-4">Contact Us</h4>
                <p class="card-text">
                    <i class="bi bi-envelope-fill text-danger me-2"></i>Email: support@apnabazar.com<br>
                    <i class="bi bi-telephone-fill text-success me-2"></i>Phone: +91 98765 43210<br>
                    <i class="bi bi-clock-fill text-primary me-2"></i>Timing: 9 AM – 9 PM (All days)
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>