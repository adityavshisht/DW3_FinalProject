<?php
// Start a session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define helper only once to avoid redeclaration errors
if (!function_exists('protect_link')) {
    function protect_link($page) {
        return isset($_SESSION['user_id']) ? $page : 'login.php?redirect=' . urlencode($page);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReTechX - Buy & Sell Used Electronics</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <header>
        <h1>ReTechX</h1>
        <nav>
            <ul>
                <li><a href="/DW3_FinalProject/index.php">Home</a></li>
                <li><a href="<?= protect_link('sell.php') ?>">Sell</a></li>
                <li><a href="buy.php">Buy</a></li>
                <li><a href="<?= protect_link('profile.php') ?>">Profile</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Hide login if already logged in -->
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
