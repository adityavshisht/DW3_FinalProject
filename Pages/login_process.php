<?php
session_start();
include 'database_connection.php'; // Provides the $pdo connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $redirectPage = $_POST['redirect'] ?? '/dw3/DW3_FinalProject/index.php';

    try {
		// Attempt to find a user with the provided email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
		
        // If user exists and password is correct, log them in
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['user_type'] = $user['user_type'];

            // Redirect to the original page
            header("Location: $redirectPage");
            exit();
        } else {
			// Invalid credentials
            echo "<script>alert('Invalid email or password.'); window.location.href='login.php?redirect=" . urlencode($redirectPage) . "';</script>";
        }
    } catch (PDOException $e) {
        // Optional: log the error internally for debugging
        // error_log($e->getMessage());
        echo "<script>alert('An error occurred during login. Please try again.'); window.location.href='login.php?redirect=" . urlencode($redirectPage) . "';</script>";
    }
}
?>