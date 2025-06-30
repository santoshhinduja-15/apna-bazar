<?php
include_once '../db.php'; // connect to database

// Fetch products
$sql = "SELECT * FROM products ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>

<body>

    <!-- Updated Navbar: Products replaces Dashboard -->
    <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="products.php">
                <img src="../images/logo.jpeg" alt="ApnaBazar Logo" class="me-2 rounded" width="40" height="40" />
                <span>ApnaBazar Admin</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">

                    <!-- Products = primary item (replaces Dashboard) -->
                    <li class="nav-item">
                        <a class="nav-link active d-flex align-items-center" href="admin_dashboard.php">
                            <i class="bi bi-house-door-fill text-primary me-2 fs-4"></i> Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="categories.php">
                            <i class="bi bi-tags text-warning me-1"></i> Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="orders.php">
                            <i class="bi bi-cart-check text-info me-1"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="reports.php">
                           <i class="bi bi-bar-chart-fill text-info me-2 fs-4"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-3" href="../logout.php">
                            Logout <i class="bi bi-box-arrow-right text-danger ms-1"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">📦 Manage Products</h2>

        <a href="add_product.php" class="btn btn-danger mb-3">➕ Add New Product</a>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price ₹</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><img src="../images/<?= $row['image'] ?>" width="60"></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No products found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>