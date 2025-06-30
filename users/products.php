<?php
session_start();
require '../db.php';

$category_name = "All Products";
$products_sql = "SELECT * FROM products";

// Handle filter by category
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $category_id = intval($_GET['category']);

    // Get category name from ID
    $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_stmt->bind_param("i", $category_id);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();

    if ($cat_result->num_rows > 0) {
        $category_row = $cat_result->fetch_assoc();
        $category_name = $category_row['name'];

        // Now filter products by category name (not ID)
        $products_stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
        $products_stmt->bind_param("s", $category_name);
        $products_stmt->execute();
        $products_result = $products_stmt->get_result();
    } else {
        // If category not found, show all products
        $products_result = mysqli_query($conn, "SELECT * FROM products");
    }
} else {
    $products_result = mysqli_query($conn, $products_sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products - <?= htmlspecialchars($category_name) ?> | Apna Bazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
</head>

<body>

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
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../index.php">
                            <i class="bi bi-house-door-fill text-info me-2 fs-5"></i>Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="about.php">
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

    <!-- Product Listing -->
    <div class="container my-5">
        <h2 class="mb-4"><i class="bi bi-boxes text-primary me-2"></i><?= htmlspecialchars($category_name) ?></h2>

        <?php if ($products_result && mysqli_num_rows($products_result) > 0): ?>
        <div class="row">
            <?php while ($product = mysqli_fetch_assoc($products_result)): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow">
                    <img src="../images/<?= htmlspecialchars($product['image']) ?>"
                        class="card-img-top border border-4 border-dark rounded"
                        alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($product['category']) ?></p>
                        <p class="card-text text-success fw-bold">₹<?= number_format($product['price'], 2) ?></p>
                        <a href="../users/product_detail.php?id=<?= $product['id'] ?>" class="btn btn-outline-success">
                            <i class="bi bi-eye-fill me-1"></i>View
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info">No products found in this category.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>