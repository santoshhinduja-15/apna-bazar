<?php
  session_start();
  include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apna Bazar - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

    <!-- Navbar -->
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
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="users/about.php">
                            <i class="bi bi-info-circle-fill text-warning me-2 fs-5"></i>About Us
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="users/cart.php">
                            <i class="bi bi-cart me-2 text-warning"></i></i>Cart
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="users/my-orders.php">
                            <i class="bi bi-receipt-cutoff text-warning me-2"></i> My Orders
                        </a>
                    </li>


                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="logout.php">
                            <i class="bi bi-box-arrow-right text-danger me-2 fs-5"></i>Logout
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="login.php">
                            <i class="bi bi-box-arrow-in-right text-success me-2 fs-5"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="signup.php">
                            <i class="bi bi-person-plus-fill text-danger me-2 fs-5"></i>Sign Up
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container my-4">
        <div class="p-5 text-center bg-danger rrounded-3 shadow">
            <h1 class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                <img src="images/logo.jpeg" alt="Apna Bazar Logo"
                    style="height: 60px; width: 90px; object-fit: contain;" class="rounded">
                <span class="fw-bold fs-1">Welcome to Apna Bazar</span>
            </h1>
            <p class="lead text-white">Your one-stop shop for groceries and more!</p>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="container my-5">
        <h2 class="mb-4"><i class="bi bi-grid-3x3-gap-fill text-secondary me-2"></i>Shop by Categories</h2>
        <div class="row">
            <?php
    $cat_sql = "SELECT * FROM categories LIMIT 6";
    $cat_result = mysqli_query($conn, $cat_sql);

    while ($category = mysqli_fetch_assoc($cat_result)) {
      echo '
        <div class="col-md-4 mb-4" style="overflow: hidden;">
          <div class="card h-100 shadow">
            <img src="images/' . htmlspecialchars($category['image']) . '" class="card-img-top" alt="' . htmlspecialchars($category['name']) . '">
            <div class="card-body text-center">
              <h5 class="card-title"><i class="bi bi-tag-fill text-warning me-1"></i>' . htmlspecialchars($category['name']) . '</h5>
              <a href="users/products.php?category=' . $category['id'] . '" class="btn btn-primary">
                <i class="bi bi-arrow-right-circle me-1"></i>Explore
              </a>
            </div>
          </div>
        </div>';
    }
    ?>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="container my-5">
        <h2 class="mb-4"><i class="bi bi-stars text-success me-2"></i>Featured Products</h2>
        <div class="row">
            <?php
    $prod_sql = "SELECT * FROM products ORDER BY RAND() LIMIT 8";
    $prod_result = mysqli_query($conn, $prod_sql);

    while ($product = mysqli_fetch_assoc($prod_result)) {
      echo '
        <div class="col-md-3 mb-4" style="overflow: hidden;">
          <div class="card h-100 shadow">
            <img src="images/' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">
            <div class="card-body text-center">
              <h5 class="card-title"><i class="bi bi-box-seam-fill text-secondary me-1"></i>' . htmlspecialchars($product['name']) . '</h5>
              <p class="card-text"><i class="bi bi-currency-rupee"></i>' . number_format($product['price'], 2) . '</p>
              <a href="users/product_detail.php?id=' . $product['id'] . '" class="btn btn-success">
                <i class="bi bi-eye-fill me-1"></i>View
              </a>
            </div>
          </div>
        </div>';
    }
    ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>