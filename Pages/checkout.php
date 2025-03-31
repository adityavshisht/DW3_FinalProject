<?php
session_start();
include 'header.php';
include 'database_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php?product_id=" . urlencode($_GET['product_id']));
    exit();
}

if (!isset($_GET['product_id'])) {
    echo "<p>No product selected for checkout.</p>";
    exit();
}

$product_id = (int) $_GET['product_id'];

// Fetch product details
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Product not found.</p>";
    exit();
}

$product = $result->fetch_assoc();
?>

<div class="wrapper">
    <h2>Checkout</h2>
    <div class="checkout-card">
        <img src="imgs/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="checkout-image">
        <h4><?= htmlspecialchars($product['title']) ?></h4>
        <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
        <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>

        <form action="process_payment.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
            <input type="hidden" name="price" value="<?= $product['price'] ?>">
            <button type="submit" class="btn">Proceed to Payment</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
