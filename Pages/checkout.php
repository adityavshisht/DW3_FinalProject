<?php
session_start();
include 'header.php';
include 'database_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}

// Make sure cart exists
if (empty($_SESSION['cart'])) {
    echo "<main><h2>Your cart is empty.</h2><a href='buy.php' class='btn'>Browse Products</a></main>";
    include 'footer.php';
    exit();
}

// Get products from database
$product_ids = implode(",", array_keys($_SESSION['cart']));
$sql = "SELECT * FROM products WHERE product_id IN ($product_ids)";
$result = $conn->query($sql);
$products = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $row['quantity'] = $_SESSION['cart'][$row['product_id']];
    $total += $row['price'] * $row['quantity'];
    $products[] = $row;
}

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment = $_POST['payment_method'];

    $buyer_id = $_SESSION['user_id'];
    $payment_status = "Paid";

    foreach ($products as $p) {
        $product_id = $p['product_id'];
        $seller_id = $p['user_id'];
        $price = $p['price'] * $p['quantity'];

        $stmt = $conn->prepare("INSERT INTO orders (buyer_id, product_id, seller_id, total_price, payment_status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiids", $buyer_id, $product_id, $seller_id, $price, $payment_status);
        $stmt->execute();
    }

    $_SESSION['cart'] = []; // clear cart
    echo "<script>alert('✅ Order placed successfully!'); window.location.href='profile.php';</script>";
    exit();
}
?>

<div class="wrapper">
    <main>
        <h2>Checkout</h2>

        <div class="checkout-card">
            <?php foreach ($products as $product): ?>
                <div style="border-bottom: 1px solid #ccc; margin-bottom: 15px; padding-bottom: 10px;">
                    <img src="../imgs/<?= htmlspecialchars($product['image']) ?>" class="checkout-image" alt="Product Image">
                    <h4><?= htmlspecialchars($product['title']) ?></h4>
                    <p><strong>Qty:</strong> <?= $product['quantity'] ?> &nbsp; | &nbsp; 
                       <strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                </div>
            <?php endforeach; ?>

            <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>

            <form method="POST" class="checkout-form" onsubmit="return validateCardFields()">
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
