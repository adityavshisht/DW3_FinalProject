<?php
session_start();
include 'header.php';
include 'database_connection.php';

// Fetch products
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
";
$result = $conn->query($sql);
?>

<div class="wrapper">
    <main>
        <h2>Browse Electronics for Sale</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="product-grid">
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img src="../imgs/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image">
                        <h3><?= htmlspecialchars($product['title']) ?></h3>
                        <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
                        <p><strong>Model:</strong> <?= htmlspecialchars($product['model']) ?></p>
                        <p><strong>Specs:</strong> <?= htmlspecialchars($product['specifications']) ?></p>
                        <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="buy_product.php?id=<?= $product['product_id'] ?>" class="btn">Buy Now</a>
                        <?php else: ?>
                            <a href="login.php?redirect=buy.php" class="btn">Login to Buy</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No products available at the moment.</p>
        <?php endif; ?>
    </main>
</div>

<?php include 'footer.php'; ?>
