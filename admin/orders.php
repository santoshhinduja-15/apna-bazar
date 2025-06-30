<?php
session_start();
require_once("../db.php");

// Update order status if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();
}

// Fetch all orders with user and product info
$sql = "SELECT o.id, o.name AS customer_name, o.phone, o.address, o.city, o.zip_code,
               p.name AS product_name, o.quantity, o.total_price, o.order_status, o.order_date
        FROM orders o
        JOIN products p ON o.product_id = p.id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>
<body class="fs-5">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-3 mt-3">
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
                <li class="nav-item">
                    <a class="nav-link active d-flex align-items-center" href="admin_dashboard.php">
                        <i class="bi bi-house-door-fill text-primary me-2 fs-4"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="products.php">
                        <i class="bi bi-box-seam-fill text-success me-2 fs-4"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="categories.php">
                        <i class="bi bi-tags-fill text-warning me-2 fs-4"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="reports.php">
                        <i class="bi bi-bar-chart-fill text-info me-2 fs-4"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm ms-3 d-flex align-items-center" href="../logout.php">
                        Logout <i class="bi bi-box-arrow-right text-danger ms-2"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container-fluid mt-4">
    <h2 class="mb-4 text-center">Customer Orders</h2>

    <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle w-100">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td>
                        <?= htmlspecialchars($row['address']) ?><br>
                        <?= htmlspecialchars($row['city']) ?> - <?= htmlspecialchars($row['zip_code']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>₹<?= number_format($row['total_price'], 2) ?></td>
                    <td>
                        <?php
                            $badge = match($row['order_status']) {
                                'Pending' => 'bg-warning',
                                'Shipped' => 'bg-info',
                                'Delivered' => 'bg-success',
                                default => 'bg-secondary'
                            };
                        ?>
                        <span class="badge <?= $badge ?>"><?= $row['order_status'] ?></span>
                    </td>
                    <td><?= date("d-m-Y H:i", strtotime($row['order_date'])) ?></td>
                    <td>
                        <?php if ($row['order_status'] !== 'Delivered'): ?>
                        <form method="POST" class="d-flex">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <select name="status" class="form-select form-select-sm me-2">
                                <?php if ($row['order_status'] === 'Pending'): ?>
                                <option value="Shipped">Shipped</option>
                                <?php endif; ?>
                                <?php if ($row['order_status'] === 'Shipped'): ?>
                                <option value="Delivered">Delivered</option>
                                <?php endif; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                        <?php else: ?>
                        <em>Completed</em>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No orders found.</div>
    <?php endif; ?>
</div>

</body>
</html>
