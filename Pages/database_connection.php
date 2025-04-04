<?php
$type     = 'mysql';     // Type of database
$server   = 'localhost'; // Server the database is on
$db       = 'retechx';   // Name of the database
$port     = '3306';      // Port is usually 8889 in MAMP and 3306 in XAMPP
$charset  = 'utf8mb4';   // UTF-8 encoding using 4 bytes of data per character

$username = 'shaillaja';          // Enter YOUR username here
$password = 'CCwx*PyuGERNBaFL';  // Enter YOUR password here

// Options for how PDO works
$options  = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create DSN
$dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";

try {                                                        // Try following code
    $pdo = new PDO($dsn, $username, $password, $options);    // Create PDO object
}
catch (PDOException $e) {                                    // If exception thrown
    throw new PDOException($e->getMessage(), $e->getCode()); // Re-throw exception
}
?>