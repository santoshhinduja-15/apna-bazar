<?php
include '../db.php';

$success = $error = '';
$id = $_GET['id'] ?? null;

// Check if ID is present
if (!$id) {
    header("Location: categories.php");
    exit;
}

// Fetch existing category
$sql = "SELECT * FROM categories WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$category = mysqli_fetch_assoc($result);

if (!$category) {
    $error = "❌ Category not found.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $newImage = $_FILES['image'];
    $imageName = $category['image']; // default

    if (empty($name)) {
        $error = "Category name is required.";
    } else {
        // Handle new image if uploaded
        if (!empty($newImage['name'])) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($newImage['name'], PATHINFO_EXTENSION));
            $size = $newImage['size'];

            if (!in_array($ext, $allowed_types)) {
                $error = "Only JPG, JPEG, PNG, or GIF files are allowed.";
            } elseif ($size > 2 * 1024 * 1024) {
                $error = "Image size must be under 2MB.";
            } else {
                $imageName = basename($newImage['name']);
                $targetPath = "../images/" . $imageName;
                move_uploaded_file($newImage["tmp_name"], $targetPath);
            }
        }

        if (!$error) {
            $updateSql = "UPDATE categories SET name = ?, image = ? WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($updateStmt, "ssi", $name, $imageName, $id);

            if (mysqli_stmt_execute($updateStmt)) {
                $success = "✅ Category updated successfully!";
                $category['name'] = $name;
                $category['image'] = $imageName;
            } else {
                $error = "❌ Failed to update category.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Category - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="categories.php">🔙 Back to Categories</a>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h3 class="card-title mb-4">✏️ Edit Category</h3>

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

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?= htmlspecialchars($category['name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Current Image</label><br>
                                <img src="../images/<?= htmlspecialchars($category['image']) ?>" class="img-thumbnail"
                                    style="max-height: 200px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Change Image (optional)</label>
                                <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                            </div>

                            <div class="mb-3 d-none" id="imagePreviewBox">
                                <label class="form-label">New Image Preview</label><br>
                                <img id="imagePreview" class="img-thumbnail" style="max-height: 200px;">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap + JS Preview -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/category_form.js"></script>
</body>

</html>