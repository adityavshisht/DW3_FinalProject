<?php
session_start();
include 'header.php';
?>
<main>
    <h2>Sign Up</h2>
    <form action="signup_process.php" method="post">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Sign Up</button>
    </form>
</main>
<?php include 'footer.php'; ?>