<?php
session_start();

// Get session values, or fallback to defaults if not set
$logged_in = $_SESSION['logged_in'] ?? false;
$username  = $_SESSION['username']  ?? '';
$user_id   = $_SESSION['user_id']   ?? '';
$user_type = $_SESSION['user_type'] ?? '';

function login($user) {
	// Regenerate session ID to prevent session fixation attacks
    session_regenerate_id(true); 
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id']   = $user['user_id'];
    $_SESSION['username']  = $user['forename']; 
    $_SESSION['user_type'] = $user['user_type'];
}

//Logs the user out by clearing session and expiring the session cookie
function logout() {
	// Clear session variables
    $_SESSION = []; 

    // Get parameters of the session cookie to properly expire it
    $params = session_get_cookie_params();

    // Expire the cookie by setting it in the past
    setcookie(
        'PHPSESSID',
        '',
        time() - (60 * 60 * 2), // Set expiration 2 hours in the past
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
	
    // Finally destroy the session on the server
    session_destroy(); 
}

//Redirects to login page 
function require_login($logged_in) {
    if (!$logged_in) {
        header('Location: login.php');
        exit;
    }
}
?>