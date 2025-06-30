<?php
include_once '../db.php';

$success = $error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = (int)$_POST['quantity'];

    $image = $_FILES['image']['name'];
    $target = "../images/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO products (name, price, category, image, description, quantity)
                VALUES ('$name', '$price', '$category', '$image', '$description', '$quantity')";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ Product added successfully!";
        } else {
            $error = "❌ Error inserting product: " . mysqli_error($conn);
        }
    } else {
        $error = "❌ Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>

<body>

    <!-- Navbar -->
    <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-2">
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
                    <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php"><i
                                class="bi bi-house me-1"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php"><i
                                class="bi bi-box-seam text-danger me-1"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="categories.php"><i
                                class="bi bi-tags text-warning me-1"></i> Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php"><i
                                class="bi bi-cart-check text-info me-1"></i> Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php"><i
                                class="bi bi-graph-up-arrow text-primary me-1"></i> Reports</a></li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-3" href="../logout.php">Logout <i
                                class="bi bi-box-arrow-right text-danger ms-1"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Form Section -->
    <div class="container mt-2 mb-5">
        <h2 class="text-center my-3">🛒 Add New Product</h2>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php elseif ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show text-center mb-4" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white px-4 py-5 rounded-4 shadow-sm border">
                    <form id="productForm" action="add_product.php" method="post" enctype="multipart/form-data">
                        <div id="formAlert" class="d-none mb-3"></div>

                        <div class="mb-4">
                            <label class="form-label">Product Name</label>
                            <div class="input-group">
                                <span class="input-group-text text-primary"><i class="bi bi-box"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Enter product name"
                                    required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Price (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><i class="bi bi-currency-rupee"></i></span>
                                <input type="number" name="price" step="0.01" class="form-control"
                                    placeholder="Enter price" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Category</label>
                            <div class="input-group">
                                <span class="input-group-text text-warning"><i class="bi bi-tags"></i></span>
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <?php
                  $res = mysqli_query($conn, "SELECT name FROM categories ORDER BY name ASC");
                  if ($res && mysqli_num_rows($res) > 0) {
                      while ($cat = mysqli_fetch_assoc($res)) {
                          echo '<option value="' . htmlspecialchars($cat['name']) . '">' . htmlspecialchars($cat['name']) . '</option>';
                      }
                  } else {
                      echo '<option disabled>No categories found</option>';
                  }
                ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Quantity</label>
                            <div class="input-group">
                                <span class="input-group-text text-secondary"><i class="bi bi-stack"></i></span>
                                <input type="number" name="quantity" min="1" value="1" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Product Image</label>
                            <div class="input-group">
                                <span class="input-group-text text-danger"><i class="bi bi-image"></i></span>
                                <input type="file" name="image" accept="image/*" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Description</label>
                            <div class="input-group">
                                <span class="input-group-text text-info"><i class="bi bi-card-text"></i></span>
                                <textarea name="description" rows="4" class="form-control"
                                    placeholder="Enter product description" required></textarea>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-plus-circle me-1"></i> Add Product
                            </button>
                        </div>
                    </form>

                    <?php if (isset($res) && mysqli_num_rows($res) === 0): ?>
                    <div class="alert alert-warning mt-4 text-center">
                        ⚠️ No categories found. Please <a href="add_category.php" class="alert-link">add a category</a>
                        first.
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/validate_product_form.js"></script>
</body>

</html>