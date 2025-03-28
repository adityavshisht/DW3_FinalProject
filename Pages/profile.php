<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
?>
<main>
    <h2>Your Profile</h2>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
    <p>Manage your personal information and view your transactions.</p>
    <p><a href="logout.php" class="btn btn-logout">Logout</a></p>
</main>

<?php include 'footer.php'; ?>
