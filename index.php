<?php
session_start();
include 'header.php';
include 'Pages/database_connection.php';

// Initialize the cart if it doesn't already exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

try {
    // Fetch the latest products
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

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Error fetching products: " . $e->getMessage());
    $products = [];
}
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
                <a href="Pages/login.php" class="btn">Login</a>
                <a href="Pages/signup.php" class="btn">Sign Up</a>
            </div>
        <?php endif; ?>

        <h3 style="margin-top: 40px;">Latest Listings</h3>

        <?php if ($products && count($products) > 0): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="imgs/<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['title']) ?>" 
                             class="product-image">
                        <h4><?= htmlspecialchars($product['title']) ?></h4>
                        <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
                        <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="Pages/checkout.php?product_id=<?= urlencode($product['product_id']) ?>" 
                               class="btn">Buy Now</a>
                            <a href="Pages/add_to_cart.php?product_id=<?= urlencode($product['product_id']) ?>" 
                               class="btn btn-cart">Add to Cart</a>
                        <?php else: ?>
                            <a href="Pages/login.php?redirect=Pages/checkout.php?product_id=<?= urlencode($product['product_id']) ?>" 
                               class="btn">Buy</a>
                            <a href="Pages/login.php?redirect=Pages/cart.php" 
                               class="btn btn-cart">Add to Cart</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; margin: 30px 0; color: #666;">
                No products available at the moment.
            </p>
        <?php endif; ?>
    </main>
</div>

<?php include 'Pages/footer.php'; ?>