<?php

// Function to count words in a file
function countWordsInFile($filename) {
    // Check if the file exists and is readable
    if (!file_exists($filename) || !is_readable($filename)) {
        echo "Error: Unable to open the file.";
        return false;
    }

    // Attempt to open the file
    $fileHandle = fopen($filename, "r");
    if (!$fileHandle) {
        echo "Error: Unable to open the file.";
        return false;
    }

    // Initialize word count
    $wordCount = 0;

    // Read the file line by line
    while (!feof($fileHandle)) {
        $line = fgets($fileHandle);
        // Increment word count by the number of words in the line
        $wordCount += str_word_count($line);
    }

    // Close the file handle
    fclose($fileHandle);

    // Display the word count
    echo "Number of words in the file: " . $wordCount;
}

// Usage example
$filename = "E:/xampp-windows-x64-8.2.12-0-VS16/xampp/htdocs/DPO/Q11/Q11.txt";

countWordsInFile($filename);

?>
