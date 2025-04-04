<?php
session_start();
include 'header.php';
include 'database_connection.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $redirectTo = "checkout.php?product_id=" . urlencode($_GET['product_id'] ?? '');
    header("Location: login.php?redirect=" . $redirectTo);
    exit();
}

// ✅ Get product_id from GET or POST
$product_id = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? (int)($_POST['product_id'] ?? 0)
    : (int)($_GET['product_id'] ?? 0);

// ✅ Validate product_id
if (!$product_id) {
    echo "<main><h2>No product selected for checkout.</h2><a href='buy.php' class='btn'>Browse Products</a></main>";
    include 'footer.php';
    exit();
}

// ✅ Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<main><h2>Product not found.</h2><a href='buy.php' class='btn'>Back to Products</a></main>";
    include 'footer.php';
    exit();
}

$product = $result->fetch_assoc();

// ✅ Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['payment_method'])) {
    $buyer_id = $_SESSION['user_id'];
    $seller_id = $product['user_id'];
    $price = $product['price'];
    $payment_status = "Paid";

    $stmt = $conn->prepare("INSERT INTO orders (buyer_id, product_id, seller_id, total_price, payment_status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiids", $buyer_id, $product_id, $seller_id, $price, $payment_status);
    $stmt->execute();

    echo "<script>alert('✅ Order placed successfully!'); window.location.href = 'profile.php';</script>";
    exit();
}
?>

<!-- ✅ Checkout UI -->
<div class="wrapper">
    <main>
        <h2>Checkout</h2>

        <div class="checkout-card">
            <img src="../imgs/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="checkout-image">
            <h4><?= htmlspecialchars($product['title']) ?></h4>
            <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
            <p><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>

            <form method="POST" class="checkout-form" onsubmit="return validateCardFields()">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

                <label>Full Name:</label>
                <input type="text" name="name" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Phone Number:</label>
                <input type="tel" name="phone" required>

                <label>Shipping Address:</label>
                <textarea name="address" required></textarea>

                <label>Payment Method:</label>
                <select name="payment_method" id="paymentMethod" required onchange="toggleCardFields()">
                    <option value="">-- Select --</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="MasterCard">MasterCard</option>
                    <option value="Cash on Delivery">Cash on Delivery</option>
                </select>

                <div id="cardDetails" style="display: none;">
                    <label>Card Number:</label>
                    <input type="text" name="card_number" maxlength="16" pattern="\d{16}" placeholder="1234567812345678" inputmode="numeric">

                    <label>Expiry Date:</label>
                    <input type="month" name="expiry_date">

                    <label>CVV:</label>
                    <input type="text" name="cvv" maxlength="4" pattern="\d{3,4}" placeholder="123" inputmode="numeric">
                </div>

                <button type="submit" class="btn">Confirm Order</button>
            </form>
        </div>
    </main>
</div>

<script>
function toggleCardFields() {
    const method = document.getElementById('paymentMethod').value;
    const cardFields = document.getElementById('cardDetails');
    cardFields.style.display = (method === 'Credit Card' || method === 'Debit Card' || method === 'MasterCard') ? 'block' : 'none';
}

function validateCardFields() {
    const method = document.getElementById('paymentMethod').value;
    if (method === 'Credit Card' || method === 'Debit Card' || method === 'MasterCard') {
        const number = document.querySelector('[name="card_number"]').value.trim();
        const cvv = document.querySelector('[name="cvv"]').value.trim();

        if (number.length !== 16 || !/^\d{16}$/.test(number)) {
            alert("Please enter a valid 16-digit card number.");
            return false;
        }

        if (cvv.length < 3 || !/^\d{3,4}$/.test(cvv)) {
            alert("Please enter a valid 3 or 4-digit CVV.");
            return false;
        }
    }
    return true;
}
</script>

<?php include 'footer.php'; ?>
