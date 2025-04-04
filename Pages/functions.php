<?php

function pdo(PDO $pdo, string $sql, array $arguments = null) {
    if (!$arguments) {               // If no arguments provided
        return $pdo->query($sql);    // Execute query directly and return PDOStatement
    }
    
    $statement = $pdo->prepare($sql); // Prepare the SQL statement
    $statement->execute($arguments);  // Execute with provided arguments
    return $statement;                // Return PDOStatement object
}

?>