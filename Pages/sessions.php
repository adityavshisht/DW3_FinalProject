<?php
session_start();

// Check login status
$logged_in = $_SESSION['logged_in'] ?? false;
$username  = $_SESSION['username']  ?? '';
$user_id   = $_SESSION['user_id']   ?? '';
$user_type = $_SESSION['user_type'] ?? '';

function login($user) {
    session_regenerate_id(true); // Prevent session fixation
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id']   = $user['user_id'];
    $_SESSION['username']  = $user['forename']; // Using 'forename' from DB query
    $_SESSION['user_type'] = $user['user_type'];
}

function logout() {
    $_SESSION = []; // Clear all session variables

    // Get current session cookie parameters
    $params = session_get_cookie_params();

    // Expire the session cookie
    setcookie(
        'PHPSESSID',
        '',
        time() - (60 * 60 * 2), // Expire 2 hours ago
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );

    session_destroy(); // Destroy the session
}

function require_login($logged_in) {
    if (!$logged_in) {
        header('Location: login.php');
        exit;
    }
}
?>