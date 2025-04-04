<?php
$type     = 'mysql';     // Type of database
$server   = 'localhost'; // Server the database is on
$db       = 'retechx';       // Name of the database
$port     = '3306';      // Port is usually 8889 in MAMP and 3306 in XAMPP
$charset  = 'utf8mb4';   // UTF-8 encoding using 4 bytes of data per character

$username = 'harbhajansinghbhajji'; // Enter YOUR username here
$password = 'ov5WmRg)QKqY_]Os'; // Enter YOUR password here

$conn = new mysqli($server, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
