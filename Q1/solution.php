<?php
// Retrieve database configuration
$config = require 'config.php';

// Database connection parameters
$host = $config['host'];
$dbname = $config['dbname'];
$user = $config['user'];
$password = $config['password'];

echo '<style>
    table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ddd;
        font-family: Arial, sans-serif;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
        color: #333;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    h2 {
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
        color: #333;
    }
    .dpo-interview {
        margin-top: 30px;
        font-style: italic;
        color: #666;
    }
</style>';

// echo '<h2>Payments of the Top 10 Bills</h2>'; // Title

echo '<table>'; // Start table

try {
    // Connect to MS SQL Server
    $pdo = new PDO("sqlsrv:Server=$host;Database=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare SQL query to fetch top 10 records of payments
    $sql = "SELECT TOP 10 ROW_NUMBER() OVER (ORDER BY SUM(total) DESC) AS row_num, bill_code, SUM(total) AS bill_total FROM payments GROUP BY bill_code ORDER BY bill_total DESC";
    $stmt = $pdo->query($sql);
    
    // Table headers
    echo '<tr><th>#</th><th>Bill Code</th><th>Total</th></tr>';
    
    // Fetch the result
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr><td>' . $row['row_num'] . '</td><td>' . $row['bill_code'] . '</td><td>ksh.' . $row['bill_total'] . '</td></tr>';
    }
    
    echo '</table>'; // End table
    
    // DPO Interview
    echo '<div class="dpo-interview">DPO interview: with Mathews Onyango.</div>';
} catch (PDOException $e) {
    // Handle database connection errors
    echo 'Connection failed: ' . $e->getMessage(); // Using single quotes
} catch (Exception $e) {
    // Handle other exceptions
    echo "An error occurred: {$e->getMessage()}"; // Using double quotes
}
?>
