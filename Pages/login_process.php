<?php
session_start();
include 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $redirectPage = $_POST['redirect'] ?? 'index.php';

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['user_type'] = $user['user_type'];

        header("Location: $redirectPage");
        exit();
    } else {
        echo "<script>alert('Invalid email or password.'); window.location.href='login.php?redirect=" . urlencode($redirectPage) . "';</script>";
    }
}
?>
