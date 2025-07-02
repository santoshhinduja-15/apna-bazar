<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'];

$ids = implode(',', array_keys($cart));
$res = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
$items = [];
$total = 0;
while ($r = $res->fetch_assoc()) {
    $q = intval($cart[$r['id']]);
    $sub = $q * $r['price'];
    $items[] = [
        'id' => $r['id'],
        'name' => $r['name'],
        'price' => $r['price'],
        'quantity' => $q,
        'subtotal' => $sub
    ];
    $total += $sub;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $zip = trim($_POST['zip']);
    $phone = trim($_POST['phone']);

    if (empty($name) || empty($address) || empty($city) || empty($zip) || empty($phone)) {
        $msg = "Please fill in all shipping details.";
    } elseif (empty($_SESSION['cart'])) {
        $msg = "Your cart is empty.";
    } else {
        foreach ($_SESSION['cart'] as $product_id => $qty) {
            // Fetch product details
            $stmt = $conn->prepare("SELECT price, quantity FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $product = $res->fetch_assoc();

            if (!$product || $product['quantity'] < $qty) {
                $msg = "Insufficient stock for one or more products.";
                break;
            }

            $total_price = $qty * $product['price'];

            // Insert into orders
            $insert = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, name, address, city, zip_code, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("iiidsssss", $user_id, $product_id, $qty, $total_price, $name, $address, $city, $zip, $phone);

            $insert->execute();

            // Update product quantity
            $new_qty = $product['quantity'] - $qty;
            $update = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
            $update->bind_param("ii", $new_qty, $product_id);
            $update->execute();
        }

        if (empty($msg)) {
            unset($_SESSION['cart']);
            header("Location: thank_you.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Apna Bazar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/checkout.css">
</head>

<body>
<!-- Navbar -->
 <nav
        class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border border-warning rounded-pill border-5 my-2 mt-3">
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
                        <a class="nav-link d-flex align-items-center fs-5" href="cart.php">
                            <i class="bi bi-cart me-2 text-warning"></i></i>Cart
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

<!-- Page Content -->
<div class="container my-5">
    <div class="checkout-banner text-white p-4 text-center mb-4 shadow-sm bg-primary rounded">
        <h2 class="checkout-header"><i class="bi bi-check2-circle me-2"></i>Checkout</h2>
        <p class="mb-0">Complete your order and we’ll take care of the rest!</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="alert alert-info text-center">Your cart is empty.</div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Shipping Form -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="bi bi-truck me-2"></i>Shipping Address</h4>
                        <form method="POST" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">City</label>
                                    <input name="city" class="form-control" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">ZIP Code</label>
                                    <input name="zip" class="form-control" value="<?= htmlspecialchars($_POST['zip'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                            </div>
                            <button type="submit" name="place_order" class="btn btn-success w-100">
                                <i class="bi bi-credit-card-2-front-fill me-1"></i> Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="bi bi-receipt me-2"></i>Order Summary</h4>
                        <ul class="list-group mb-3">
                            <?php foreach ($items as $it): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($it['name']) ?> × <?= $it['quantity'] ?>
                                    <span class="badge bg-primary rounded-pill">₹<?= number_format($it['subtotal'], 2) ?></span>
                                </li>
                            <?php endforeach; ?>
                            <li class="list-group-item d-flex justify-content-between fw-bold bg-light">
                                Total
                                <span>₹<?= number_format($total, 2) ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
