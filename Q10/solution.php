<?php

require __DIR__ . '/vendor/autoload.php'; // Load dotenv library

try {
    // Load environment variables from .env file
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Get API URL, username, and password from environment variables
    $url = $_ENV['API_URL'];
    $username = $_ENV['API_USERNAME'];
    $password = $_ENV['API_PASSWORD'];

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Basic " . base64_encode("$username:$password")
    ));

    // Execute cURL session
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        throw new Exception('Failed to fetch data: ' . curl_error($ch));
    }

    // Decode JSON response
    $data = json_decode($response, true);

    // Check if JSON decoding was successful
    if ($data === null) {
        throw new Exception('Failed to decode JSON response.');
    }

    // Iterate through the first 10 items and display name, age, and gender
    for ($i = 0; $i < 10 && $i < count($data); $i++) {
        $item = $data[$i];
        echo 'Name: ' . $item['name'] . '<br>';
        echo 'Age: ' . $item['age'] . '<br>';
        echo 'Gender: ' . $item['gender'] . '<br>';
        echo '<br>';
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>
