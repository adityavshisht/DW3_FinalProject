<?php
session_start();
include 'header.php';
?>
<main>
    <h2>Login</h2>
    <form action="login_process.php" method="post">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</main>
<?php include 'footer.php'; ?>