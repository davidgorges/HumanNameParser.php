<?php

require_once __DIR__ . '/../vendor/autoload.php';

use HumanNameParser\Parser;

// Check if a name was provided as a command-line argument
if ($argc > 1) {
    // Get the name from the command-line arguments
    $nameString = implode(' ', array_slice($argv, 1));
} else {
    // Example names to parse
    $examples = [
        "John Smith",
        "Dr. Jane Smith",
        "Smith, John",
        "John William Smith",
        "John Smith Jr.",
        "Dr. John W. ('Johnny') Smith-Brown, III",
        "Vincent van Gogh",
        "James C. O'Neill",
        "Björn O'Malley, Jr."
    ];
    
    // Let user choose an example or enter their own name
    echo "Choose an example to parse:\n";
    foreach ($examples as $i => $example) {
        echo ($i + 1) . ". $example\n";
    }
    echo ($i + 2) . ". Enter your own name\n";
    echo "Your choice (1-" . ($i + 2) . "): ";
    
    $choice = (int) trim(fgets(STDIN));
    
    if ($choice === count($examples) + 1) {
        echo "Enter a name to parse: ";
        $nameString = trim(fgets(STDIN));
    } elseif ($choice > 0 && $choice <= count($examples)) {
        $nameString = $examples[$choice - 1];
    } else {
        echo "Invalid choice. Using first example.\n";
        $nameString = $examples[0];
    }
}

// Create a new Parser instance
$parser = new Parser();

try {
    // Parse the name
    $name = $parser->parse($nameString);
    
    // Display the results
    echo "\nParsing results for: " . $nameString . "\n";
    echo str_repeat("-", 40) . "\n";
    echo "Academic Title: " . ($name->getAcademicTitle() ?? "N/A") . "\n";
    echo "Leading Initial: " . ($name->getLeadingInitial() ?? "N/A") . "\n";
    echo "First Name: " . ($name->getFirstName() ?? "N/A") . "\n";
    echo "Nicknames: " . ($name->getNicknames() ?? "N/A") . "\n";
    echo "Middle Name: " . ($name->getMiddleName() ?? "N/A") . "\n";
    echo "Last Name: " . ($name->getLastName() ?? "N/A") . "\n";
    echo "Suffix: " . ($name->getSuffix() ?? "N/A") . "\n";
    
} catch (Exception $e) {
    echo "Error parsing name: " . $e->getMessage() . "\n";
}