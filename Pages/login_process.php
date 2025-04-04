<?php
session_start();
include 'database_connection.php'; // Assumes this provides $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $redirectPage = $_POST['redirect'] ?? 'index.php';

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['user_type'] = $user['user_type'];

            header("Location: $redirectPage");
            exit();
        } else {
            echo "<script>alert('Invalid email or password.'); window.location.href='login.php?redirect=" . urlencode($redirectPage) . "';</script>";
        }
    } catch (PDOException $e) {
        // For debugging, you could log: error_log($e->getMessage());
        echo "<script>alert('An error occurred during login. Please try again.'); window.location.href='login.php?redirect=" . urlencode($redirectPage) . "';</script>";
    }
}
?>