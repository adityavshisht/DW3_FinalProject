<?php
session_start();
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}

include 'database_connection.php';

$products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE product_id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        $total += $row['price'];
    }
}

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['cart'] = [];
    echo "<script>alert('✅ Order placed successfully!'); window.location.href = 'index.php';</script>";
    exit();
}
?>

<div class="wrapper">
    <main>
        <h2>Checkout</h2>

        <?php if (empty($products)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($products as $product): ?>
                    <li><?= htmlspecialchars($product['title']) ?> - $<?= number_format($product['price'], 2) ?></li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>

            <form method="POST">
                <button type="submit" class="btn">Place Order</button>
            </form>
        <?php endif; ?>
    </main>
</div>

<?php include 'footer.php'; ?>
