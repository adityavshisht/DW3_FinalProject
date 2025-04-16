<?php
session_start();
include 'header.php';
include 'database_connection.php'; // your PDO connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$productsToCheckout = [];
$totalAmount = 0;

// STEP 1: DETECT IF SINGLE PRODUCT OR CART CHECKOUT
if (isset($_GET['product_id'])) {
    // Single product checkout
    $product_id = (int)$_GET['product_id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id");
    $stmt->execute([':product_id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $product['quantity'] = 1;
        $productsToCheckout[] = $product;
        $totalAmount = $product['price'];
    } else {
        echo "<div class='error'>Product not found.</div>";
        exit();
    }
} else {
    // Cart checkout
    if (empty($_SESSION['cart'])) {
        echo "<div class='error'>Your cart is empty.</div>";
        exit();
    }

    $cart_ids = array_keys($_SESSION['cart']);
    $in_clause = implode(',', array_fill(0, count($cart_ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($in_clause)");
    $stmt->execute($cart_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $quantity = $_SESSION['cart'][$product['product_id']];
        $product['quantity'] = $quantity;
        $productsToCheckout[] = $product;
        $totalAmount += $product['price'] * $quantity;
    }
}

// STEP 2: IF FORM SUBMITTED, INSERT INTO ORDERS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    if ($payment_method === "Cash on Delivery") {
        $payment_status = "Payment Pending";
    } else {
        $payment_status = "Paid";
    }
    
    // Optionally collect card details
    $card_number = $_POST['card_number'] ?? null;
    $expiry_date = $_POST['expiry_date'] ?? null;
    $cvv = $_POST['cvv'] ?? null;

    try {
        foreach ($productsToCheckout as $item) {
            $stmt = $pdo->prepare("INSERT INTO orders (buyer_id, product_id, seller_id, total_price, payment_status)
                                   VALUES (:buyer_id, :product_id, :seller_id, :total_price, :payment_status)");
            $stmt->execute([
                ':buyer_id' => $user_id,
                ':product_id' => $item['product_id'],
                ':seller_id' => $item['user_id'],
                ':total_price' => $item['price'] * $item['quantity'],
                ':payment_status' => $payment_status
            ]);
        }

        // Clear cart after checkout
        if (!isset($_GET['product_id'])) {
            $_SESSION['cart'] = [];
        }

        echo "<script>alert('Order placed successfully!'); window.location.href = 'profile.php';</script>";
        exit();
    } catch (Exception $e) {
        echo "<div class='error'>Error placing order: " . $e->getMessage() . "</div>";
        exit();
    }
}
?>

<div class="wrapper">
    <main class="checkout-container">
        
        <h1>- - Checkout Process  - -</h1>

        <?php foreach ($productsToCheckout as $product): ?>
            <div class="product-box">
                <h4><?= htmlspecialchars($product['title']) ?></h4>
                <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
                <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?> × <?= $product['quantity'] ?></p>
            </div>
        <?php endforeach; ?>

        <p class="total-price"><strong>Total: $<?= number_format($totalAmount, 2) ?></strong></p>

        <form method="POST" class="checkout-form">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" name="phone" id="phone" required>
            </div>

            <div class="form-group">
                <label for="address">Shipping Address:</label>
                <textarea name="address" id="address" required></textarea>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="">-- Select Payment Method --</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Mastercard">Mastercard</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                </select>
            </div>

            <!-- Card Details Section (Hidden by default) -->
            <div id="cardDetails" style="display: none;">
                <label>Card Number:</label>
                <input type="text" name="card_number" maxlength="16" pattern="\d{16}" placeholder="1234567812345678" inputmode="numeric">

                <label>Expiry Date:</label>
                <input type="month" name="expiry_date">

                <label>CVV:</label>
                <input type="text" name="cvv" maxlength="4" pattern="\d{3,4}" placeholder="123" inputmode="numeric">
            </div>

            <button type="submit" class="btn">Place Order</button>
        </form>
    </main>
</div>

<script>
    const paymentMethodSelect = document.getElementById('payment_method');
    const cardDetailsDiv = document.getElementById('cardDetails');

    paymentMethodSelect.addEventListener('change', function () {
        const selected = this.value;

        if (selected === 'Credit Card' || selected === 'Debit Card' || selected === 'Mastercard') {
            cardDetailsDiv.style.display = 'block';
        } else {
            cardDetailsDiv.style.display = 'none';
        }
    });
</script>

<?php include 'footer.php'; ?>
