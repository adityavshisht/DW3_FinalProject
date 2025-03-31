<?php
session_start();
include 'header.php';
include 'database_connection.php';

// Ensure cart session exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch latest 6 products
$sql = "
    SELECT 
        p.product_id,
        p.title,
        p.brand,
        p.model,
        p.specifications,
        p.price,
        p.image,
        c.category_name
    FROM products p
    JOIN categories c ON p.category_id = c.category_id
    ORDER BY p.created_at DESC
    LIMIT 6
";
$result = $conn->query($sql);
?>

<div class="wrapper">
    <main>
        <?php if (isset($_SESSION['user_id'])): ?>
            <h2>Welcome back, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?> 👋</h2>
            <p>Explore new listings or manage your profile.</p>
        <?php else: ?>
            <h2>Welcome to ReTechX</h2>
            <p>Buy and sell used electronics securely and conveniently.</p>
            <div class="btn-wrapper">
                <a href="login.php" class="btn">Login</a>
                <a href="signup.php" class="btn">Sign Up</a>
            </div>
        <?php endif; ?>

        <h3 style="margin-top: 40px;">Latest Listings</h3>
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="product-grid">
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="<?= (isset($_SERVER['PHP_SELF']) && str_contains($_SERVER['PHP_SELF'], 'Pages')) ? '../imgs/' : 'imgs/' ?><?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image">
                        <h4><?= htmlspecialchars($product['title']) ?></h4>
                        <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
                        <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="checkout.php?product_id=<?= urlencode($product['product_id']) ?>" class="btn">Buy Now</a>
                            <a href="add_to_cart.php?product_id=<?= urlencode($product['product_id']) ?>" class="btn btn-cart">Add to Cart</a>
                        <?php else: ?>
                            <a href="login.php?redirect=checkout.php?product_id=<?= urlencode($product['product_id']) ?>" class="btn">Buy</a>
                            <a href="login.php?redirect=cart.php" class="btn btn-cart">Add to Cart</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No products available yet.</p>
        <?php endif; ?>
    </main>
</div>

<?php include 'footer.php'; ?>
