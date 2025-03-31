<?php
session_start();
include 'header.php';
include 'database_connection.php';

// Ensure cart session exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
}

// Fetch products from database
$products = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE product_id IN ($ids)";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $_SESSION['cart'][$row['product_id']];
        $products[] = $row;
        $total += $row['price'] * $row['quantity'];
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
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['title']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= $product['quantity'] ?></td>
                        <td><a href="cart.php?remove=<?= $product['product_id'] ?>">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p>Total: $<?= number_format($total, 2) ?></p>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        <?php endif; ?>
    </main>
</div>
