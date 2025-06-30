<?php
session_start();
require_once '../db.php';

// Handle filters
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$category = $_GET['category'] ?? '';

// Fetch categories for filter dropdown
$categoryQuery = "SELECT DISTINCT category FROM products";
$categoryResult = mysqli_query($conn, $categoryQuery);

// Build WHERE clause
$where = "WHERE 1";
if ($from && $to) {
    $fromDate = $from . ' 00:00:00';
    $toDate = $to . ' 23:59:59';
    $where .= " AND o.order_date BETWEEN '$fromDate' AND '$toDate'";
}


if (!empty($category)) {
    $where .= " AND p.category = '" . mysqli_real_escape_string($conn, $category) . "'";
}

// Total Orders
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders o JOIN products p ON o.product_id = p.id $where";
$totalOrdersResult = mysqli_query($conn, $totalOrdersQuery);
$totalOrders = mysqli_fetch_assoc($totalOrdersResult)['total_orders'] ?? 0;

// Total Revenue
$totalRevenueQuery = "SELECT SUM(total_price) AS total_revenue FROM orders o JOIN products p ON o.product_id = p.id $where";
$totalRevenueResult = mysqli_query($conn, $totalRevenueQuery);
$totalRevenue = mysqli_fetch_assoc($totalRevenueResult)['total_revenue'] ?? 0.00;

// All Ordered Products
$orderedProductsQuery = "
    SELECT p.name, p.category, p.quantity AS available_stock, SUM(o.quantity) AS total_quantity
    FROM orders o
    JOIN products p ON o.product_id = p.id
    $where
    GROUP BY o.product_id
    ORDER BY total_quantity DESC
";
$orderedProductsResult = mysqli_query($conn, $orderedProductsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reports - Apna Bazar Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/index.css">
</head>

<body>

    <!-- Admin Navbar -->
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
                        <a class="nav-link d-flex align-items-center" href="orders.php">
                            <i class="bi bi-cart-check text-info me-1"></i> Orders
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

    <div class="container my-4">
        <h2 class="text-center mb-4"><i class="bi bi-graph-up-arrow text-danger me-2"></i>Sales Reports</h2>

        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" name="from" value="<?= htmlspecialchars($from) ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" name="to" value="<?= htmlspecialchars($to) ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php while ($cat = mysqli_fetch_assoc($categoryResult)) {
                    $selected = ($category == $cat['category']) ? 'selected' : '';
                    echo "<option value=\"{$cat['category']}\" $selected>{$cat['category']}</option>";
                } ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i>Apply Filters</button>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-cart-check-fill text-primary me-2"></i>Total Orders</h5>
                        <p class="display-6 fw-bold"><?= $totalOrders ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-success">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-currency-rupee text-success me-2"></i>Total Revenue</h5>
                        <p class="display-6 fw-bold">₹<?= number_format($totalRevenue, 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ordered Products Table -->
        <h4 class="mb-3"><i class="bi bi-boxes text-dark me-2"></i>All Ordered Products</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><i class="bi bi-box-fill me-1"></i>Product</th>
                        <th><i class="bi bi-tags-fill me-1"></i>Category</th>
                        <th><i class="bi bi-bag-check-fill me-1"></i>Quantity Sold</th>
                        <th><i class="bi bi-archive-fill me-1"></i>Available Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $i = 1;
                mysqli_data_seek($orderedProductsResult, 0);
                while ($row = mysqli_fetch_assoc($orderedProductsResult)) {
                    echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['category']}</td>
                            <td>{$row['total_quantity']}</td>
                            <td>{$row['available_stock']}</td>
                          </tr>";
                    $i++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>