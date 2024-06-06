<?php
// Include the database configuration
$config = require 'config.php';

// Database connection parameters
$host = $config['host'];
$dbname = $config['dbname'];
$username = $config['user'];
$password = $config['password'];
//I have provided the wrong db user to see the errors here, see the screenshot  provided in the Q 3 folder
try {
    // Connect to MS SQL Server
    $pdo = new PDO("sqlsrv:Server=$host;Database=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL query to fetch top 10 records of payments
    $sql = "SELECT TOP 10 bill_code, SUM(total) AS total FROM payments GROUP BY bill_code";
    $stmt = $pdo->query($sql);

    // Start table
    echo '<table>';

    // Table headers
    echo '<tr><th>Bill Code</th><th>Total</th></tr>';

    // Fetch the result
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr><td>' . $row['bill_code'] . '</td><td>$' . $row['total'] . '</td></tr>';
    }

    // End table
    echo '</table>';

} catch (PDOException $e) {
    // Print the caught error message
    echo "Failed to connect to the database. Please try again later.";
} catch (Exception $e) {
    // Print the caught error message
    echo "An error occurred: " . $e->getMessage();
}
?>
