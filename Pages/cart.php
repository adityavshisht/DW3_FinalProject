<?php
session_start();
include 'header.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove from cart
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
}

// Fetch products in cart
include 'database_connection.php';
$products = [];

if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE product_id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<div class="wrapper">
    <main>
        <h2>Your Cart</h2>

        <?php if (empty($products)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['title']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><a href="cart.php?remove=<?= $product['product_id'] ?>" class="btn">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        <?php endif; ?>
    </main>
</div>

<?php include 'footer.php'; ?>
