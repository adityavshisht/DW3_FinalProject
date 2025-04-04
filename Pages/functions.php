<?php

/**
 * Helper function to simplify PDO queries.
 * If arguments are provided, it prepares and executes the query safely.
 * If not, it runs the query directly.
 */
function pdo(PDO $pdo, string $sql, array $arguments = null) {
    if (!$arguments) { 
        // No arguments — run the query as-is	
        return $pdo->query($sql);    
    }
    
	// Use a prepared statement when arguments are passed
    $statement = $pdo->prepare($sql); 
    $statement->execute($arguments);  
    return $statement;                
}

?>