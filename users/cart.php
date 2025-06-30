<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $rid = intval($_GET['remove']);
    unset($_SESSION['cart'][$rid]);
    header("Location: cart.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        $id = intval($id);
        $_SESSION['cart'][$id] = max(1, intval($qty));
    }
    header("Location: cart.php");
    exit();
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $pid = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    if ($stmt->get_result()->num_rows) {
        $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
    }
    $stmt->close();
}
$products = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $res = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($r = $res->fetch_assoc()) {
        $qty = $_SESSION['cart'][$r['id']];
        $r['quantity'] = $qty;
        $r['subtotal'] = $qty * $r['price'];
        $total += $r['subtotal'];
        $products[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart | Apna Bazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/cart.css">
</head>

<body>

    <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fs-4" href="../index.php">
                <img src="../images/logo.jpeg" alt="Logo" style="height:40px;">
                <span class="fw-bold">ApnaBazar</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto gap-3">

                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../index.php">
                            <i class="bi bi-house-door-fill me-2 text-primary"></i> Home
                        </a>
                    </li>

                    <!-- About -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="about.php">
                            <i class="bi bi-info-circle-fill me-2 text-warning"></i> About Us
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="my-orders.php">
                            <i class="bi bi-receipt-cutoff text-warning me-2"></i> My Orders
                        </a>
                    </li>
                    
                    <!-- User Options -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../logout.php">
                            <i class="bi bi-box-arrow-right me-2 text-danger"></i> Logout
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../login.php">
                            <i class="bi bi-box-arrow-in-right me-2 text-success"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center fs-5" href="../signup.php">
                            <i class="bi bi-person-plus-fill me-2 text-info"></i> Sign Up
                        </a>
                    </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-4">
        <div class="banner text-white text-center py-4 mb-4 shadow">
            <h2><i class="bi bi-cart-fill me-2"></i>Your Shopping Cart</h2>
            <p>Review & update your items below</p>
        </div>
    </div>

    <div class="container">
        <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">Your cart is currently empty.</div>
        <?php else: ?>
        <form method="POST" action="cart.php">
            <input type="hidden" name="update_cart" value="1">
            <div class="table-responsive shadow-sm rounded mb-3">
                <table class="table cart-table table-bordered table-hover">
                    <thead class="table-warning">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $item): ?>
                        <tr>
                            <td><?=htmlspecialchars($item['name'])?></td>
                            <td>₹<?=number_format($item['price'],2)?></td>
                            <td><input type="number" name="quantities[<?=$item['id']?>]"
                                    class="form-control quantity-input" value="<?=$item['quantity']?>" min="1"></td>
                            <td>₹<?=number_format($item['subtotal'],2)?></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#confirmRemove<?=$item['id']?>"><i class="bi bi-trash3-fill"></i>
                                    Remove</button></td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="confirmRemove<?=$item['id']?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-danger">
                                    <div class="modal-header bg-danger text-white">
                                        <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Removal</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Remove <strong><?=htmlspecialchars($item['name'])?></strong> from cart?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <a href="cart.php?remove=<?=$item['id']?>" class="btn btn-danger">Yes,
                                            Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-3">
                <div class="p-3 bg-light rounded total-card w-100 w-md-auto">
                    <h5 class="text-success mb-0">Total: ₹<?=number_format($total,2)?></h5>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-update me-2"><i class="bi bi-arrow-repeat"></i> Update
                        Cart</button>
                    <a href="checkout.php" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Proceed to
                        Checkout</a>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>