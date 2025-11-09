<?php
// Filename: insert_documents.php

$dbFile = __DIR__ . '/documents.sqlite';

try {
    // Connect to the SQLite database
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Array of INSERT statements
    $insertStatements = [
        "INSERT INTO documents (name, type, expiration_date, notes) VALUES ('Driver''s License', 'License', '2024-01-15', 'Expired - needs renewal');",
        "INSERT INTO documents (name, type, expiration_date, notes) VALUES ('Passport', 'Passport', '2027-06-30', 'Valid for international travel');",
        "INSERT INTO documents (name, type, expiration_date, notes) VALUES ('Employment Contract', 'Contract', '2026-12-31', 'Signed with HR');",
        "INSERT INTO documents (name, type, expiration_date, notes) VALUES ('Health Insurance Card', 'Insurance', '2025-05-20', 'Renew annually');",
        "INSERT INTO documents (name, type, expiration_date, notes) VALUES ('Building Lease Agreement', 'Contract', '2025-11-01', 'Office space lease renewal due soon');"
    ];

    // Begin transaction for efficiency and atomicity
    $db->beginTransaction();

    foreach ($insertStatements as $sql) {
        $db->exec($sql);
    }

    $db->commit();

    echo "Records inserted successfully!";
} catch (PDOException $e) {
    // Roll back on error
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "Error inserting records: " . htmlspecialchars($e->getMessage());
}
