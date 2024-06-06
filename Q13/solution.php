<?php
// Define the file path
$filePath = 'data.json';

// Read JSON data from the file
$jsonData = file_get_contents($filePath);

// Compress the JSON data
$compressedData = gzcompress($jsonData);

// Save the compressed data to a file
$compressedFilePath = 'compressed_data.json';
file_put_contents($compressedFilePath, $compressedData);

echo "Data has been compressed and saved to the file '{$compressedFilePath}'." . PHP_EOL;

// Read the compressed data from the file
$compressedDataFromFile = file_get_contents($compressedFilePath);

// Decompress the data
$decompressedData = gzuncompress($compressedDataFromFile);

// Decode the JSON data
$decodedData = json_decode($decompressedData, true);

echo "Data has been read from the file, decompressed, and decoded:" . PHP_EOL;
print_r($decodedData);
?>
