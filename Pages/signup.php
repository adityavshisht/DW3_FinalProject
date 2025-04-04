<?php
session_start();
include 'header.php';

// Capture optional redirect parameter (from login / checkout pages)
$redirect = $_GET['redirect'] ?? '';
?>

<div class="wrapper">
    <main>
        <h2>Sign Up</h2>

        <?php if (isset($_SESSION['signup_error'])): ?>
            <p style="color: red; text-align:center;"><?= htmlspecialchars($_SESSION['signup_error']) ?></p>
            <?php unset($_SESSION['signup_error']); // Remove the error after showing it ?>
        <?php endif; ?>

        <form action="signup_process.php" method="post">
            <?php if ($redirect): ?>
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
            <?php endif; ?>

            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn">Sign Up</button>
        </form>

        <p style="text-align:center; margin-top: 20px;">Already have an account? <a href="login.php<?php if ($redirect) echo '?redirect=' . urlencode($redirect); ?>">Log In</a></p>
    </main>
</div>

<?php include 'footer.php'; ?>