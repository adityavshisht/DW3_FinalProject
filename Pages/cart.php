<?php
session_start();
include 'header.php';
include 'database_connection.php'; // Provides $pdo for DB access

// Make sure the cart session is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove item from cart if requested
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
}

// Prepare to fetch cart items and calculate total
$products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
        $stmt->execute($ids);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $row['quantity'] = $_SESSION['cart'][$row['product_id']];
            $products[] = $row;
            $total += $row['price'] * $row['quantity'];
        }
    } catch (PDOException $e) {
        $errorMessage = "Error fetching cart items. Please try again later.";
    }
}
?>

<div class="wrapper">
    <main>
        <h2>Your Cart</h2>

        <?php if (isset($errorMessage)): ?>
            <p style="color: red; text-align:center;"><?= $errorMessage ?></p>
        <?php elseif (empty($products)): ?>
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

            <p><strong>Total: $<?= number_format($total, 2) ?></strong></p>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        <?php endif; ?>
    </main>
</div>

<?php include 'footer.php'; ?>
