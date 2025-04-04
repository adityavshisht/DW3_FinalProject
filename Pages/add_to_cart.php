<?php
session_start();
include 'header.php'; // Included for layout consistency

// Check if a product ID was provided via GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $product_id = (int) $_GET['product_id'];

    // Initialize the cart if it doesn't already exist in the session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If the product is already in the cart, increase the quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
		// Otherwise, add it with quantity 1
        $_SESSION['cart'][$product_id] = 1;
    }

    // Redirect to the cart page after adding the product
    header("Location: cart.php");
    exit();
} else {
    // Display an error if the request is invalid (e.g. no product selected)
    echo "<p style='color: red; text-align:center;'>Invalid request. Please select a product to add to your cart.</p>";
}

include 'footer.php'; // Included for layout consistency
?>