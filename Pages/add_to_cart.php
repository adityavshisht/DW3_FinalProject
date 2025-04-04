<?php
session_start();
include 'header.php'; // Optional, for consistency with other pages

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $product_id = (int) $_GET['product_id'];

    // Ensure cart session exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add or increment product in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    // Redirect to cart page after adding item
    header("Location: cart.php");
    exit();
} else {
    // Optional: Handle invalid requests
    echo "<p style='color: red; text-align:center;'>Invalid request. Please select a product to add to your cart.</p>";
}

include 'footer.php'; // Optional, for consistency with other pages
?>