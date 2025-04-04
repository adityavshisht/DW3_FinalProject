<?php
session_start();
include 'header.php';
include 'database_connection.php'; // Assumes this provides $pdo

// Ensure cart session exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
}

// Fetch products from database using PDO
$products = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE product_id IN (:ids)";
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN (" . $ids . ")"); // Note: PDO doesn't support IN directly with binding
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $row) {
            $row['quantity'] = $_SESSION['cart'][$row['product_id']];
            $products[] = $row;
            $total += $row['price'] * $row['quantity'];
        }
    } catch (PDOException $e) {
        // For debugging, you could log: error_log($e->getMessage());
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
            <p>Total: $<?= number_format($total, 2) ?></p>
            <a href="checkout.php?product_id=<?= urlencode($product['product_id']) ?>" class="btn">Proceed to Checkout</a>
        <?php endif; ?>
    </main>
</div>

<?php include 'footer.php'; ?>