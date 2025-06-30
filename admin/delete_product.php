<?php
session_start();
require_once '../db.php';

// Check if product ID is passed via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            header("Location: products.php");
            exit();
        } else {
            // Error executing delete
            header("Location: products.php");
            exit();
        }
    } else {
        // Error preparing SQL
        header("Location: products.php");
        exit();
    }
} else {
    // Invalid or missing ID
    header("Location: products.php");
    exit();
}

$conn->close();
?>