<?php
session_start();
include 'database_connection.php'; // Assumes this provides $pdo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $check = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $check->execute([':email' => $email]);
        
        if ($check->rowCount() > 0) {
            echo "<script>alert('Email already exists.'); window.location.href='signup.php';</script>";
        } else {
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type) VALUES (:name, :email, :password, 'user')");
            $success = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword
            ]);

            if ($success) {
                echo "<script>alert('Account created successfully! Please log in.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Signup failed. Try again.'); window.location.href='signup.php';</script>";
            }
        }
    } catch (PDOException $e) {
        // For debugging, you could log: error_log($e->getMessage());
        echo "<script>alert('An error occurred during signup. Please try again.'); window.location.href='signup.php';</script>";
    }
}
?>