<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders with product and details
$sql = "SELECT o.id, p.name AS product_name, p.image AS product_image, o.quantity, o.total_price, o.order_status, o.order_date 
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders - Apna Bazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
</head>

<body>

    <!-- ✅ Navbar (as provided, unchanged) -->
    <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fs-4" href="../index.php">
                <img src="../images/logo.jpeg" alt="Logo" style="height:40px;">
                <span class="fw-bold">ApnaBazar</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../index.php">
                            <i class="bi bi-house-door-fill me-2 text-primary"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="about.php">
                            <i class="bi bi-info-circle-fill me-2 text-warning"></i> About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="cart.php">
                            <i class="bi bi-cart me-2 text-warning"></i> Cart
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../logout.php">
                            <i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../login.php">
                            <i class="bi bi-box-arrow-in-right me-2 text-success"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../signup.php">
                            <i class="bi bi-person-plus-fill me-2 text-info"></i> Sign Up
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ Orders Table Section -->
    <div class="container my-5 fs-5">
        <h2 class="text-center mb-4 text-dark">
            <i class="bi bi-bag-check-fill me-2 text-success"></i>My Orders
        </h2>

        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-bordered bg-white">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th><i class="bi bi-image-fill"></i> Image</th>
                        <th><i class="bi bi-box-seam"></i> Product</th>
                        <th><i class="bi bi-123"></i> Quantity</th>
                        <th><i class="bi bi-currency-rupee"></i> Price</th>
                        <th><i class="bi bi-info-circle-fill"></i> Status</th>
                        <th><i class="bi bi-clock-history"></i> Ordered On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?= $count++ ?></td>
                        <td class="text-center">
                            <img src="../images/<?= htmlspecialchars($row['product_image']) ?>" alt="Product" width="60"
                                height="60" class="rounded">
                        </td>
                        <td class="text-center"><?= htmlspecialchars($row['product_name']) ?></td>
                        <td class="text-center"><?= $row['quantity'] ?></td>
                        <td class="text-center">₹<?= number_format($row['total_price'], 2) ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?=
                  $row['order_status'] === 'Pending' ? 'warning' :
                  ($row['order_status'] === 'Shipped' ? 'info' :
                  ($row['order_status'] === 'Delivered' ? 'success' : 'secondary'))
                ?>">
                                <?= htmlspecialchars($row['order_status']) ?>
                            </span>
                        </td>
                        <td class="text-center"><?= date('d M Y, h:i A', strtotime($row['order_date'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>You have not placed any orders yet.
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>