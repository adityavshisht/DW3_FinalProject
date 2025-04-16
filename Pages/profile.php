<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
include 'database_connection.php'; // Provides the $pdo connection

$userId = $_SESSION['user_id'];

// Fetch user details
$userSql = "SELECT name, email, phone, address FROM users WHERE user_id = :userId";
$userStmt = $pdo->prepare($userSql);
$userStmt->execute([':userId' => $userId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Fetch the logged-in user's order history
$sql = "
    SELECT 
        o.order_id, o.order_date, o.total_price, o.payment_status,
        p.title, p.image
    FROM orders o
    JOIN products p ON o.product_id = p.product_id
    WHERE o.buyer_id = :userId
    ORDER BY o.order_date DESC
";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	// Optional: log the error for debugging
    // error_log($e->getMessage());
    $errorMessage = "Error fetching order history. Please try again later.";
}
?>

<main>
    <h2>Your Profile</h2>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
    <p>Manage your personal information and view your transactions.</p>
	
	<!-- User Information -->
    <div style="max-width: 600px; margin: 20px auto; text-align: left;">
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($user['address'] ?? 'Not provided') ?></p>
        <a href="edit_profile.php" class="btn">Update Profile</a>
    </div>

    <!-- Cart & Logout -->
    <p><a href="cart.php" class="btn">View Cart</a></p>
    <p><a href="logout.php" class="btn btn-logout">Logout</a></p>

    <!-- Order History -->
    <h3 style="margin-top: 40px;">Order History</h3>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red; text-align:center;"><?= $errorMessage ?></p>
    <?php elseif (!empty($orders)): ?>
        <div class="order-history">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <img src="../imgs/<?= htmlspecialchars($order['image']) ?>" alt="Product" class="order-thumb">
                    <div>
                        <p><strong><?= htmlspecialchars($order['title']) ?></strong></p>
                        <p>Order #<?= $order['order_id'] ?> | <?= date("d M Y, H:i", strtotime($order['order_date'])) ?></p>
                        <p>Total: $<?= number_format($order['total_price'], 2) ?></p>
                        <p>Status: <?= htmlspecialchars($order['payment_status']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <p><a href="appointment_history.php" class="btn">Appointments</a></p>

</main>

<?php include 'footer.php'; ?>