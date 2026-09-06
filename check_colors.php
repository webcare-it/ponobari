<?php
// Simple script to check website color values in the database

// Database configuration (adjust these values according to your .env file)
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query to get the first general setting record
    $stmt = $pdo->query("SELECT website_primary_color, website_secondary_color FROM general_settings LIMIT 1");
    $setting = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($setting) {
        echo "Website Primary Color: " . ($setting->website_primary_color ?: 'Not set') . "\n";
        echo "Website Secondary Color: " . ($setting->website_secondary_color ?: 'Not set') . "\n";
    } else {
        echo "No general settings record found.\n";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>