<?php
/**
 * crud.php
 * Complete SQLite CRUD operations for the 'documents' table.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Establishes a connection to the SQLite database.
 *
 * @return PDO
 * @throws Exception if the connection fails
 */
function getDbConnection(): PDO {
    try {
        $dbPath = __DIR__ . '/documents.sqlite';
        $pdo = new PDO("sqlite:" . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Unable to connect to the database.");
    }
}

/**
 * Retrieves all document records from the 'documents' table.
 *
 * @return array Returns an array of associative arrays (documents)
 */
function getAllDocuments(): array {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("
            SELECT id, name, type, expiration_date, notes 
            FROM documents 
            ORDER BY expiration_date ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching documents: " . $e->getMessage());
        return [];
    }
}

/**
 * Retrieves a single document by its ID.
 *
 * @param int $id The document ID
 * @return array|null Returns the document as an associative array, or null if not found
 */
function getDocumentById(int $id): ?array {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("
            SELECT id, name, type, expiration_date, notes 
            FROM documents 
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $document = $stmt->fetch(PDO::FETCH_ASSOC);
        return $document !== false ? $document : null;
    } catch (PDOException $e) {
        error_log("Error fetching document by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Inserts a new document into the 'documents' table.
 *
 * @param string $name
 * @param string $type
 * @param string $expirationDate (YYYY-MM-DD format)
 * @param string|null $notes
 * @return bool True on success, False on failure
 */
function addDocument(string $name, string $type, string $expirationDate, ?string $notes = null): bool {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("
            INSERT INTO documents (name, type, expiration_date, notes)
            VALUES (:name, :type, :expiration_date, :notes)
        ");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':expiration_date', $expirationDate);
        $stmt->bindParam(':notes', $notes);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error inserting document: " . $e->getMessage());
        return false;
    }
}

/**
 * Updates an existing document in the 'documents' table.
 *
 * @param int $id
 * @param string $name
 * @param string $type
 * @param string $expirationDate
 * @param string|null $notes
 * @return bool True on success, False on failure
 */
function updateDocument(int $id, string $name, string $type, string $expirationDate, ?string $notes = null): bool {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("
            UPDATE documents 
            SET name = :name, type = :type, expiration_date = :expiration_date, notes = :notes
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':expiration_date', $expirationDate);
        $stmt->bindParam(':notes', $notes);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error updating document: " . $e->getMessage());
        return false;
    }
}

/**
 * Deletes a document from the 'documents' table by ID.
 *
 * @param int $id
 * @return bool True on success, False on failure
 */
function deleteDocument(int $id): bool {
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare("DELETE FROM documents WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error deleting document: " . $e->getMessage());
        return false;
    }
}

/**
 * ------------------------------
 * TEST SECTION (manual testing)
 * ------------------------------
 */

// Uncomment one block at a time to test specific functionality

// 1️⃣ Add a new document
/*
$added = addDocument("Software License", "License", "2027-03-15", "Annual renewal required");
echo $added ? "✅ Document added successfully!\n" : "❌ Failed to add document.\n";
*/

// 2️⃣ Get all documents
/*
$documents = getAllDocuments();
echo "<h3>All Documents</h3><pre>";
print_r($documents);
echo "</pre>";
*/

// 3️⃣ Get a single document by ID
/*
$doc = getDocumentById(2);
echo "<h3>Document with ID 2</h3><pre>";
print_r($doc);
echo "</pre>";
*/

// 4️⃣ Update a document
/*
$updated = updateDocument(2, "Updated Passport", "Passport", "2028-01-01", "Renewed recently");
echo $updated ? "✅ Document updated successfully!\n" : "❌ Failed to update document.\n";
*/

// 5️⃣ Delete a document
/*
$deleted = deleteDocument(5);
echo $deleted ? "✅ Document deleted successfully!\n" : "❌ Failed to delete document.\n";
*/