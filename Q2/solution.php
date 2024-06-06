<?php
// Include the database configuration
$config = require 'config.php';

// Database connection parameters
$host = $config['host'];
$dbname = $config['dbname'];
$username = $config['username'];
$password = $config['password'];

try {
    // Connect to MS SQL Server
    $pdo = new PDO("sqlsrv:Server=$host;Database=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Define a Payment class representing payments
class Payment {
    // Properties
    public $billCode;
    public $total;

    // Constructor
    public function __construct($billCode, $total) {
        $this->billCode = $billCode;
        $this->total = $total;
    }

    // Method to display payment details
    public function displayDetails() {
        echo "Bill Code: " . $this->billCode . "<br>";
        echo "Total: " . $this->total . "<br>";
    }
}

// Fetch payments from the database
try {
    $stmt = $pdo->query("SELECT TOP 10 bill_code, SUM(total) AS total FROM payments GROUP BY bill_code");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $payment = new Payment($row['bill_code'], $row['total']);
        $payment->displayDetails();
        echo "<br>";
    }
} catch (PDOException $e) {
    echo "Error fetching payments: " . $e->getMessage();
}
?>
