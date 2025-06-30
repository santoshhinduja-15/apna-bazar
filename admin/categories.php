<?php
include '../db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>

<body>

    <!-- Navbar -->
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
                        <a class="nav-link d-flex align-items-center" href="products.php">
                            <i class="bi bi-tags text-warning me-1"></i> products
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

    <div class="container my-5">
        <h2 class="text-center mb-4">Manage Categories</h2>
        <div class="text-end mb-3">
            <a href="add_category.php" class="btn btn-success">+ Add Category</a>
        </div>

        <div class="row">
            <?php
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="../images/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top"
                        alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <!-- Trigger modal -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#confirmDeleteModal<?php echo $row['id']; ?>">
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="confirmDeleteModal<?php echo $row['id']; ?>" tabindex="-1"
                aria-labelledby="deleteLabel<?php echo $row['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteLabel<?php echo $row['id']; ?>">Confirm Deletion</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the category
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a href="delete_category.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Yes,
                                Delete</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
      }
    } else {
      echo "<p class='text-center'>No categories found.</p>";
    }
    ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>