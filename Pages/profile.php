<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
include 'database_connection.php';

$userId = $_SESSION['user_id'];

// Fetch order history from `orders` table
$sql = "
    SELECT 
        o.order_id, o.order_date, o.total_price, o.payment_status,
        p.title, p.image
    FROM orders o
    JOIN products p ON o.product_id = p.product_id
    WHERE o.buyer_id = ?
    ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<main>
    <h2>Your Profile</h2>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
    <p>Manage your personal information and view your transactions.</p>

    <!-- Cart & Logout -->
    <p><a href="cart.php" class="btn">View Cart</a></p>
    <p><a href="logout.php" class="btn btn-logout">Logout</a></p>

    <!-- Order History -->
    <h3 style="margin-top: 40px;">Order History</h3>

    <?php if ($result->num_rows > 0): ?>
        <div class="order-history">
            <?php while ($order = $result->fetch_assoc()): ?>
                <div class="order-card">
                    <img src="../imgs/<?= htmlspecialchars($order['image']) ?>" alt="Product" class="order-thumb">
                    <div>
                        <p><strong><?= htmlspecialchars($order['title']) ?></strong></p>
                        <p>Order #<?= $order['order_id'] ?> | <?= date("d M Y, H:i", strtotime($order['order_date'])) ?></p>
                        <p>Total: $<?= number_format($order['total_price'], 2) ?></p>
                        <p>Status: <?= htmlspecialchars($order['payment_status']) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>
