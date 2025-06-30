<?php
session_start();
require '../db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$product_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $error = "Product not found.";
} else {
    $product = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= isset($product) ? htmlspecialchars($product['name']) : 'Product Not Found' ?> | Apna Bazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/product_detail.css">
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

    <!-- Product Detail -->
    <div class="container my-5">
        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
        <?php else: ?>
        <div class="product-container">
            <div class="row g-4 align-items-center">
                <div class="col-md-5 text-center">
                    <img src="../images/<?= htmlspecialchars($product['image']) ?>" class="img-fluid"
                        alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="col-md-7">
                    <h2 class="product-title fw-bold"><?= htmlspecialchars($product['name']) ?></h2>
                    <p class="product-meta"><i class="bi bi-tags-fill text-warning me-1"></i>Category:
                        <?= htmlspecialchars($product['category']) ?></p>
                    <p class="fs-5 text-success"><strong>Price:</strong> ₹<?= number_format($product['price'], 2) ?></p>
                    <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                    <div class="d-flex gap-3 mt-4">
                        <a href="cart.php?id=<?= $product['id'] ?>" class="btn btn-success">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>