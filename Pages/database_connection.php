<?php
// Database connection settings
$type     = 'mysql';     // Database type
$server   = 'localhost'; // Host where the database is running
$db       = 'retechx';   // Name of the database
$port     = '3306';      // Default MySQL port
$charset  = 'utf8mb4';   // UTF-8 charset with support for emojis and special characters

// Credentials (This should be replace with own)
<<<<<<< HEAD
$username = 'viratkohli'; // Enter YOUR username here
$password = 'nZf[1n0P0OEH_vDZ';
=======
$username = 'shaillaja';          
$password = 'CCwx*PyuGERNBaFL';  
>>>>>>> 4c08e15769e9817180267880a99ae344277f010b

// PDO options for secure and consistent behavior
$options  = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false, // Use real prepared statements
];

// Create the Data Source Name (DSN) string
$dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";

// Attempt to establish a database connection
try {                                                        
    $pdo = new PDO($dsn, $username, $password, $options); // Create the PDO instance
}
catch (PDOException $e) { 
    // If connection fails, rethrow the exception with details                                   
    throw new PDOException($e->getMessage(), $e->getCode()); 
}
?>
