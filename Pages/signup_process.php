<?php
session_start();
include 'database_connection.php'; // Assumes this file provides the $pdo connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Capture submitted form data
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
	
	// Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if a user with the same email already exists
        $check = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $check->execute([':email' => $email]);
        
        if ($check->rowCount() > 0) {
			// Email is already registered
            echo "<script>alert('Email already exists.'); window.location.href='signup.php';</script>";
        } else {
            // Insert the new user into the database
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, user_type) VALUES (:name, :email, :password, 'user')");
            $success = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword
            ]);

            if ($success) {
				// Signup successful, redirect to login page
                echo "<script>alert('Account created successfully! Please log in.'); window.location.href='login.php';</script>";
            } else {
				// Insert failed, show error
                echo "<script>alert('Signup failed. Try again.'); window.location.href='signup.php';</script>";
            }
        }
    } catch (PDOException $e) {
        // You can optionally log the error for debugging: error_log($e->getMessage());
        echo "<script>alert('An error occurred during signup. Please try again.'); window.location.href='signup.php';</script>";
    }
}
?>