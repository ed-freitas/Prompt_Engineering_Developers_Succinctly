<?php
function getDbConnection() {
    try {
        // Path to the SQLite database file (
        // same directory as this script)
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
function getAllDocuments() {
    try {
        // Establish database connection
        $pdo = getDbConnection();

        // Prepare SQL statement
        $stmt = $pdo->prepare("
            SELECT id, name, type, expiration_date, notes
            FROM documents
            ORDER BY expiration_date ASC
        ");

        // Execute the query
        $stmt->execute();

        // Fetch all results as associative arrays
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $documents;
    } catch (PDOException $e) {
        // Log and handle database errors
        error_log("Error fetching documents: " . $e->getMessage());
        return [];
    }
}

// 👇 Add this to actually fetch and display results
try {
    $documents = getAllDocuments();

    if (empty($documents)) {
        echo "No documents found in the database.";
    } else {
        echo "<pre>";
        print_r($documents);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}