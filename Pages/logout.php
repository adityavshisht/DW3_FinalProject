<?php
include 'sessions.php';

// Destroy the session and log the user out
session_destroy();

// Redirect back to the homepage
header('Location: /dw3/DW3_FinalProject/index.php');
exit();