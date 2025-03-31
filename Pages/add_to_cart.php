<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $product_id = (int) $_GET['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    // Redirect to cart page after adding item
    header("Location: cart.php");
    exit();
}
?>
