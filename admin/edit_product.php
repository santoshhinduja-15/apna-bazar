<?php
include_once '../db.php';

$success = $error = '';
$product = null;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    $product = mysqli_fetch_assoc($result);
    if (!$product) {
        $error = "Product not found.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = (int)$_POST['quantity'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../images/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $updateImage = ", image='$image'";
    } else {
        $updateImage = "";
    }

    $sql = "UPDATE products SET name='$name', price='$price', category='$category', quantity='$quantity', description='$description' $updateImage WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        $success = "✅ Product updated successfully!";
        $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
        $product = mysqli_fetch_assoc($result);
    } else {
        $error = "❌ Failed to update product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Edit Product - Apna Bazar Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>

<body>

    <!-- Navbar (unchanged) -->
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
                    <li class="nav-item">
                        <a class="nav-link active d-flex align-items-center" href="admin_dashboard.php"><i
                                class="bi bi-house me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="products.php"><i
                                class="bi bi-box-seam text-danger me-1"></i> Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="categories.php"><i
                                class="bi bi-tags text-warning me-1"></i> Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="users.php"><i
                                class="bi bi-people text-success me-1"></i> Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="orders.php"><i
                                class="bi bi-cart-check text-info me-1"></i> Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="reports.php"><i
                                class="bi bi-graph-up-arrow text-primary me-1"></i> Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-3" href="../logout.php">Logout <i
                                class="bi bi-box-arrow-right text-danger ms-1"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <h2 class="text-center mb-4">✏️ Edit Product</h2>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php elseif ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if ($product): ?>
        <form id="editProductForm" action="edit_product.php" method="post" enctype="multipart/form-data"
            class="p-4 bg-white rounded shadow-sm border">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">

            <div id="formAlert" class="d-none mb-3"></div>

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Price (₹)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">Select Category</option>
                    <?php
        $categories = ["Fruits", "Vegetables", "Beverages", "Snacks", "Grocery"];
        foreach ($categories as $cat) {
            $selected = ($cat === $product['category']) ? 'selected' : '';
            echo "<option value='$cat' $selected>$cat</option>";
        }
        ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="<?= $product['quantity'] ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="../images/<?= $product['image'] ?>" width="100" class="mb-2">
            </div>

            <div class="mb-3">
                <label class="form-label">Change Image (optional)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="4"
                    class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Product</button>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/validate_edit_product_form.js"></script>
</body>

</html>