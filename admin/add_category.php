<?php
include '../db.php';

$success = "";
$error = "";
$name = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $image = $_FILES['image'];

    if (empty($name)) {
        $error = "Category name is required.";
    } elseif (!empty($image['name'])) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $image_name = basename($image['name']);
        $image_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $image_size = $image['size'];

        if (!in_array($image_type, $allowed_types)) {
            $error = "Only JPG, JPEG, PNG, or GIF files are allowed.";
        } elseif ($image_size > 2 * 1024 * 1024) {
            $error = "Image size must be under 2MB.";
        } else {
            $target_path = "../images/" . $image_name;
            if (move_uploaded_file($image["tmp_name"], $target_path)) {
                $sql = "INSERT INTO categories (name, image) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $name, $image_name);
                if (mysqli_stmt_execute($stmt)) {
                    $success = "✅ Category added successfully!";
                    $name = ""; // Clear form
                } else {
                    $error = "Database error: " . mysqli_error($conn);
                }
            } else {
                $error = "Failed to upload image.";
            }
        }
    } else {
        $error = "Please select an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Category - Admin</title>
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
                        <h3 class="card-title mb-4">➕ Add New Category</h3>

                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php elseif ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?php echo htmlspecialchars($name); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category Image</label>
                                <input type="file" name="image" id="imageInput" class="form-control" accept="image/*"
                                    required>
                            </div>

                            <div class="mb-3 d-none" id="imagePreviewBox">
                                <label class="form-label">Preview</label><br>
                                <img id="imagePreview" class="img-thumbnail" style="max-height: 200px;">
                            </div>

                            <button type="submit" class="btn btn-success">Add Category</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (required for dismissible alerts) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Your custom preview logic -->
    <script src="../js/category_form.js"></script>
</body>

</html>