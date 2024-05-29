<?php
/**
 * Database Connection Configuration and Initialization
 *
 * This script sets up a PDO database connection based on the SQLite database file
 * specified in the .env file. It also configures PDO error handling.
 */

// Set strict types
declare(strict_types=1);

// require autoload
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database configuration
$database_file = $_ENV['DB_FILE'];

// PDO Database Connection
try {
    // Establish a connection to the SQLite database
    $db = new PDO("sqlite:$database_file");

    // Set the error mode to throw exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
    exit;
}
