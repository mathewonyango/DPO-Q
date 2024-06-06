<?php
// Load configuration securely
$config = require '.env';

// Establish a connection to the MS SQL Server
$connectionInfo = [
    "Database" => $config['dbname'],
    "UID" => $config['user'],
    "PWD" => $config['password']
];

try {
    $conn = sqlsrv_connect($config['host'], $connectionInfo);

    if ($conn === false) {
        throw new Exception("Could not connect to the database.");
    }

    // Define the SQL query to join the tables and check payment status
    $sql = "
        SELECT TOP 10
            b.bill_code,
            b.total AS bill_total,
            COALESCE(SUM(p.total), 0) AS total_paid,
            CASE 
                WHEN COALESCE(SUM(p.total), 0) >= b.total THEN 'Fully Paid'
                ELSE 'Not Fully Paid'
            END AS payment_status,
            o.order_id,
            STRING_AGG(oi.item_name, ', ') AS item_names
        FROM 
            [Hms].[dbo].[bills] b
        LEFT JOIN 
            [Hms].[dbo].[payments] p ON b.bill_code = p.bill_code
        LEFT JOIN
            [Hms].[dbo].[bill_has_orders] o ON b.bill_code = o.bill_id
        LEFT JOIN
            [Hms].[dbo].[order_items] oi ON o.order_id = oi.order_id
        GROUP BY 
            b.bill_code, b.total, o.order_id
        ORDER BY 
            b.bill_code
    ";

    // Execute the query using prepared statements
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        throw new Exception("Query execution failed.");
    }

    // Fetch the results
    $results = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $results[] = $row;
    }

    // Free the statement and close the connection
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($results);

} catch (Exception $e) {
    // Log the error details
    error_log($e->getMessage());

    // Return a generic error message to the client
    header('Content-Type: application/json', true, 500);
    echo json_encode(["error" => "An internal server error occurred."]);
}
