<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'header.php';
include 'database_connection.php';

$userId = $_SESSION['user_id'];
$message = "";
$redirect = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        header("Location: profile.php");
        exit();
    }

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $updateSql = "UPDATE users SET name = :name, phone = :phone, address = :address WHERE user_id = :userId";
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':address' => $address,
        ':userId' => $userId
    ]);

    $message = "Profile updated successfully!";
	$redirect = true; // Trigger redirect in the page
}

// Fetch existing user data
$userSql = "SELECT name, email, phone, address FROM users WHERE user_id = :userId";
$stmt = $pdo->prepare($userSql);
$stmt->execute([':userId' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<main style="max-width: 600px; margin: 40px auto;">
    <h2>Edit Profile</h2>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?= $message ?></p>
		<?php if ($redirect): ?>
            <meta http-equiv="refresh" content="3;url=profile.php">
        
        <?php endif; ?>
    <?php endif; ?>

    <form method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br><br>

        <label for="email">Email (read-only):</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly><br><br>

        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"><br><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address"><?= htmlspecialchars($user['address']) ?></textarea><br><br>

        <button type="submit" name="save" class="btn">Save</button>
        <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
    </form>
</main>

<?php include 'footer.php'; ?>
