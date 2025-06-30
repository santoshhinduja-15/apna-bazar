<?php
include '../db.php';

$success = $error = "";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // First check if the category exists
    $checkSql = "SELECT * FROM categories WHERE id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "i", $id);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($result) === 0) {
        $error = "❌ Category not found.";
    } else {
        // Perform delete
        $deleteSql = "DELETE FROM categories WHERE id = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "i", $id);

        if (mysqli_stmt_execute($deleteStmt)) {
            $success = "✅ Category deleted successfully!";
        } else {
            $error = "❌ Failed to delete category.";
        }
    }
} else {
    $error = "Invalid category ID.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="text-center">
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

            <a href="categories.php" class="btn btn-primary mt-3">🔙 Back to Categories</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>