<?php
// Connect to database using PDO
$dsn = 'mysql:host=localhost;dbname=Hms';
$username = 'root';
$password = 'switch';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Perform a basic database query
    $stmt = $pdo->query('SELECT * FROM users');
    
    // Fetch results
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "User: {$row['username']}, Email: {$row['email']}<br>";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>




<!-- Explanation
 
There are primarily two methods for connecting to a database in PHP: MySQLi (MySQL Improved) and PDO (PHP Data Objects).

MySQLi (MySQL Improved):
- MySQLi is an extension specifically designed to work with MySQL databases.
- It provides both procedural and object-oriented interfaces for interacting with the database.
- Supports prepared statements, which help prevent SQL injection.
- Offers support for asynchronous queries and transactions.

PDO (PHP Data Objects):
- PDO is a database access abstraction layer that provides a consistent interface for accessing different databases, including MySQL, PostgreSQL, SQLite, and more.
- Supports prepared statements and named parameters, enhancing security against SQL injection.
- Offers better portability as it allows developers to switch between different database systems without changing much of the code.

Differences between MySQLi and PDO:
- MySQLi is MySQL-specific, while PDO is database-agnostic.
- PDO supports multiple database systems, whereas MySQLi is limited to MySQL.
- MySQLi provides both procedural and object-oriented interfaces, while PDO primarily provides an object-oriented interface.
- PDO supports prepared statements and named parameters by default, making it more secure against SQL injection, while MySQLi requires explicit use of prepared statements for this purpose. -->

