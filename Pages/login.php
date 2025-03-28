<?php
session_start();
include 'header.php';

// Handle incoming redirect from query param
$redirect = $_GET['redirect'] ?? 'index.php';
if (isset($_GET['id'])) {
    $redirect .= '?id=' . $_GET['id'];
}
?>

<div class="wrapper">
    <main>
        <h2>Login</h2>
        <form action="login_process.php" method="post">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

            <button type="submit" class="btn">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php" class="btn">Sign Up</a></p>
    </main>
</div>

<?php include 'footer.php'; ?>
