<?php
// Filename: create_documents_db.php

$dbFile = __DIR__ . '/documents.sqlite';

try {
    // Create (or open) the SQLite database
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL statement to create the documents table
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS documents (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            type TEXT NOT NULL,
            expiration_date TEXT NOT NULL CHECK(length(expiration_date) = 10),
            notes TEXT
        );
    ";

    // Execute the SQL command
    $db->exec($createTableSQL);

    echo "Database and table created successfully!";
} catch (PDOException $e) {
    echo "Error creating database or table: " . htmlspecialchars($e->getMessage());
}