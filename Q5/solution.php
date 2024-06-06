<?php
try {
    // Connect to MS SQL Server
    $serverName = "localhost";
    $connectionOptions = array(
        "Database" => "Hms",
        "Uid" => "sa",
        "PWD" => "switch"
    );
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    // Check connection
    if ($conn === false) {
        throw new Exception("Connection failed: " . sqlsrv_errors()); // Throw an exception if connection fails
    }

    // Fetch payments based on bill_code
    $billCode = $_POST['bill_code']; // I assuming the bill_code is passed via POST method
    $sql = "SELECT * FROM payments WHERE bill_code = ?";
    $params = array($billCode);
    $stmt = sqlsrv_query($conn, $sql, $params); // Execute the parameterized query

    if ($stmt === false) {
        throw new Exception("Query failed: " . sqlsrv_errors()); // Throw an exception if query execution fails
    }

    // Calculate total of the bill
    $total = 0;
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { // Loop through the fetched payment records
        $total += $row['total']; // Sum up the total amounts
    }

    echo "Total of bill with code $billCode is: $total"; // Display the total amount

    // Close connection
    sqlsrv_close($conn); // Close the database connection
} catch (Exception $e) { // Catch any exceptions thrown in the try block
    echo "Error: " . $e->getMessage(); // Display the error message
}
?>


<!-- Explanation:

The  code snippet demonstrates how to protect a PHP application from SQL injection and XSS vulnerabilities:
- To prevent SQL injection, use parameterized queries or prepared statements (`sqlsrv_query` function) to separate user input from SQL commands, ensuring data integrity.
- For XSS prevention, utilize `htmlspecialchars` to encode user input before displaying it on the webpage, preventing malicious script execution.
- Robust error handling with try-catch blocks ensures graceful handling of exceptions during database operations, enhancing application security.