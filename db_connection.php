<?php
function getDbConnection() {
    try {
        // Path to the SQLite database file (same directory as this script)
        $dbPath = __DIR__ . '/documents.sqlite';
        
        // Create a new PDO connection
        $pdo = new PDO("sqlite:" . $dbPath);
        
        // Set error mode to exceptions
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
    } catch (PDOException $e) {
        // Handle connection errors
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Unable to connect to the database.");
    }
}