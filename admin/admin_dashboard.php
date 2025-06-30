<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

require_once "../db.php";

$productCount = 0;
$userCount = 0;
$orderCount = 0;

$result = $conn->query("SELECT COUNT(*) as total FROM products");
if ($result) {
    $row = $result->fetch_assoc();
    $productCount = $row['total'];
}

$result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
if ($result) {
    $row = $result->fetch_assoc();
    $userCount = $row['total'];
}

$result = $conn->query("SELECT COUNT(*) as total FROM orders");
if ($result) {
    $row = $result->fetch_assoc();
    $orderCount = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard - ApnaBazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>

<body>

   <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-3 mt-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="products.php">
                <img src="../images/logo.jpeg" alt="ApnaBazar Logo" class="me-2 fs-4 rounded" width="40" height="40" />
                <span class="fw-bold text-warning">ApnaBazar Admin</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <!-- Products -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="products.php">
                            <i class="bi bi-box-seam-fill text-success me-2 fs-4"></i> Products
                        </a>
                    </li>

                    <!-- Categories -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="categories.php">
                            <i class="bi bi-tags-fill text-warning me-2 fs-4"></i> Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="orders.php">
                            <i class="bi bi-cart-check text-info me-2 fs-4"></i> Orders
                        </a>
                    </li>

                    <!-- Reports -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="reports.php">
                            <i class="bi bi-bar-chart-fill text-info me-2 fs-4"></i> Reports
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-3 d-flex align-items-center" href="../logout.php">
                            Logout <i class="bi bi-box-arrow-right text-danger ms-2"></i>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>


    <div class="container my-5">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p class="lead">Here is the overview of your store.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-box-seam fs-1 text-success me-3"></i>
                        <div>
                            <h5 class="card-title text-muted">Total Products</h5>
                            <h2><?= $productCount; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-people fs-1 text-info me-3"></i>
                        <div>
                            <h5 class="card-title text-muted">Total Users</h5>
                            <h2><?= $userCount; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-cart-check fs-1 text-warning me-3"></i>
                        <div>
                            <h5 class="card-title text-muted">Total Orders</h5>
                            <h2><?= $orderCount; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>