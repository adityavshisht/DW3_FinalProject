<?php
session_start();
include 'header.php';
include 'database_connection.php'; // Provides $pdo connection

// Handle "Add to Cart" form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    
    // Initialize cart if it doesn't exist
	if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
	// Add product to cart or increase its quantity
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1; // Start with quantity = 1
    } else {
        $_SESSION['cart'][$product_id] += 1;
    }
    
	// Redirect to the cart page after adding an item
    header("Location: cart.php");
    exit();
}

// Query to fetch all products with category info
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

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
     // Optional: log error with error_log($e->getMessage());
    $errorMessage = "Error fetching products. Please try again later.";
}
?>

<div class="wrapper">
    <main>
        <h2>Browse Electronics for Sale</h2>

        <?php if (isset($errorMessage)): ?>
            <p style="color: red; text-align:center;"><?= $errorMessage ?></p>
        <?php elseif (!empty($products)): ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?= (isset($_SERVER['PHP_SELF']) && str_contains($_SERVER['PHP_SELF'], 'Pages')) ? '../imgs/' : 'imgs/' ?><?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image">

                        <h3><?= htmlspecialchars($product['title']) ?></h3>
                        <p><strong>Category:</strong> <?= htmlspecialchars($product['category_name']) ?></p>
                        <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
                        <p><strong>Model:</strong> <?= htmlspecialchars($product['model']) ?></p>
                        <p><strong>Specs:</strong> <?= htmlspecialchars($product['specifications']) ?></p>
                        <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>

                        <div class="btn-wrapper">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="checkout.php?product_id=<?= urlencode($product['product_id']) ?>" class="btn">Buy Now</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-cart">Add to Cart</button>
                                </form>
                            <?php else: ?>
                                <a href="login.php?redirect=buy_product.php&id=<?= $product['product_id'] ?>" class="btn">Login to Buy</a>
                                <a href="login.php?redirect=cart.php" class="btn btn-cart">Login to Add to Cart</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No products available at the moment.</p>
        <?php endif; ?>
    </main>
</div>

<?php include 'footer.php'; ?>